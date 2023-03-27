<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\Role::create([
            'name' => 'Admin',
            'status' => true,
        ]);

        \App\Models\Role::create([
            'name' => 'Lecturers',
            'status' => true,
        ]);


        \App\Models\Role::create([
            'name' => 'Students',
            'status' => true,
        ]);


        \App\Models\Semester::create([
            'name' => 'Fall',
            'status' => true,
        ]);


        \App\Models\Semester::create([
            'name' => 'Spring',
            'status' => true,
        ]);


        \App\Models\Semester::create([
            'name' => 'Summer',
            'status' => true,
        ]);


        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role_id' => 1,
            'satus' => true,
        ]);
    }
}
