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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'status' => \App\Enums\UserStatus::Active,
            'plan' => \App\Enums\UserPlan::Free,
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'is_admin' => true,
            'status' => \App\Enums\UserStatus::Active,
            'plan' => \App\Enums\UserPlan::Enterprise,
            'role' => \App\Enums\UserRole::Admin,
        ]);

        $this->call(UserSeeder::class);
    }
}
