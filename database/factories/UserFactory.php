<?php

namespace Database\Factories;

use App\Enums\UserPlan;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $registeredAt = fake()->dateTimeBetween('-1 year', 'now');
        $phoneVerified = fake()->boolean(60);
        $everLoggedIn = fake()->boolean(85);

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => fake()->boolean(85) ? fake()->dateTimeBetween($registeredAt, 'now') : null,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'status' => fake()->randomElement([
                ...array_fill(0, 70, UserStatus::Active),
                ...array_fill(0, 10, UserStatus::Pending),
                ...array_fill(0, 10, UserStatus::Suspended),
                ...array_fill(0, 5, UserStatus::Banned),
                ...array_fill(0, 5, UserStatus::Inactive),
            ]),
            'plan' => fake()->randomElement([
                ...array_fill(0, 60, UserPlan::Free),
                ...array_fill(0, 15, UserPlan::Starter),
                ...array_fill(0, 15, UserPlan::Pro),
                ...array_fill(0, 7, UserPlan::Business),
                ...array_fill(0, 3, UserPlan::Enterprise),
            ]),
            'role' => fake()->randomElement([
                ...array_fill(0, 80, UserRole::Member),
                ...array_fill(0, 10, UserRole::Editor),
                ...array_fill(0, 8, UserRole::Manager),
                ...array_fill(0, 2, UserRole::Admin),
            ]),
            'country' => fake()->randomElement(array_keys(config('countries'))),
            'phone' => fake()->numerify('+1##########'),
            'phone_verified_at' => $phoneVerified ? fake()->dateTimeBetween($registeredAt, 'now') : null,
            'two_factor_enabled' => fake()->boolean(30),
            'last_login_at' => $everLoggedIn ? fake()->dateTimeBetween($registeredAt, 'now') : null,
            'created_at' => $registeredAt,
            'updated_at' => $registeredAt,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => UserStatus::Active]);
    }

    public function suspended(): static
    {
        return $this->state(fn () => ['status' => UserStatus::Suspended]);
    }

    public function banned(): static
    {
        return $this->state(fn () => ['status' => UserStatus::Banned]);
    }

    public function pendingReview(): static
    {
        return $this->state(fn () => ['status' => UserStatus::Pending]);
    }

    public function onPlan(UserPlan $plan): static
    {
        return $this->state(fn () => ['plan' => $plan]);
    }

    public function registeredToday(): static
    {
        return $this->state(fn () => [
            'created_at' => fake()->dateTimeBetween('-8 hours', 'now'),
            'updated_at' => now(),
        ]);
    }

    public function registeredThisMonth(): static
    {
        return $this->state(fn () => [
            'created_at' => fake()->dateTimeBetween(now()->startOfMonth(), 'now'),
            'updated_at' => now(),
        ]);
    }
}
