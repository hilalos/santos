<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.getItem('admin-theme') === 'dark' }" x-init="$watch('dark', value => { localStorage.setItem('admin-theme', value ? 'dark' : 'light'); })" :class="{ 'dark': dark }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? __('Admin') }} - {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-950">
        <div class="flex h-screen" x-data="{ mobileSidebarOpen: false }">
            <!-- Sidebar -->
            <aside
                class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 text-gray-200 overflow-y-auto transform transition-transform lg:static lg:translate-x-0"
                :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            >
                <div class="p-4 text-lg font-bold text-white border-b border-gray-800">
                    {{ __('Admin Panel') }}
                </div>

                <nav class="p-2 space-y-1 pb-10" aria-label="{{ __('Admin navigation') }}">
                    @foreach (config('admin_menu') as $section)
                        @if (isset($section['route']))
                            <a
                                href="{{ route($section['route']) }}"
                                class="flex items-center gap-2 px-3 py-2 rounded text-sm {{ request()->routeIs($section['route']) ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' }}"
                            >
                                <span aria-hidden="true">{{ $section['icon'] }}</span>
                                <span>{{ $section['label'] }}</span>
                            </a>
                        @else
                            @php
                                $sectionSlug = \Illuminate\Support\Str::slug($section['label']);
                                $sectionActive = request()->is("admin/{$sectionSlug}/*");

                                foreach ($section['items'] as $item) {
                                    if (is_array($item) && isset($item['route']) && request()->routeIs($item['route'].'*')) {
                                        $sectionActive = true;
                                    }
                                }
                            @endphp
                            <div x-data="{ open: {{ $sectionActive ? 'true' : 'false' }} }">
                                <button
                                    @click="open = !open"
                                    type="button"
                                    class="w-full flex items-center justify-between px-3 py-2 rounded text-sm hover:bg-gray-800"
                                    :aria-expanded="open"
                                >
                                    <span class="flex items-center gap-2">
                                        <span aria-hidden="true">{{ $section['icon'] }}</span>
                                        <span>{{ $section['label'] }}</span>
                                    </span>
                                    <x-heroicon-m-chevron-right class="w-3 h-3 transition-transform shrink-0" x-bind:class="{ 'rotate-90': open }" />
                                </button>

                                <div x-show="open" x-collapse x-cloak class="ml-6 mt-1 space-y-1">
                                    @foreach ($section['items'] as $item)
                                        @php
                                            $itemLabel = is_array($item) ? $item['label'] : $item;
                                            $itemHref = is_array($item) && isset($item['route'])
                                                ? route($item['route'])
                                                : url("admin/{$sectionSlug}/".\Illuminate\Support\Str::slug($itemLabel));
                                            $itemActive = is_array($item) && isset($item['route'])
                                                ? request()->routeIs($item['route'].'*')
                                                : request()->is("admin/{$sectionSlug}/".\Illuminate\Support\Str::slug($itemLabel));
                                        @endphp
                                        <a
                                            href="{{ $itemHref }}"
                                            class="block px-3 py-1.5 rounded text-sm {{ $itemActive ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                                        >
                                            {{ $itemLabel }}
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
                @if (session('impersonator_id'))
                    <div class="bg-amber-500 text-amber-950 text-sm px-4 py-2 flex items-center justify-between">
                        <span>{{ __('You are viewing the app as :name.', ['name' => Auth::user()->name]) }}</span>
                        <form method="POST" action="{{ route('admin.impersonate.stop') }}">
                            @csrf
                            <button type="submit" class="font-semibold underline">{{ __('Return to admin') }}</button>
                        </form>
                    </div>
                @endif

                <header class="bg-white dark:bg-gray-900 dark:border-b dark:border-gray-800 shadow flex items-center justify-between px-4 sm:px-6 py-4">
                    <div class="flex items-center gap-3">
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen" type="button" class="lg:hidden text-gray-500 dark:text-gray-400" aria-label="{{ __('Toggle sidebar') }}">
                            <x-heroicon-o-bars-3 class="w-6 h-6" />
                        </button>

                        <div class="font-semibold text-xl text-gray-800 dark:text-gray-100">
                            {{ $header ?? '' }}
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <button
                            @click="dark = !dark"
                            type="button"
                            class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                            :aria-label="dark ? '{{ __('Switch to light mode') }}' : '{{ __('Switch to dark mode') }}'"
                        >
                            <x-heroicon-o-sun class="w-5 h-5" x-show="dark" x-cloak />
                            <x-heroicon-o-moon class="w-5 h-5" x-show="!dark" x-cloak />
                        </button>

                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ Auth::user()->name }}</span>

                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white underline">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </header>

                @if (session('success'))
                    <div class="bg-green-50 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-sm px-4 sm:px-6 py-2 border-b border-green-100 dark:border-green-900">
                        {{ session('success') }}
                    </div>
                @endif

                <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
