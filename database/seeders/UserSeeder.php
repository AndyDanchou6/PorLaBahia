<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@porlabahia.com',
            'password' => Hash::make('admin'),
            'role' => 1,
        ]);

        User::create([
            'name' => 'Staff',
            'email' => 'staff@porlabahia.com',
            'password' => Hash::make('staff'),
            'role' => 0,
        ]);
    }
}
