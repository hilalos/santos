<?php

namespace App\Filters\Admin;

use App\Enums\UserPlan;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Applies the "All Users" admin table filters to a query.
 *
 * Each filter is only applied when present in the request, so an empty
 * request returns every (non-trashed) user unfiltered.
 */
class UserFilter
{
    /**
     * Columns the table is allowed to sort by, mapped to their DB column.
     *
     * @var array<string, string>
     */
    public const SORTABLE = [
        'name' => 'name',
        'email' => 'email',
        'country' => 'country',
        'created_at' => 'created_at',
        'last_login_at' => 'last_login_at',
        'status' => 'status',
        'plan' => 'plan',
    ];

    public function __construct(private readonly Request $request)
    {
    }

    public function apply(Builder $query): Builder
    {
        $this->applySearch($query);
        $this->applyExact($query, 'status', UserStatus::class);
        $this->applyExact($query, 'plan', UserPlan::class);
        $this->applyExact($query, 'role', UserRole::class);
        $this->applyCountry($query);
        $this->applyRegistrationDateRange($query);
        $this->applyBooleanFlag($query, 'email_verified', 'email_verified_at');
        $this->applyBooleanFlag($query, 'phone_verified', 'phone_verified_at');
        $this->applyTwoFactor($query);
        $this->applyLastLogin($query);
        $this->applySubscription($query);
        $this->applySort($query);

        return $query;
    }

    private function applySearch(Builder $query): void
    {
        $search = trim((string) $this->request->string('q'));

        if ($search === '') {
            return;
        }

        $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * @param  class-string  $enum
     */
    private function applyExact(Builder $query, string $field, string $enum): void
    {
        $value = $this->request->string($field)->toString();

        if ($value === '' || ! $enum::tryFrom($value)) {
            return;
        }

        $query->where($field, $value);
    }

    private function applyCountry(Builder $query): void
    {
        $country = $this->request->string('country')->toString();

        if ($country === '' || ! array_key_exists($country, config('countries'))) {
            return;
        }

        $query->where('country', $country);
    }

    private function applyRegistrationDateRange(Builder $query): void
    {
        $from = $this->request->string('registered_from')->toString();
        $to = $this->request->string('registered_to')->toString();

        if ($from !== '') {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to !== '') {
            $query->whereDate('created_at', '<=', $to);
        }
    }

    private function applyBooleanFlag(Builder $query, string $field, string $column): void
    {
        $value = $this->request->string($field)->toString();

        if ($value === 'yes') {
            $query->whereNotNull($column);
        } elseif ($value === 'no') {
            $query->whereNull($column);
        }
    }

    private function applyTwoFactor(Builder $query): void
    {
        $value = $this->request->string('two_factor')->toString();

        if ($value === 'yes') {
            $query->where('two_factor_enabled', true);
        } elseif ($value === 'no') {
            $query->where('two_factor_enabled', false);
        }
    }

    private function applyLastLogin(Builder $query): void
    {
        $value = $this->request->string('last_login')->toString();

        match ($value) {
            'today' => $query->whereDate('last_login_at', now()->toDateString()),
            '7d' => $query->where('last_login_at', '>=', now()->subDays(7)),
            '30d' => $query->where('last_login_at', '>=', now()->subDays(30)),
            'never' => $query->whereNull('last_login_at'),
            default => null,
        };
    }

    private function applySubscription(Builder $query): void
    {
        $value = $this->request->string('subscription')->toString();

        if ($value === 'free') {
            $query->where('plan', UserPlan::Free->value);
        } elseif ($value === 'paid') {
            $query->where('plan', '!=', UserPlan::Free->value);
        }
    }

    private function applySort(Builder $query): void
    {
        $sort = $this->request->string('sort')->toString();
        $direction = $this->request->string('direction')->toString() === 'asc' ? 'asc' : 'desc';

        $column = self::SORTABLE[$sort] ?? 'created_at';

        $query->orderBy($column, $direction);
    }
}
