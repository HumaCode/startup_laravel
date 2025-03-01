<?php

namespace App\Http\Controllers\Konfigurasi;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    private $title          = 'Konfigurasi';
    private $subtitle       = 'User Manajemen';
    private $formView       = 'pages.konfigurasi.users.users-form';
    private $indexView      = 'pages.konfigurasi.users.users';
    private $urlStore       = 'konfigurasi.users.store';
    private $urlUpdate      = 'konfigurasi.users.update';
    private $urlLink        = 'konfigurasi.users.index';
    private $urlData        = 'konfigurasi.users.data';
    private $tabel          = 'tableusers';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title'         => $this->title,
            'subtitle'      => $this->subtitle,
            'link'          => route($this->urlLink),
            'urlData'       => route($this->urlData),
            'datatableId'   => $this->tabel,
        ];

        return view($this->indexView, $data);
    }

    public function aktivasiUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->is_active == '0') {
            $stts = '1';
            $alert = 'Berhasil diaktifkan';
        } else {
            $stts = '0';
            $alert = 'Berhasil di non aktifkan';
        }

        $user->is_active = $stts;
        $user->save();

        return back()->with('success', $alert);
    }

    public function getData(Request $request)
    {
        Gate::authorize('read konfigurasi/users');

        // Ambil query pencarian
        $search = $request->input('search');

        // Menggunakan eloquent untuk mendukung server-side processing
        $users   = User::orderBy('id', 'DESC');

        // Filter berdasarkan pencarian jika ada
        if (!empty($search)) {
            $search = strtolower($search);

            // Pencarian untuk kolom lain
            $users->where('name', 'like', "%{$search}%");
        }


        return DataTables::eloquent($users)
            ->addColumn('action', function ($row) {

                if ($row->is_active == '1') {
                    // Tombol Nonaktifkan User jika user memiliki izin
                    $actions = user()->can('aktivasi konfigurasi/users') ?
                        '<a href="' . route('konfigurasi.aktivasi.user', $row->id) . '" class="btn btn-danger btn-border btn-sm hover-scale" id="activateUserLink">Non Aktifkan User</a> &nbsp;'
                        : '';

                    // Buat array routes untuk tombol Edit dan Hapus
                    $routes = [
                        'detail'    => route('konfigurasi.users.show', $row->id),
                        'edit'      => route('konfigurasi.users.edit', $row->id),
                    ];

                    // Cek apakah user yang sedang login adalah dirinya sendiri
                    if ($row->id === user()->id) {
                        // Tombol hapus dalam keadaan disabled
                        $hapusButton = '<button class="btn btn-danger btn-border btn-sm hover-scale" disabled style="cursor: not-allowed; opacity: 0.6;"><i class="fas fa-trash"></i></button>';
                    } else {
                        $routes['hapus'] = route('konfigurasi.users.destroy', $row->id);
                    }

                    $actions .= $this->generateButtons('konfigurasi/users', $routes);

                    // Jika tombol hapus disabled, tambahkan secara manual
                    if (!isset($routes['hapus'])) {
                        $actions .= $hapusButton;
                    }
                } else {
                    // Tombol Aktifkan User jika user memiliki izin
                    $actions = user()->can('aktivasi konfigurasi/users') ?
                        '<a href="' . route('konfigurasi.aktivasi.user', $row->id) . '" class="btn btn-primary btn-border btn-sm hover-scale" id="activateUserLink">Aktifkan User</a> &nbsp;'
                        : '';

                    // Buat array routes untuk tombol Edit dan Hapus
                    $routes = [
                        'edit'      => route('konfigurasi.users.edit', $row->id),
                        'detail'    => route('konfigurasi.users.show', $row->id),
                    ];

                    // Cek apakah user yang sedang login adalah dirinya sendiri
                    if ($row->id === user()->id) {
                        // Tombol hapus dalam keadaan disabled
                        $hapusButton = '<button class="btn btn-danger btn-border btn-sm hover-scale" disabled style="cursor: not-allowed; opacity: 0.6;"><i class="fas fa-trash"></i></button>';
                    } else {
                        $routes['hapus'] = route('konfigurasi.users.destroy', $row->id);
                    }

                    $actions .= $this->generateButtons('konfigurasi/users', $routes);

                    // Jika tombol hapus disabled, tambahkan secara manual
                    if (!isset($routes['hapus'])) {
                        $actions .= $hapusButton;
                    }
                }

                return '<center>' . $actions . '</center>';
            })
            ->addColumn('user', function ($row) {
                $fotoUrl = $row->foto == null ? asset('img/noimage.png') : Storage::url($row->foto);

                $user =  '
                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-45px me-5">
                                            <img src="' . $fotoUrl . '"
                                                alt="">
                                        </div>


                                        <div class="d-flex justify-content-start flex-column">

                                            <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-6">
                                                <li class="d-flex align-items-center">
                                                    <span class="bullet bg-info"></span> &nbsp;' . $row->name . '
                                                </li>
                                            </a>

                                            <span class="text-muted fw-semibold text-muted d-block fs-7">
                                                <li class="d-flex align-items-center">
                                                    <span class="bullet bg-info"></span> &nbsp;' . $row->username . '
                                                </li>
                                            </span>
                                            
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">
                                                <li class="d-flex align-items-center ">
                                                    <span class="bullet bg-info"></span> &nbsp;' . $row->email . '
                                                </li>
                                            </span>
                                        </div>
                                    </div>
                ';


                return $user;
            })

            ->addColumn('role', function ($row) {
                $roleName = $row->getRoleNames()->first(); // Ambil role pertama
                $badgeColor = 'secondary'; // Default untuk "user"

                // Tentukan warna badge berdasarkan role
                if ($roleName === 'administrator') {
                    $badgeColor = 'primary';
                } elseif ($roleName === 'admin') {
                    $badgeColor = 'success';
                }

                $roleBadge = '<span class="badge badge-' . $badgeColor . '">' . $roleName . '</span>';

                return '<center>' . $roleBadge . '</center>';
            })
            ->addColumn('telp', function ($row) {
                $telp = $row->telp ?? ' <span class="align-items-center py-1">
                                            <span class="bullet bg-danger"></span>
                                        </span>';

                return '<center>' . $telp . '</center>';
            })
            ->addColumn('jk', function ($row) {
                $jk = $row->jk ?? '<span class="align-items-center py-1">
                                            <span class="bullet bg-danger"></span>
                                        </span>';

                return '<center>' . $jk . '</center>';
            })
            ->addColumn('status', function ($row) {

                if ($row->is_active == 0) {
                    $stts = '<span class="badge badge-danger">Tidak Aktif</span>';
                } else {
                    $stts = '<span class="badge badge-primary">Aktif</span>';
                }

                return '<center>' . $stts . '</center>';
            })
            ->addIndexColumn() // untuk menampilkan nomor urut (index)
            ->rawColumns(['action', 'user', 'role', 'telp', 'jk', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
