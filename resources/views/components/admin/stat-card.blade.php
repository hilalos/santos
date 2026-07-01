@props(['icon', 'label', 'value', 'trend' => null, 'trendSuffix' => '%'])

<div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm p-5">
    <div class="flex items-start justify-between">
        <div>
            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</div>
            <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $value }}</div>
        </div>
        <div class="w-10 h-10 rounded-lg bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400">
            {{ $icon }}
        </div>
    </div>

    @if (! is_null($trend))
        <div class="mt-3 flex items-center gap-1 text-xs">
            @if ($trend > 0)
                <x-heroicon-m-arrow-trending-up class="w-4 h-4 text-green-600" />
                <span class="text-green-600 font-medium">+{{ $trend }}{{ $trendSuffix }}</span>
            @elseif ($trend < 0)
                <x-heroicon-m-arrow-trending-down class="w-4 h-4 text-red-600" />
                <span class="text-red-600 font-medium">{{ $trend }}{{ $trendSuffix }}</span>
            @else
                <x-heroicon-m-minus class="w-4 h-4 text-gray-400" />
                <span class="text-gray-400 font-medium">0{{ $trendSuffix }}</span>
            @endif
            <span class="text-gray-400 dark:text-gray-500">{{ __('vs last period') }}</span>
        </div>
    @endif
</div>
