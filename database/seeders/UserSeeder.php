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
        User::create([
            'name'              => 'Administrator',
            'username'          => 'administrator',
            'email'             => 'administrator@gmail.com',
            'password'          => Hash::make(123),
            'email_verified_at' => now(),
            'remember_token'    => Str::random(10),
        ]);

        User::create([
            'name'              => 'Admin',
            'username'          => 'admin',
            'email'             => 'admin@gmail.com',
            'password'          => Hash::make(123),
            'email_verified_at' => now(),
            'remember_token'    => Str::random(10),
        ]);
    }
}
