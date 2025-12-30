<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Hash;
use App\Models\Room;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //* UNTUK ADMIN
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true
        ]);

        //* UNTUK USER BIASA
        User::create([
            'name' => 'User Test',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false
        ]);

        Room::create([
            'name' => 'Ruang Meeting A', 'location' => 'Lantai 1', 'capacity' => 10
        ]);

        Room::create([
            'name' => 'Ruang Meeting B', 'location' => 'Lantai 2', 'capacity' => 20
        ]);

        Room::create([
            'name' => 'Ruang Meeting C', 'location' => 'Lantai 3', 'capacity' => 30
        ]);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
