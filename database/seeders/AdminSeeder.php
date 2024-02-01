<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::query()->create([
            'name'     => 'Test User',
            'username' => 'admin',
            'email'    => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
