<?php

namespace Database\Seeders;

use App\HasMenuPermission;
use App\Models\Konfigurasi\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    use HasMenuPermission;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mm = Menu::firstOrCreate(['url' => 'konfigurasi'], ['name' => 'konfigurasi', 'category' => 'KONFIGURASI', 'icon' => 'ki-element-11']);
        $this->attachMenupermission($mm, ['read'], ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Menu', 'url' => $mm->url . '/menu',  'category' => $mm->category]);
        $this->attachMenupermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Akses Role', 'url' => $mm->url . '/akses-role',  'category' => $mm->category]);
        $this->attachMenupermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'User', 'url' => $mm->url . '/users',  'category' => $mm->category]);
        $this->attachMenupermission($sm, null, ['admin']);

        $mm = Menu::firstOrCreate(['url' => 'Content'], ['name' => 'Content', 'category' => 'CONTENT', 'icon' => 'ki-element-11']);
        $this->attachMenupermission($mm, ['read'], ['admin']);
    }
}
