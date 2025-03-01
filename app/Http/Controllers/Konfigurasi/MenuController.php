<?php

namespace App\Http\Controllers\Konfigurasi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Konfigurasi\MenuRequest;
use App\Models\Konfigurasi\Menu;
use App\Models\Permission;
use App\Repositories\MenuRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Mavinoo\Batch\BatchFacade;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    private $title          = 'Konfigurasi';
    private $subtitle       = 'Menu Manajemen';
    private $formView       = 'pages.konfigurasi.menu.menu-form';
    private $indexView      = 'pages.konfigurasi.menu.menu';
    private $urlStore       = 'konfigurasi.menu.store';
    private $urlUpdate      = 'konfigurasi.menu.update';
    private $urlLink        = 'konfigurasi.menu.index';
    private $urlData        = 'konfigurasi.roles.data';
    private $tabel          = 'tablemenu';

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

    public function sort()
    {
        $menus = $this->repository->getMenus();

        $data = [];
        $i = 0;
        foreach ($menus as $mm) {
            $i++;
            $data[] = ['id' => $mm->id, 'orders' => $i];
            foreach ($mm->subMenus as $sm) {
                $i++;
                $data[] = ['id' => $sm->id, 'orders' => $i];
            }
        }

        Cache::forget('menus');

        BatchFacade::update(new Menu(), $data, 'id');
        responseSuccess(true);
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
    public function create(Menu $menu)
    {
        return view($this->formView, [
            'action'    => route($this->urlStore),
            'data'      => $menu,
            'mainMenus' => $this->repository->getMainMenus(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MenuRequest $request, Menu $menu)
    {
        DB::beginTransaction();
        try {
            $menu->fill($request->validated());
            $menu->fill([
                'orders'            => $request->orders,
                'icon'              => $request->icon,
                'category'          => $request->category,
                'main_menu_id'      => $request->main_menu_id,
            ]);

            $menu->save();

            foreach ($request->permissions as $permission) {
                Permission::create(['name' => $permission . " {$menu->url}"])->menus()->attach($menu);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return responseError($th);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil menambah data'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(403, 'Unauthorized');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        return view($this->formView, [
            'action'        => route($this->urlUpdate, $menu->id),
            'data'          => $menu,
            'mainMenus'     => $this->repository->getMainMenus(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MenuRequest $request, Menu $menu)
    {
        $menu->fill($request->validated());
        $menu->fill([
            'orders'            => $request->orders,
            'icon'              => $request->icon,
            'category'          => $request->category,
        ]);

        if ($request->level_menu == 'main_menu') {
            $menu->main_menu_id = null;
        }
        $menu->save();

        return responseSuccess(true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort(403, 'Unauthorized');
    }
}
