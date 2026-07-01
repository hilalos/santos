<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('Admin Dashboard') }} - {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <div class="font-semibold text-gray-800">{{ __('Admin Panel') }}</div>

                        <div class="flex items-center gap-4">
                            <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>

                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Admin Dashboard') }}
                    </h2>
                </div>
            </header>

            <main>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                            <div class="text-sm text-gray-500">{{ __('Total Users') }}</div>
                            <div class="text-3xl font-semibold text-gray-900">{{ $usersCount }}</div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                            <div class="text-sm text-gray-500">{{ __('Admins') }}</div>
                            <div class="text-3xl font-semibold text-gray-900">{{ $adminsCount }}</div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
