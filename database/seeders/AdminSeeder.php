<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nama' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'role' => 'admin'
        ]);

        // Tambah user customer untuk testing
        User::create([
            'nama' => 'Customer',
            'email' => 'customer@test.com',
            'password' => Hash::make('123456'),
            'role' => 'customer'
        ]);
    }
} 