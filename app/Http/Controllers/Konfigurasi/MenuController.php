<?php

namespace App\Http\Controllers\Konfigurasi;

use App\Http\Controllers\Controller;
use App\Models\Konfigurasi\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    private $title          = 'Konfigurasi';
    private $subtitle       = 'Menu Manajemen';
    private $formView       = 'pages.konfigurasi.menu.menu-form';
    private $indexView      = 'pages.konfigurasi.menu.menu';
    private $tabel          = 'tablemenu';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title'         => $this->title,
            'subtitle'      => $this->subtitle,
            'link'          => route('konfigurasi.menu.index'),
            'datatableId'   => $this->tabel,
        ];

        return view($this->indexView, $data);
    }

    public function getData(Request $request)
    {
        Gate::authorize('read konfigurasi/menu');

        // Ambil query pencarian
        $search = $request->input('search');

        // Menggunakan eloquent untuk mendukung server-side processing
        $menu   = Menu::select(['id', 'name', 'url', 'category', 'orders'])
            ->orderBy('id', 'DESC');

        // Filter berdasarkan pencarian jika ada
        if (!empty($search)) {
            $search = strtolower($search);

            // Pencarian untuk kolom lain
            $menu->where('name', 'like', "%{$search}%");
        }


        return DataTables::eloquent($menu)
            ->addColumn('action', function ($row) {

                $routes = [
                    'edit' => route('konfigurasi.menu.edit', $row->id),
                ];

                $actions = $this->generateButtons('konfigurasi/menu', $routes);

                return '<center>' . $actions . '</center>';
            })
            ->addIndexColumn() // untuk menampilkan nomor urut (index)
            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

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
