<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\UserRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('test'),
            'role' => UserRole::ADMIN,
        ]);

        Subject::create([
            'name' => 'MATH',
        ]);

        Subject::create([
            'name' => 'ELEX',
        ]);

        Subject::create([
            'name' => 'GEAS',
        ]);

        Subject::create([
            'name' => 'ESAT',
        ]);
    }
}
