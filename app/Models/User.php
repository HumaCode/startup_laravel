<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke administrator yang mendaftarkan user
    public function admin()
    {
        return $this->belongsTo(User::class, 'type_daftar');
    }

    // Mendapatkan nama admin atau "Mendaftar Mandiri"
    public function getPendaftarAttribute()
    {
        return $this->type_daftar == 0 ? 'Mendaftar Mandiri' : 'Didaftarkan Oleh ' . optional($this->admin)->name;
    }

    // Scope untuk menampilkan role user
    public function scopeWithUserRole($query)
    {
        return $query->with('roles:name'); // Ambil hanya nama role
    }

    // Akses nama role secara langsung di model
    public function getUserRoleAttribute()
    {
        return $this->roles->pluck('name')->implode(', '); // Gabungkan jika lebih dari satu
    }
}
