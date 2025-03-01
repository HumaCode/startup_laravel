<?php

namespace Database\Seeders;

use App\HasMenuPermission;
use App\Models\Konfigurasi\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class MenuSeeder extends Seeder
{
    use HasMenuPermission;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cache::forget('menus');

        $mm = Menu::firstOrCreate(['url' => 'konfigurasi'], ['name' => 'Konfigurasi', 'category' => 'KONFIGURASI', 'icon' => 'ki-element-11']);
        $this->attachMenupermission($mm, ['read'], ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Menu', 'url' => $mm->url . '/menu',  'category' => $mm->category]);
        $this->attachMenupermission($sm, ['create', 'read', 'update', 'sort'], ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Role', 'url' => $mm->url . '/roles',  'category' => $mm->category]);
        $this->attachMenupermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Permission', 'url' => $mm->url . '/permissions',  'category' => $mm->category]);
        $this->attachMenupermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Akses Role', 'url' => $mm->url . '/akses-role',  'category' => $mm->category]);
        $this->attachMenupermission($sm, ['read', 'update'], ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'User', 'url' => $mm->url . '/users',  'category' => $mm->category]);
        $this->attachMenupermission($sm, ['create', 'read', 'update', 'delete', 'aktivasi'], ['admin']);

        $mm = Menu::firstOrCreate(['url' => 'setting'], ['name' => 'Setting', 'category' => 'SETTING', 'icon' => 'ki-setting-2']);
        $this->attachMenupermission($mm, ['read'], ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Profil', 'url' => $mm->url . '/profil',  'category' => $mm->category]);
        $this->attachMenupermission($sm, ['read', 'update'], ['admin']);
    }
}
