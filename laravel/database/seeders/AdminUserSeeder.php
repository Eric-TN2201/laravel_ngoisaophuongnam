<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => config('app.admin.name'),
            'email' => config('app.admin.email'),
            'password' => Hash::make(config('app.admin.password')),
            'role' => 'admin',
        ]);
    }
}
