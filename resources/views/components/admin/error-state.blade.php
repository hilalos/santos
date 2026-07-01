@props(['message' => 'Something went wrong while loading this data.'])

<div class="flex flex-col items-center justify-center py-16 px-6 text-center">
    <div class="w-20 h-20 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center mb-4">
        <x-heroicon-o-exclamation-triangle class="w-10 h-10 text-red-400" />
    </div>

    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Unable to load users') }}</h3>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-sm">{{ $message }}</p>

    <button
        onclick="window.location.reload()"
        type="button"
        class="mt-5 inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition"
    >
        <x-heroicon-m-arrow-path class="w-4 h-4" />
        {{ __('Retry') }}
    </button>
</div>
