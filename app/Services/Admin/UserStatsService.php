<?php

namespace App\Services\Admin;

use App\Enums\UserPlan;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Carbon;

/**
 * Computes the summary card metrics shown at the top of the "All Users" page.
 */
class UserStatsService
{
    /**
     * @return array<string, array{value: string, trend: float|null, label: string}>
     */
    public function summary(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', UserStatus::Active)->count();
        $suspended = User::where('status', UserStatus::Suspended)->count();
        $premiumUsers = User::where('plan', '!=', UserPlan::Free)->count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();

        $newToday = User::whereDate('created_at', now()->toDateString())->count();

        [$newThisMonth, $newLastMonth] = $this->monthlyRegistrations();

        return [
            'total_users' => [
                'label' => 'Total Users',
                'value' => number_format($totalUsers),
                'trend' => $this->percentChange($newLastMonth, $newThisMonth),
            ],
            'active_users' => [
                'label' => 'Active Users',
                'value' => number_format($activeUsers),
                'trend' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0.0,
            ],
            'new_today' => [
                'label' => 'New Today',
                'value' => number_format($newToday),
                'trend' => null,
            ],
            'new_this_month' => [
                'label' => 'New This Month',
                'value' => number_format($newThisMonth),
                'trend' => $this->percentChange($newLastMonth, $newThisMonth),
            ],
            'suspended' => [
                'label' => 'Suspended',
                'value' => number_format($suspended),
                'trend' => $totalUsers > 0 ? round(($suspended / $totalUsers) * 100, 1) : 0.0,
            ],
            'email_verified' => [
                'label' => 'Email Verified %',
                'value' => $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100, 1).'%' : '0%',
                'trend' => null,
            ],
            'premium_users' => [
                'label' => 'Premium Users',
                'value' => number_format($premiumUsers),
                'trend' => $totalUsers > 0 ? round(($premiumUsers / $totalUsers) * 100, 1) : 0.0,
            ],
            'monthly_growth' => [
                'label' => 'Monthly Growth %',
                'value' => $this->percentChange($newLastMonth, $newThisMonth).'%',
                'trend' => $this->percentChange($newLastMonth, $newThisMonth),
            ],
        ];
    }

    /**
     * @return array{0: int, 1: int} [newThisMonth, newLastMonth]
     */
    private function monthlyRegistrations(): array
    {
        $now = Carbon::now();

        $newThisMonth = User::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        $lastMonth = $now->copy()->subMonthNoOverflow();

        $newLastMonth = User::whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->count();

        return [$newThisMonth, $newLastMonth];
    }

    private function percentChange(int $previous, int $current): float
    {
        if ($previous === 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
