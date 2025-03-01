<?php

namespace App\Http\Controllers\Konfigurasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Konfigurasi\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    private $title          = 'Konfigurasi';
    private $subtitle       = 'Role Manajemen';
    private $formView       = 'pages.konfigurasi.roles.roles-form';
    private $indexView      = 'pages.konfigurasi.roles.roles';
    private $urlStore       = 'konfigurasi.roles.store';
    private $urlUpdate      = 'konfigurasi.roles.update';
    private $urlLink        = 'konfigurasi.menu.index';
    private $urlData        = 'konfigurasi.roles.data';
    private $tabel          = 'tableroles';

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

    public function getData(Request $request)
    {
        Gate::authorize('read konfigurasi/roles');

        // Ambil query pencarian
        $search = $request->input('search');

        // Menggunakan eloquent untuk mendukung server-side processing
        $roles   = Role::orderBy('id', 'DESC');

        // Filter berdasarkan pencarian jika ada
        if (!empty($search)) {
            $search = strtolower($search);

            // Pencarian untuk kolom lain
            $roles->where('name', 'like', "%{$search}%");
        }


        return DataTables::eloquent($roles)
            ->addColumn('action', function ($row) {

                $routes = [
                    'edit' => route('konfigurasi.roles.edit', $row->id),
                    'hapus' => route('konfigurasi.roles.destroy', $row->id)
                ];

                $actions = $this->generateButtons('konfigurasi/roles', $routes);

                return '<center>' . $actions . '</center>';
            })
            ->addIndexColumn() // untuk menampilkan nomor urut (index)
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Role $role)
    {
        return view($this->formView, [
            'action'    => route($this->urlStore),
            'data'      => $role,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request, Role $role)
    {
        $role  = new Role($request->validated());
        $role->save();

        return responseSuccess();
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
