<?php

namespace App\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
    case Pending = 'pending';
    case Banned = 'banned';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Suspended => 'Suspended',
            self::Pending => 'Pending',
            self::Banned => 'Banned',
            self::Inactive => 'Inactive',
        };
    }

    /**
     * Tailwind classes for the badge component.
     */
    public function badgeColor(): string
    {
        return match ($this) {
            self::Active => 'green',
            self::Suspended => 'amber',
            self::Pending => 'blue',
            self::Banned => 'red',
            self::Inactive => 'gray',
        };
    }
}
