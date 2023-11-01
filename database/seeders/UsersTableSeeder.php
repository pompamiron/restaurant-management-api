<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Waitstaff User',
            'email' => 'waitstaff@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Kitchen Staff',
            'email' => 'kitchen@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
