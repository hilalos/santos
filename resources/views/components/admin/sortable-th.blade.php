@props(['field', 'label'])

@php
    $currentSort = request('sort', 'created_at');
    $currentDirection = request('direction', 'desc');
    $isActive = $currentSort === $field;
    $nextDirection = $isActive && $currentDirection === 'asc' ? 'desc' : 'asc';
@endphp

<th scope="col" {{ $attributes->merge(['class' => 'px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide select-none']) }}>
    <a
        href="{{ request()->fullUrlWithQuery(['sort' => $field, 'direction' => $nextDirection]) }}"
        class="inline-flex items-center gap-1 hover:text-gray-900 dark:hover:text-gray-200"
    >
        {{ $label }}

        @if ($isActive)
            @if ($currentDirection === 'asc')
                <x-heroicon-m-chevron-up class="w-3.5 h-3.5" />
            @else
                <x-heroicon-m-chevron-down class="w-3.5 h-3.5" />
            @endif
        @else
            <x-heroicon-m-chevron-up-down class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" />
        @endif
    </a>
</th>
