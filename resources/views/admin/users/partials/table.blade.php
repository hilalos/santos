{{-- Expects $users (paginator|null), $toggleableColumns (array) from the parent view --}}

<div class="flex items-center justify-between mb-3">
    <div class="text-sm text-gray-500 dark:text-gray-400">
        {{ __(':count users found', ['count' => $users->total()]) }}
    </div>

    <x-dropdown align="right" width="56">
        <x-slot name="trigger">
            <button type="button" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <x-heroicon-o-view-columns class="w-4 h-4" />
                {{ __('Columns') }}
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">{{ __('Visible columns') }}</div>
            @foreach ($toggleableColumns as $key => $label)
                <label class="flex items-center gap-2 px-4 py-1.5 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" x-model="visibleColumns.{{ $key }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    {{ $label }}
                </label>
            @endforeach
        </x-slot>
    </x-dropdown>
</div>

<div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm overflow-hidden">
    @if ($users->isEmpty())
        <x-admin.empty-state
            title="{{ __('No users found') }}"
            message="{{ __('Try adjusting your filters, or create the first user.') }}"
            actionLabel="{{ __('Create First User') }}"
            @click="$dispatch('open-modal', 'create-user')"
        />
    @else
        <div class="overflow-x-auto">
            <div class="max-h-[65vh] overflow-y-auto">
                <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-gray-800 text-sm">
                    <thead class="sticky top-0 z-10 bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col" class="px-4 py-3 w-10">
                                <input type="checkbox" :checked="allSelected" @click="selected = allSelected ? [] : [...allIds]" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>

                            <th x-show="visibleColumns.avatar" scope="col" class="px-4 py-3 w-14"></th>

                            <th scope="col" class="relative px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide" :style="{ width: colWidth('name', 200) }">
                                {{ __('Full Name') }}
                                <span @mousedown="startResize($event, 'name', 200)" class="absolute right-0 top-0 h-full w-1 cursor-col-resize hover:bg-indigo-400"></span>
                            </th>

                            <th x-show="visibleColumns.email" scope="col" class="relative px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide" :style="{ width: colWidth('email', 220) }">
                                {{ __('Email') }}
                                <span @mousedown="startResize($event, 'email', 220)" class="absolute right-0 top-0 h-full w-1 cursor-col-resize hover:bg-indigo-400"></span>
                            </th>

                            <th x-show="visibleColumns.role" scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Role') }}</th>

                            <th x-show="visibleColumns.plan" scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Plan') }}</th>

                            <th x-show="visibleColumns.country" scope="col" class="relative px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide" :style="{ width: colWidth('country', 140) }">
                                {{ __('Country') }}
                                <span @mousedown="startResize($event, 'country', 140)" class="absolute right-0 top-0 h-full w-1 cursor-col-resize hover:bg-indigo-400"></span>
                            </th>

                            <x-admin.sortable-th field="created_at" label="{{ __('Registration Date') }}" x-show="visibleColumns.created_at" />

                            <x-admin.sortable-th field="last_login_at" label="{{ __('Last Login') }}" x-show="visibleColumns.last_login_at" />

                            <th x-show="visibleColumns.email_verified" scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Email Verification') }}</th>

                            <th x-show="visibleColumns.subscription" scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Subscription') }}</th>

                            <x-admin.sortable-th field="status" label="{{ __('Status') }}" x-show="visibleColumns.status" />

                            <th scope="col" class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-colors">
                                <td class="px-4 py-3">
                                    <input type="checkbox" value="{{ $user->id }}" x-model.number="selected" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </td>

                                <td x-show="visibleColumns.avatar" class="px-4 py-3">
                                    <x-admin.avatar :name="$user->name" />
                                </td>

                                <td class="px-4 py-3">
                                    <button type="button" @click="openDrawer({{ $user->id }})" class="font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 text-left">
                                        {{ $user->name }}
                                    </button>
                                </td>

                                <td x-show="visibleColumns.email" class="px-4 py-3 text-gray-600 dark:text-gray-400 truncate">{{ $user->email }}</td>

                                <td x-show="visibleColumns.role" class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $user->role->label() }}</td>

                                <td x-show="visibleColumns.plan" class="px-4 py-3">
                                    <x-admin.badge :color="$user->plan->badgeColor()">{{ $user->plan->label() }}</x-admin.badge>
                                </td>

                                <td x-show="visibleColumns.country" class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ config('countries')[$user->country] ?? ($user->country ?? '—') }}
                                </td>

                                <td x-show="visibleColumns.created_at" class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $user->created_at->format('M j, Y') }}</td>

                                <td x-show="visibleColumns.last_login_at" class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $user->last_login_at?->diffForHumans() ?? __('Never') }}</td>

                                <td x-show="visibleColumns.email_verified" class="px-4 py-3">
                                    @if ($user->email_verified_at)
                                        <x-admin.badge color="green">{{ __('Verified') }}</x-admin.badge>
                                    @else
                                        <x-admin.badge color="amber">{{ __('Pending') }}</x-admin.badge>
                                    @endif
                                </td>

                                <td x-show="visibleColumns.subscription" class="px-4 py-3">
                                    <x-admin.badge :color="$user->plan->isPaid() ? 'indigo' : 'gray'">
                                        {{ $user->plan->isPaid() ? __('Active') : __('Free') }}
                                    </x-admin.badge>
                                </td>

                                <td x-show="visibleColumns.status" class="px-4 py-3">
                                    <x-admin.badge :color="$user->status->badgeColor()">{{ $user->status->label() }}</x-admin.badge>
                                </td>

                                <td class="px-4 py-3 text-right">
                                    @include('admin.users.partials.row-actions', ['user' => $user])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">
            {{ $users->onEachSide(1)->links() }}
        </div>
    @endif
</div>
