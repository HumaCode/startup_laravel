<?php

namespace App\Models\Konfigurasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuPermission extends Model
{
    use HasFactory;


    protected $guarded = [];
    protected $table = 'menu_permission';
}
