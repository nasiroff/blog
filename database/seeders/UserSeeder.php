<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends CustomSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');
        \App\Models\User::factory()->create([
            'name'     => 'Test User',
            'username' => 'admin',
            'email'    => 'test@example.com',
            'password' => $password,
            'token'    => md5(Str::random())
        ]);
        $users = [];
        for ($i = 0; $i < 100; $i++) {
            $users[] = [
                'name'     => Str::random(5),
                'username' => Str::random(8),
                'email'    => Str::random(8).'_test@example.com',
                'password' => $password,
                'token'    => md5(Str::random()),
                'created_at' => $this->randomDateTime()
            ];

        }
        \App\Models\User::query()->insert($users);
    }
}
