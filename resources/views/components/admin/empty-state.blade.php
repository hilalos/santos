@props(['title' => 'No results found', 'message' => null, 'actionLabel' => null, 'actionUrl' => null])

<div class="flex flex-col items-center justify-center py-16 px-6 text-center">
    <div class="w-20 h-20 rounded-full bg-gray-50 dark:bg-gray-800 flex items-center justify-center mb-4">
        <x-heroicon-o-users class="w-10 h-10 text-gray-300 dark:text-gray-600" />
    </div>

    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>

    @if ($message)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-sm">{{ $message }}</p>
    @endif

    @if ($actionLabel && $actionUrl)
        <a
            href="{{ $actionUrl }}"
            class="mt-5 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-500 transition"
        >
            <x-heroicon-m-plus class="w-4 h-4" />
            {{ $actionLabel }}
        </a>
    @elseif ($actionLabel)
        <button
            type="button"
            {{ $attributes->merge(['class' => 'mt-5 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-500 transition']) }}
        >
            <x-heroicon-m-plus class="w-4 h-4" />
            {{ $actionLabel }}
        </button>
    @endif
</div>
