<?php

namespace App\Enums;

/**
 * A descriptive business role shown in the admin UI.
 *
 * This is independent from the `is_admin` flag, which gates access to the
 * admin panel itself and is intentionally not editable from this screen.
 */
enum UserRole: string
{
    case Member = 'member';
    case Editor = 'editor';
    case Manager = 'manager';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Member => 'Member',
            self::Editor => 'Editor',
            self::Manager => 'Manager',
            self::Admin => 'Admin',
        };
    }
}
