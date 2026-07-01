<?php

namespace App\Enums;

enum UserPlan: string
{
    case Free = 'free';
    case Starter = 'starter';
    case Pro = 'pro';
    case Business = 'business';
    case Enterprise = 'enterprise';

    public function label(): string
    {
        return match ($this) {
            self::Free => 'Free',
            self::Starter => 'Starter',
            self::Pro => 'Pro',
            self::Business => 'Business',
            self::Enterprise => 'Enterprise',
        };
    }

    /**
     * Tailwind classes for the badge component.
     */
    public function badgeColor(): string
    {
        return match ($this) {
            self::Free => 'gray',
            self::Starter => 'blue',
            self::Pro => 'indigo',
            self::Business => 'purple',
            self::Enterprise => 'amber',
        };
    }

    public function isPaid(): bool
    {
        return $this !== self::Free;
    }
}
