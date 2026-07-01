<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\LoginHistoryFactory;
use Database\Factories\UserNoteFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed a realistic set of demo users for the admin "All Users" page.
     */
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();

        User::factory()
            ->count(20)
            ->registeredThisMonth()
            ->create()
            ->each(fn (User $user) => $this->attachHistory($user, $admin));

        User::factory()
            ->count(5)
            ->registeredToday()
            ->create()
            ->each(fn (User $user) => $this->attachHistory($user, $admin));

        User::factory()
            ->count(25)
            ->create()
            ->each(fn (User $user) => $this->attachHistory($user, $admin));
    }

    private function attachHistory(User $user, ?User $admin): void
    {
        $user->loginHistories()->createMany(
            LoginHistoryFactory::new()->count(random_int(1, 6))->raw()
        );

        if ($admin && random_int(1, 100) <= 20) {
            $user->notes()->create([
                'author_id' => $admin->id,
                'body' => UserNoteFactory::new()->make()->body,
            ]);
        }
    }
}
