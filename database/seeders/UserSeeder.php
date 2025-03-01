<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users      = ['administrator', 'admin', 'user'];

        $default    = [
            // 'email_verified_at' => now(),
            'password'          => Hash::make('123'),
            'is_active'         => '1',
            // 'remember_token'    => Str::random(10)
        ];

        foreach ($users as $value) {
            User::create(
                [...$default, ...[
                    'name'              => ucwords($value),
                    'username'          => $value,
                    'email'             => $value . '@gmail.com',
                ]]
            )->assignRole($value);
        }
    }
}
