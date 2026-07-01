<?php

namespace Database\Factories;

use App\Models\UserNote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserNote>
 */
class UserNoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body' => fake()->randomElement([
                'Reached out about upgrading to a higher plan.',
                'Reported a billing discrepancy, resolved via refund.',
                'Flagged for unusual login activity, monitoring.',
                'Requested data export for GDPR compliance.',
                'VIP customer, escalate support tickets directly.',
            ]),
        ];
    }
}
