<?php

namespace Database\Factories;

use App\Models\LoginHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoginHistory>
 */
class LoginHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    /**
     * Realistic user agents so LoginHistory::device() has something to parse.
     *
     * @var list<string>
     */
    protected array $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15',
        'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Mobile Safari/605.1.15',
        'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0 Mobile Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Edg/125.0 Safari/537.36',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Firefox/126.0',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->randomElement($this->userAgents),
            'location' => fake()->city().', '.fake()->countryCode(),
            'created_at' => fake()->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
