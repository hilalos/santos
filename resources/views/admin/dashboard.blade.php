<x-admin-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Dashboard') }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            {{ __('An overview of your AI ad generation platform — users, subscriptions, generations and system health.') }}
        </p>
    </div>

    {{-- ================= REAL OVERVIEW ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <x-admin.stat-card label="{{ $overview['total_users']['label'] }}" value="{{ $overview['total_users']['value'] }}">
            <x-slot:icon><x-heroicon-o-users class="w-5 h-5" /></x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card label="{{ $overview['active_subscribers']['label'] }}" value="{{ $overview['active_subscribers']['value'] }}">
            <x-slot:icon><x-heroicon-o-user-group class="w-5 h-5" /></x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card label="{{ $overview['conversion_rate']['label'] }}" value="{{ $overview['conversion_rate']['value'] }}">
            <x-slot:icon><x-heroicon-o-arrow-trending-up class="w-5 h-5" /></x-slot:icon>
        </x-admin.stat-card>
    </div>

    {{-- ================= PLATFORM OVERVIEW (placeholder until generation/billing subsystems exist) ================= --}}
    <div class="mb-3 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Platform Overview') }}</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-admin.stat-card label="{{ $platformOverview['monthly_revenue']['label'] }}" value="{{ $platformOverview['monthly_revenue']['value'] }}">
            <x-slot:icon><x-heroicon-o-currency-dollar class="w-5 h-5" /></x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card label="{{ $platformOverview['total_generations']['label'] }}" value="{{ $platformOverview['total_generations']['value'] }}">
            <x-slot:icon><x-heroicon-o-sparkles class="w-5 h-5" /></x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card label="{{ $platformOverview['image_generations']['label'] }}" value="{{ $platformOverview['image_generations']['value'] }}">
            <x-slot:icon><x-heroicon-o-photo class="w-5 h-5" /></x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card label="{{ $platformOverview['video_generations']['label'] }}" value="{{ $platformOverview['video_generations']['value'] }}">
            <x-slot:icon><x-heroicon-o-film class="w-5 h-5" /></x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card label="{{ $platformOverview['ad_copy_generations']['label'] }}" value="{{ $platformOverview['ad_copy_generations']['value'] }}">
            <x-slot:icon><x-heroicon-o-pencil-square class="w-5 h-5" /></x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card label="{{ $platformOverview['credits_used']['label'] }}" value="{{ $platformOverview['credits_used']['value'] }}">
            <x-slot:icon><x-heroicon-o-bolt class="w-5 h-5" /></x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card label="{{ $platformOverview['ai_provider_cost']['label'] }}" value="{{ $platformOverview['ai_provider_cost']['value'] }}">
            <x-slot:icon><x-heroicon-o-banknotes class="w-5 h-5" /></x-slot:icon>
        </x-admin.stat-card>

        <x-admin.stat-card label="{{ $platformOverview['failed_generations']['label'] }}" value="{{ $platformOverview['failed_generations']['value'] }}">
            <x-slot:icon><x-heroicon-o-exclamation-triangle class="w-5 h-5" /></x-slot:icon>
        </x-admin.stat-card>
    </div>

    {{-- ================= TOP MODELS + SYSTEM HEALTH ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">{{ __('Top Used Models') }}</h3>

            <div class="space-y-3">
                @foreach ($topModels as $model)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-700 dark:text-gray-300">{{ $model['name'] }}</span>
                            <span class="text-gray-400">{{ $model['share'] }}%</span>
                        </div>
                        <div class="h-1.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $model['share'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">{{ __('System Health') }}</h3>

            <div class="space-y-3">
                @foreach ($systemHealth as $check)
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            @if ($check['ok'])
                                <x-heroicon-m-check-circle class="w-4 h-4 text-green-500" />
                            @else
                                <x-heroicon-m-x-circle class="w-4 h-4 text-red-500" />
                            @endif
                            <span class="text-gray-700 dark:text-gray-300">{{ $check['label'] }}</span>
                        </div>
                        <span class="text-gray-400 text-xs">{{ $check['detail'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ================= RECENT GENERATIONS + RECENT SUBSCRIPTIONS ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">{{ __('Recent Generations') }}</h3>

            <div class="space-y-1">
                @foreach ($recentGenerations as $generation)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-800 last:border-0">
                        <div class="flex items-center gap-2 text-sm">
                            <span>{{ $generation['type'] }}</span>
                            <span class="text-gray-700 dark:text-gray-300">{{ $generation['title'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-admin.badge :color="match ($generation['status']) {
                                'Completed' => 'green',
                                'Processing' => 'blue',
                                'Failed' => 'red',
                                default => 'gray',
                            }">{{ $generation['status'] }}</x-admin.badge>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $generation['at'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-4">{{ __('Recent Subscriptions') }}</h3>

            @if (count($recentSubscriptions) > 0)
                <div class="space-y-1">
                    @foreach ($recentSubscriptions as $subscription)
                        <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-800 last:border-0">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $subscription['name'] }}</span>
                            <div class="flex items-center gap-2">
                                <x-admin.badge color="indigo">{{ $subscription['plan'] }}</x-admin.badge>
                                <span class="text-xs text-gray-400 whitespace-nowrap">{{ $subscription['at'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400">{{ __('No paid subscriptions yet.') }}</p>
            @endif
        </div>
    </div>
</x-admin-layout>
