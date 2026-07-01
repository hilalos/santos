<x-admin-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <div class="text-sm text-gray-500">{{ __('Total Users') }}</div>
            <div class="text-3xl font-semibold text-gray-900">{{ $usersCount }}</div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <div class="text-sm text-gray-500">{{ __('Admins') }}</div>
            <div class="text-3xl font-semibold text-gray-900">{{ $adminsCount }}</div>
        </div>
    </div>
</x-admin-layout>
