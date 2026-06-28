<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'role' => 'admin', 'password' => bcrypt('clinica2024')]
        );

        User::updateOrCreate(
            ['email' => 'soporte.tecnicotics2@cacsantabarbara.co'],
            ['name' => 'Jorge Andres Mosquera', 'role' => 'user', 'password' => bcrypt('123456*')]
        );

        User::updateOrCreate(
            ['email' => 'soporte.tecnicotics1@cacsantabarbara.co'],
            ['name' => 'Andres Fernando', 'role' => 'user', 'password' => bcrypt('123456*')]
        );

        // Seed rooms
        $this->call(RoomSeeder::class);
    }
}
