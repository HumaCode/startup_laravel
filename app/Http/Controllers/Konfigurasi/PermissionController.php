<?php

namespace App\Http\Controllers\Konfigurasi;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    private $title          = 'Konfigurasi';
    private $subtitle       = 'Permission Manajemen';
    private $formView       = 'pages.konfigurasi.permission.permission-form';
    private $indexView      = 'pages.konfigurasi.permission.permission';
    private $urlStore       = 'konfigurasi.permission.store';
    private $urlUpdate      = 'konfigurasi.permissions.update';
    private $urlLink        = 'konfigurasi.permissions.index';
    private $urlData        = 'konfigurasi.permissions.data';
    private $tabel          = 'tablepermission';

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
        Gate::authorize('read konfigurasi/permissions');

        // Ambil query pencarian
        $search = $request->input('search');

        // Menggunakan eloquent untuk mendukung server-side processing
        $permission   = Permission::orderBy('id', 'DESC');

        // Filter berdasarkan pencarian jika ada
        if (!empty($search)) {
            $search = strtolower($search);

            // Pencarian untuk kolom lain
            $permission->where('name', 'like', "%{$search}%");
        }


        return DataTables::eloquent($permission)
            ->addColumn('action', function ($row) {

                $routes = [
                    'edit' => route('konfigurasi.permissions.edit', $row->id),
                    'hapus' => route('konfigurasi.permissions.destroy', $row->id)
                ];

                $actions = $this->generateButtons('konfigurasi/permissions', $routes);

                return '<center>' . $actions . '</center>';
            })
            ->addIndexColumn() // untuk menampilkan nomor urut (index)
            ->rawColumns(['action'])
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
