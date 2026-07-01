<?php

namespace App\Support;

/**
 * Best-effort, dependency-free "browser on OS" label parsed from a user agent.
 * Good enough for admin display purposes; not a full UA parser.
 */
class DeviceParser
{
    public static function label(string $userAgent): string
    {
        $os = match (true) {
            str_contains($userAgent, 'iPhone') => 'iPhone',
            str_contains($userAgent, 'iPad') => 'iPad',
            str_contains($userAgent, 'Android') => 'Android',
            str_contains($userAgent, 'Macintosh') => 'Mac',
            str_contains($userAgent, 'Windows') => 'Windows',
            str_contains($userAgent, 'Linux') => 'Linux',
            default => 'Unknown device',
        };

        $browser = match (true) {
            str_contains($userAgent, 'Edg/') => 'Edge',
            str_contains($userAgent, 'Chrome/') => 'Chrome',
            str_contains($userAgent, 'Firefox/') => 'Firefox',
            str_contains($userAgent, 'Safari/') && ! str_contains($userAgent, 'Chrome/') => 'Safari',
            default => 'Unknown browser',
        };

        return "{$browser} on {$os}";
    }
}
