<x-admin-layout>
    <x-slot name="header">
        {{ $sectionIcon }} {{ $sectionLabel }} <span class="text-gray-400">/</span> {{ $itemLabel }}
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-10 text-center">
        <div class="text-5xl mb-4">🚧</div>
        <h3 class="text-lg font-semibold text-gray-800">{{ $itemLabel }}</h3>
        <p class="text-gray-500 mt-2">{{ __('This page is under construction.') }}</p>
    </div>
</x-admin-layout>
