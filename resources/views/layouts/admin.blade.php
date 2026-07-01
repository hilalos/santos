<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? __('Admin') }} - {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex h-screen bg-gray-100" x-data="{ mobileSidebarOpen: false }">
            <!-- Sidebar -->
            <aside
                class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 text-gray-200 overflow-y-auto transform transition-transform lg:static lg:translate-x-0"
                :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            >
                <div class="p-4 text-lg font-bold text-white border-b border-gray-800">
                    {{ __('Admin Panel') }}
                </div>

                <nav class="p-2 space-y-1 pb-10">
                    @foreach (config('admin_menu') as $section)
                        @if (isset($section['route']))
                            <a
                                href="{{ route($section['route']) }}"
                                class="flex items-center gap-2 px-3 py-2 rounded text-sm {{ request()->routeIs($section['route']) ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' }}"
                            >
                                <span>{{ $section['icon'] }}</span>
                                <span>{{ $section['label'] }}</span>
                            </a>
                        @else
                            @php
                                $sectionSlug = \Illuminate\Support\Str::slug($section['label']);
                                $sectionActive = request()->is("admin/{$sectionSlug}/*");
                            @endphp
                            <div x-data="{ open: {{ $sectionActive ? 'true' : 'false' }} }">
                                <button
                                    @click="open = !open"
                                    type="button"
                                    class="w-full flex items-center justify-between px-3 py-2 rounded text-sm hover:bg-gray-800"
                                >
                                    <span class="flex items-center gap-2">
                                        <span>{{ $section['icon'] }}</span>
                                        <span>{{ $section['label'] }}</span>
                                    </span>
                                    <svg :class="{ 'rotate-90': open }" class="w-3 h-3 transition-transform shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak class="ml-6 mt-1 space-y-1">
                                    @foreach ($section['items'] as $item)
                                        @php
                                            $itemSlug = \Illuminate\Support\Str::slug($item);
                                            $itemActive = request()->is("admin/{$sectionSlug}/{$itemSlug}");
                                        @endphp
                                        <a
                                            href="{{ url("admin/{$sectionSlug}/{$itemSlug}") }}"
                                            class="block px-3 py-1.5 rounded text-sm {{ $itemActive ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                                        >
                                            {{ $item }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </nav>
            </aside>

            <!-- Mobile overlay -->
            <div
                x-show="mobileSidebarOpen"
                x-cloak
                @click="mobileSidebarOpen = false"
                class="fixed inset-0 z-20 bg-black/50 lg:hidden"
            ></div>

            <!-- Main -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <header class="bg-white shadow flex items-center justify-between px-4 sm:px-6 py-4">
                    <div class="flex items-center gap-3">
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen" type="button" class="lg:hidden text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div class="font-semibold text-xl text-gray-800">
                            {{ $header ?? '' }}
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>

                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </header>

                <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
