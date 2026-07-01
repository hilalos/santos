<?php

namespace App\Services\Admin;

use App\Enums\UserPlan;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Stats for the admin dashboard.
 *
 * Users/subscribers/conversion/system-health figures are computed from real
 * data. This app has no generation, billing or AI-provider-cost subsystem
 * yet, so those figures are clearly-scoped placeholder numbers until that
 * data exists — see the `platformOverview()`/`topModels()`/
 * `recentGenerations()` methods.
 */
class DashboardStatsService
{
    /**
     * @return array<string, array{label: string, value: string}>
     */
    public function overview(): array
    {
        $totalUsers = User::count();
        $activeSubscribers = User::where('status', UserStatus::Active)
            ->where('plan', '!=', UserPlan::Free)
            ->count();

        return [
            'total_users' => [
                'label' => 'Total Users',
                'value' => number_format($totalUsers),
            ],
            'active_subscribers' => [
                'label' => 'Active Subscribers',
                'value' => number_format($activeSubscribers),
            ],
            'conversion_rate' => [
                'label' => 'Conversion Rate',
                'value' => $totalUsers > 0 ? round(($activeSubscribers / $totalUsers) * 100, 1).'%' : '0%',
            ],
        ];
    }

    /**
     * Placeholder platform metrics until generation/billing/cost tracking exists.
     *
     * @return array<string, array{label: string, value: string}>
     */
    public function platformOverview(): array
    {
        return [
            'monthly_revenue' => ['label' => 'Monthly Revenue', 'value' => '$18,420'],
            'total_generations' => ['label' => 'Total Generations', 'value' => '42,918'],
            'image_generations' => ['label' => 'Image Generations', 'value' => '27,340'],
            'video_generations' => ['label' => 'Video Generations', 'value' => '9,812'],
            'ad_copy_generations' => ['label' => 'Ad Copy Generations', 'value' => '5,766'],
            'credits_used' => ['label' => 'Credits Used', 'value' => '381,204'],
            'ai_provider_cost' => ['label' => 'AI Provider Cost', 'value' => '$4,932'],
            'failed_generations' => ['label' => 'Failed Generations', 'value' => '128'],
        ];
    }

    /**
     * @return list<array{name: string, share: int}>
     */
    public function topUsedModels(): array
    {
        return [
            ['name' => 'Flux Pro (Image)', 'share' => 38],
            ['name' => 'Kling Video (Video)', 'share' => 24],
            ['name' => 'GPT Ad Copy (Copy)', 'share' => 19],
            ['name' => 'Stable Diffusion XL (Image)', 'share' => 12],
            ['name' => 'Runway Gen-3 (Video)', 'share' => 7],
        ];
    }

    /**
     * @return list<array{type: string, title: string, at: string, status: string}>
     */
    public function recentGenerations(): array
    {
        return [
            ['type' => '🖼️', 'title' => 'Product shot — "Summer Sale Banner"', 'at' => '2 minutes ago', 'status' => 'Completed'],
            ['type' => '🎬', 'title' => 'Video ad — "15s Product Demo"', 'at' => '11 minutes ago', 'status' => 'Completed'],
            ['type' => '📝', 'title' => 'Ad copy — "Black Friday Headlines"', 'at' => '24 minutes ago', 'status' => 'Completed'],
            ['type' => '🖼️', 'title' => 'Social post — "Instagram Carousel"', 'at' => '38 minutes ago', 'status' => 'Failed'],
            ['type' => '🎬', 'title' => 'Video ad — "Brand Story 30s"', 'at' => '1 hour ago', 'status' => 'Processing'],
        ];
    }

    /**
     * @return list<array{name: string, plan: string, at: string}>
     */
    public function recentSubscriptions(): array
    {
        return User::where('plan', '!=', UserPlan::Free)
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn (User $user) => [
                'name' => $user->name,
                'plan' => $user->plan->label(),
                'at' => $user->created_at->diffForHumans(),
            ])
            ->all();
    }

    /**
     * Lightweight, real infrastructure checks.
     *
     * @return list<array{label: string, ok: bool, detail: string}>
     */
    public function systemHealth(): array
    {
        $checks = [];

        try {
            DB::connection()->getPdo();
            $checks[] = ['label' => 'Database', 'ok' => true, 'detail' => 'Connected'];
        } catch (\Throwable) {
            $checks[] = ['label' => 'Database', 'ok' => false, 'detail' => 'Unreachable'];
        }

        try {
            Cache::put('admin-health-check', true, 5);
            $checks[] = ['label' => 'Cache', 'ok' => (bool) Cache::get('admin-health-check'), 'detail' => config('cache.default')];
        } catch (\Throwable) {
            $checks[] = ['label' => 'Cache', 'ok' => false, 'detail' => 'Unreachable'];
        }

        $freeBytes = @disk_free_space(storage_path());
        $checks[] = [
            'label' => 'Storage',
            'ok' => $freeBytes === false || $freeBytes > 1024 * 1024 * 100,
            'detail' => $freeBytes ? round($freeBytes / 1024 / 1024 / 1024, 1).' GB free' : 'Unknown',
        ];

        return $checks;
    }
}
