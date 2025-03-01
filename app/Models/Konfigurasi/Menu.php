<?php

namespace App\Models\Konfigurasi;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = [];


    /**
     * Get all of the subMenus for the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subMenus(): HasMany
    {
        return $this->hasMany(Menu::class, 'main_menu_id');
    }

    /**
     * The permissions that belong to the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'menu_permission', 'menu_id', 'permission_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
