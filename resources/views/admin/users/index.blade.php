@php
    $toggleableColumns = [
        'avatar' => 'Avatar',
        'email' => 'Email',
        'role' => 'Role',
        'plan' => 'Plan',
        'country' => 'Country',
        'created_at' => 'Registration Date',
        'last_login_at' => 'Last Login',
        'email_verified' => 'Email Verification',
        'subscription' => 'Subscription',
        'status' => 'Status',
    ];
@endphp

<x-admin-layout>
    <x-slot name="header">
        {{ __('All Users') }}
    </x-slot>

    @if ($error)
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl">
            <x-admin.error-state message="{{ __('We couldn\'t load the users list. The database might be temporarily unavailable.') }}" />
        </div>
    @else
        <div
            x-data="usersPage({
                ids: @js($users->pluck('id')->all()),
                users: @js($drawerPayload),
                defaultColumns: @js(array_fill_keys(array_keys($toggleableColumns), true)),
            })"
            x-cloak
        >
            {{-- ================= PAGE HEADER ================= --}}
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ __('All Users') }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Manage all registered users, monitor their activity, subscriptions and account status.') }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button
                        type="button"
                        @click="$dispatch('open-modal', 'create-user')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-500 transition"
                    >
                        <x-heroicon-m-plus class="w-4 h-4" />
                        {{ __('Create User') }}
                    </button>

                    <a
                        href="{{ route('admin.users.export', request()->query()) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                    >
                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                        {{ __('Export CSV') }}
                    </a>

                    <button
                        type="button"
                        @click="$dispatch('open-modal', 'import-users')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                    >
                        <x-heroicon-o-arrow-up-tray class="w-4 h-4" />
                        {{ __('Import Users') }}
                    </button>

                    <button
                        type="button"
                        title="{{ __('Select all users on this page') }}"
                        @click="selected = allSelected ? [] : [...allIds]"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                    >
                        <x-heroicon-o-check-circle class="w-4 h-4" />
                        {{ __('Bulk Actions') }}
                    </button>
                </div>
            </div>

            {{-- ================= SUMMARY CARDS ================= --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <x-admin.stat-card label="{{ $stats['total_users']['label'] }}" value="{{ $stats['total_users']['value'] }}" :trend="$stats['total_users']['trend']">
                    <x-slot:icon><x-heroicon-o-users class="w-5 h-5" /></x-slot:icon>
                </x-admin.stat-card>

                <x-admin.stat-card label="{{ $stats['active_users']['label'] }}" value="{{ $stats['active_users']['value'] }}" :trend="$stats['active_users']['trend']">
                    <x-slot:icon><x-heroicon-o-check-circle class="w-5 h-5" /></x-slot:icon>
                </x-admin.stat-card>

                <x-admin.stat-card label="{{ $stats['new_today']['label'] }}" value="{{ $stats['new_today']['value'] }}" :trend="null">
                    <x-slot:icon><x-heroicon-o-sparkles class="w-5 h-5" /></x-slot:icon>
                </x-admin.stat-card>

                <x-admin.stat-card label="{{ $stats['new_this_month']['label'] }}" value="{{ $stats['new_this_month']['value'] }}" :trend="$stats['new_this_month']['trend']">
                    <x-slot:icon><x-heroicon-o-calendar class="w-5 h-5" /></x-slot:icon>
                </x-admin.stat-card>

                <x-admin.stat-card label="{{ $stats['suspended']['label'] }}" value="{{ $stats['suspended']['value'] }}" :trend="$stats['suspended']['trend']">
                    <x-slot:icon><x-heroicon-o-no-symbol class="w-5 h-5" /></x-slot:icon>
                </x-admin.stat-card>

                <x-admin.stat-card label="{{ $stats['email_verified']['label'] }}" value="{{ $stats['email_verified']['value'] }}" :trend="null">
                    <x-slot:icon><x-heroicon-o-envelope class="w-5 h-5" /></x-slot:icon>
                </x-admin.stat-card>

                <x-admin.stat-card label="{{ $stats['premium_users']['label'] }}" value="{{ $stats['premium_users']['value'] }}" :trend="$stats['premium_users']['trend']">
                    <x-slot:icon><x-heroicon-o-star class="w-5 h-5" /></x-slot:icon>
                </x-admin.stat-card>

                <x-admin.stat-card label="{{ $stats['monthly_growth']['label'] }}" value="{{ $stats['monthly_growth']['value'] }}" :trend="$stats['monthly_growth']['trend']">
                    <x-slot:icon><x-heroicon-o-arrow-trending-up class="w-5 h-5" /></x-slot:icon>
                </x-admin.stat-card>
            </div>

            {{-- ================= FILTER BAR ================= --}}
            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm p-4 mb-6">
                <form method="GET" action="{{ route('admin.users.index') }}" x-ref="filterForm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Search') }}</label>
                            <div class="relative">
                                <x-heroicon-m-magnifying-glass class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                                <input
                                    type="text"
                                    id="global-search"
                                    name="q"
                                    value="{{ request('q') }}"
                                    placeholder="{{ __('Search by name or email... (Ctrl+K)') }}"
                                    class="w-full pl-9 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Status') }}</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Any') }}</option>
                                @foreach (\App\Enums\UserStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Plan') }}</label>
                            <select name="plan" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Any') }}</option>
                                @foreach (\App\Enums\UserPlan::cases() as $plan)
                                    <option value="{{ $plan->value }}" @selected(request('plan') === $plan->value)>{{ $plan->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Role') }}</label>
                            <select name="role" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Any') }}</option>
                                @foreach (\App\Enums\UserRole::cases() as $role)
                                    <option value="{{ $role->value }}" @selected(request('role') === $role->value)>{{ $role->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Country') }}</label>
                            <select name="country" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Any') }}</option>
                                @foreach (config('countries') as $code => $countryName)
                                    <option value="{{ $code }}" @selected(request('country') === $code)>{{ $countryName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Registered From') }}</label>
                            <input type="date" name="registered_from" value="{{ request('registered_from') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Registered To') }}</label>
                            <input type="date" name="registered_to" value="{{ request('registered_to') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Email Verified') }}</label>
                            <select name="email_verified" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Any') }}</option>
                                <option value="yes" @selected(request('email_verified') === 'yes')>{{ __('Verified') }}</option>
                                <option value="no" @selected(request('email_verified') === 'no')>{{ __('Pending') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Phone Verified') }}</label>
                            <select name="phone_verified" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Any') }}</option>
                                <option value="yes" @selected(request('phone_verified') === 'yes')>{{ __('Verified') }}</option>
                                <option value="no" @selected(request('phone_verified') === 'no')>{{ __('Pending') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Two Factor Enabled') }}</label>
                            <select name="two_factor" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Any') }}</option>
                                <option value="yes" @selected(request('two_factor') === 'yes')>{{ __('Enabled') }}</option>
                                <option value="no" @selected(request('two_factor') === 'no')>{{ __('Disabled') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Last Login') }}</label>
                            <select name="last_login" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Any') }}</option>
                                <option value="today" @selected(request('last_login') === 'today')>{{ __('Today') }}</option>
                                <option value="7d" @selected(request('last_login') === '7d')>{{ __('Last 7 days') }}</option>
                                <option value="30d" @selected(request('last_login') === '30d')>{{ __('Last 30 days') }}</option>
                                <option value="never" @selected(request('last_login') === 'never')>{{ __('Never') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Subscription Status') }}</label>
                            <select name="subscription" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Any') }}</option>
                                <option value="free" @selected(request('subscription') === 'free')>{{ __('Free') }}</option>
                                <option value="paid" @selected(request('subscription') === 'paid')>{{ __('Paid') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 mt-4">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-500 transition">
                            <x-heroicon-m-funnel class="w-4 h-4" />
                            {{ __('Apply Filters') }}
                        </button>

                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <x-heroicon-m-x-mark class="w-4 h-4" />
                            {{ __('Reset Filters') }}
                        </a>

                        <button type="button" @click="saveCurrentFilter()" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <x-heroicon-m-bookmark class="w-4 h-4" />
                            {{ __('Save Filter') }}
                        </button>
                    </div>
                </form>

                <div x-show="savedFilters.length > 0" class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                    <template x-for="filter in savedFilters" :key="filter.name">
                        <div class="inline-flex items-center gap-1 bg-gray-50 dark:bg-gray-800 rounded-full pl-3 pr-1 py-1 text-xs">
                            <a :href="'{{ route('admin.users.index') }}?' + filter.query" class="text-gray-700 dark:text-gray-200 hover:underline" x-text="filter.name"></a>
                            <button type="button" @click="removeSavedFilter(filter.name)" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 p-0.5">
                                <x-heroicon-m-x-mark class="w-3 h-3" />
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            @include('admin.users.partials.table')
            @include('admin.users.partials.drawer')
            @include('admin.users.partials.bulk-bar')
            @include('admin.users.partials.modals')
        </div>
    @endif

    @push('scripts')
        <script>
            function usersPage(config) {
                return {
                    allIds: config.ids,
                    usersData: config.users,
                    selected: [],
                    visibleColumns: { ...config.defaultColumns, ...(JSON.parse(localStorage.getItem('admin-users-columns') || 'null') || {}) },
                    columnWidths: JSON.parse(localStorage.getItem('admin-users-col-widths') || '{}'),
                    resizing: null,

                    drawerOpen: false,
                    activeUser: null,
                    activeTab: 'profile',

                    bulkAction: '',
                    bulkPlan: '',
                    bulkRole: '',
                    bulkSubject: '',
                    bulkBody: '',

                    savedFilters: JSON.parse(localStorage.getItem('admin-users-saved-filters') || '[]'),

                    get allSelected() {
                        return this.allIds.length > 0 && this.selected.length === this.allIds.length;
                    },

                    toggleColumn(key) {
                        this.visibleColumns[key] = !this.visibleColumns[key];
                        localStorage.setItem('admin-users-columns', JSON.stringify(this.visibleColumns));
                    },

                    openDrawer(id) {
                        this.activeUser = this.usersData[id];
                        this.activeTab = 'profile';
                        this.drawerOpen = true;
                    },

                    colWidth(key, fallback = 160) {
                        return (this.columnWidths[key] || fallback) + 'px';
                    },

                    startResize(event, key, fallback = 160) {
                        this.resizing = { key, startX: event.clientX, startWidth: this.columnWidths[key] || fallback };

                        const onMove = (e) => {
                            if (!this.resizing) return;
                            const delta = e.clientX - this.resizing.startX;
                            this.columnWidths = { ...this.columnWidths, [key]: Math.max(80, this.resizing.startWidth + delta) };
                        };

                        const onUp = () => {
                            this.resizing = null;
                            localStorage.setItem('admin-users-col-widths', JSON.stringify(this.columnWidths));
                            window.removeEventListener('mousemove', onMove);
                            window.removeEventListener('mouseup', onUp);
                        };

                        window.addEventListener('mousemove', onMove);
                        window.addEventListener('mouseup', onUp);
                    },

                    submitBulk(action) {
                        this.bulkAction = action;
                        this.$nextTick(() => this.$refs.bulkForm.submit());
                    },

                    saveCurrentFilter() {
                        const name = prompt('Name this filter:');

                        if (!name) return;

                        const formData = new FormData(this.$refs.filterForm);
                        const params = new URLSearchParams();

                        for (const [key, value] of formData.entries()) {
                            if (value !== '') params.append(key, value);
                        }

                        this.savedFilters = this.savedFilters.filter(f => f.name !== name);
                        this.savedFilters.push({ name, query: params.toString() });
                        localStorage.setItem('admin-users-saved-filters', JSON.stringify(this.savedFilters));
                    },

                    removeSavedFilter(name) {
                        this.savedFilters = this.savedFilters.filter(f => f.name !== name);
                        localStorage.setItem('admin-users-saved-filters', JSON.stringify(this.savedFilters));
                    },
                };
            }
        </script>
    @endpush
</x-admin-layout>
