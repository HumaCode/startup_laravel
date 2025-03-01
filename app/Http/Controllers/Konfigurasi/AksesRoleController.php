<?php

namespace App\Http\Controllers\Konfigurasi;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Repositories\MenuRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class AksesRoleController extends Controller
{
    private $title          = 'Konfigurasi';
    private $subtitle       = 'Akses Role Manajemen';
    private $formView       = 'pages.konfigurasi.aksesrole.akses-role-form';
    private $indexView      = 'pages.konfigurasi.aksesrole.akses-role';
    private $itemView       = 'pages.konfigurasi.aksesrole.akses-role-items';
    private $urlStore       = 'konfigurasi.akses-role.store';
    private $urlUpdate      = 'konfigurasi.akses-role.update';
    private $urlLink        = 'konfigurasi.akses-role.index';
    private $urlData        = 'konfigurasi.akses-role.data';
    private $tabel          = 'tableaksesrole';

    public function __construct(private MenuRepository $repository)
    {
        $this->repository = $repository;
    }

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
        Gate::authorize('read konfigurasi/akses-role');

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
                    'edit' => route('konfigurasi.akses-role.edit', $row->id),
                ];

                $actions = $this->generateButtons('konfigurasi/akses-role', $routes);

                return '<center>' . $actions . '</center>';
            })
            ->addIndexColumn() // untuk menampilkan nomor urut (index)
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getPermissionByRole(Role $role)
    {
        Gate::authorize('update konfigurasi/akses-role');

        return view($this->itemView, [
            'data'      => $role,
            'menus'     => $this->repository->getMainMenuWithPermissions(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $roles  = Role::where('id', '!=', $role->id)->get()->pluck('id', 'name');

        return view($this->formView, [
            'action'    => route($this->urlUpdate, $role->id),
            'data'      => $role,
            'menus'     => $this->repository->getMainMenuWithPermissions(),
            'roles'     => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions);

        return responseSuccess(true);
    }
}
