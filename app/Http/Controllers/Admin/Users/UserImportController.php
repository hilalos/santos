<?php

namespace App\Http\Controllers\Admin\Users;

use App\Enums\UserPlan;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\ImportUsersRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserImportController extends Controller
{
    /**
     * Import users from a CSV with a `name,email,country` header row.
     * Rows with an email that already exists are skipped.
     */
    public function store(ImportUsersRequest $request): RedirectResponse
    {
        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = array_map(fn ($value) => Str::of($value)->trim()->lower()->toString(), fgetcsv($handle) ?: []);

        $nameIndex = array_search('name', $header, true);
        $emailIndex = array_search('email', $header, true);
        $countryIndex = array_search('country', $header, true);

        $created = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $email = $emailIndex !== false ? trim((string) ($row[$emailIndex] ?? '')) : null;
            $name = $nameIndex !== false ? trim((string) ($row[$nameIndex] ?? '')) : null;

            if (! $email || ! $name || User::where('email', $email)->exists()) {
                $skipped++;

                continue;
            }

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(16)),
                'role' => UserRole::Member,
                'plan' => UserPlan::Free,
                'status' => UserStatus::Pending,
                'country' => $countryIndex !== false ? (trim((string) ($row[$countryIndex] ?? '')) ?: null) : null,
            ]);

            $created++;
        }

        fclose($handle);

        return back()->with('success', "Import complete: {$created} created, {$skipped} skipped.");
    }
}
