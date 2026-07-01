@props(['name', 'size' => 'w-9 h-9'])

@php
    $initials = collect(explode(' ', trim($name)))
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->take(2)
        ->implode('');

    $colors = ['bg-rose-500', 'bg-orange-500', 'bg-amber-500', 'bg-emerald-500', 'bg-teal-500', 'bg-blue-500', 'bg-indigo-500', 'bg-purple-500'];
    $color = $colors[crc32($name) % count($colors)];
@endphp

<div {{ $attributes->merge(['class' => "{$size} {$color} rounded-full flex items-center justify-center text-white text-xs font-semibold shrink-0"]) }}>
    {{ strtoupper($initials) }}
</div>
