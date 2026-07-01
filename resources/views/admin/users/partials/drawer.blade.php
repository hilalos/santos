<x-admin.drawer open="drawerOpen">
    <template x-if="activeUser">
        <div class="flex flex-col h-full">
            <div class="flex items-start justify-between px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-semibold" x-text="activeUser.name.split(' ').map(p => p[0]).slice(0,2).join('').toUpperCase()"></div>
                    <div>
                        <div class="font-semibold text-gray-900 dark:text-gray-100" x-text="activeUser.name"></div>
                        <div class="text-sm text-gray-500 dark:text-gray-400" x-text="activeUser.email"></div>
                    </div>
                </div>

                <button @click="drawerOpen = false" type="button" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200" aria-label="{{ __('Close') }}">
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                </button>
            </div>

            <div class="px-4 pt-3 border-b border-gray-100 dark:border-gray-800 flex flex-wrap gap-1 text-xs">
                <template x-for="tab in [
                    ['profile', '{{ __('Profile') }}'],
                    ['basic', '{{ __('Basic Info') }}'],
                    ['contact', '{{ __('Contact') }}'],
                    ['subscription', '{{ __('Subscription') }}'],
                    ['billing', '{{ __('Billing') }}'],
                    ['orders', '{{ __('Orders') }}'],
                    ['invoices', '{{ __('Invoices') }}'],
                    ['activity', '{{ __('Activity') }}'],
                    ['logins', '{{ __('Recent Logins') }}'],
                    ['devices', '{{ __('Devices') }}'],
                    ['sessions', '{{ __('Sessions') }}'],
                    ['api', '{{ __('API Usage') }}'],
                    ['security', '{{ __('Security') }}'],
                    ['notes', '{{ __('Notes') }}'],
                ]" :key="tab[0]">
                    <button
                        @click="activeTab = tab[0]"
                        type="button"
                        class="px-2.5 py-1.5 rounded-t-lg border-b-2"
                        :class="activeTab === tab[0] ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 font-medium' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                        x-text="tab[1]"
                    ></button>
                </template>
            </div>

            <div class="flex-1 overflow-y-auto p-6 text-sm">
                {{-- Profile --}}
                <div x-show="activeTab === 'profile'" class="space-y-3">
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Role') }}</span><span x-text="activeUser.role"></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Plan') }}</span><span x-text="activeUser.plan"></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Status') }}</span><span x-text="activeUser.status"></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Registered') }}</span><span x-text="activeUser.registeredAt"></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Last Login') }}</span><span x-text="activeUser.lastLoginAt"></span></div>
                </div>

                {{-- Basic Information --}}
                <div x-show="activeTab === 'basic'" class="space-y-3">
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Name') }}</span><span x-text="activeUser.name"></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Email') }}</span><span x-text="activeUser.email"></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Country') }}</span><span x-text="activeUser.countryName"></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Registered') }}</span><span x-text="activeUser.registeredAt"></span></div>
                </div>

                {{-- Contact --}}
                <div x-show="activeTab === 'contact'" class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">{{ __('Email') }}</span>
                        <span x-text="activeUser.email"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">{{ __('Email Verified') }}</span>
                        <span x-text="activeUser.emailVerified ? '{{ __('Yes') }}' : '{{ __('No') }}'"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">{{ __('Phone') }}</span>
                        <span x-text="activeUser.phone || '—'"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">{{ __('Phone Verified') }}</span>
                        <span x-text="activeUser.phoneVerified ? '{{ __('Yes') }}' : '{{ __('No') }}'"></span>
                    </div>
                </div>

                {{-- Subscription --}}
                <div x-show="activeTab === 'subscription'" class="space-y-3">
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Current Plan') }}</span><span x-text="activeUser.plan"></span></div>
                    <p class="text-gray-400 text-xs pt-2">{{ __('Full billing/subscription management is not built yet — coming soon.') }}</p>
                </div>

                {{-- Placeholders --}}
                <template x-for="tab in ['billing', 'orders', 'invoices', 'api']">
                    <div x-show="activeTab === tab" class="text-center py-10">
                        <div class="text-3xl mb-2">🚧</div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('Coming soon.') }}</p>
                    </div>
                </template>

                {{-- Activity Timeline --}}
                <div x-show="activeTab === 'activity'">
                    <ul class="space-y-3">
                        <li class="flex gap-2"><span class="text-gray-400">•</span><span>{{ __('Registered on') }} <span x-text="activeUser.registeredAt"></span></span></li>
                        <li class="flex gap-2" x-show="activeUser.emailVerified"><span class="text-gray-400">•</span><span>{{ __('Verified their email address') }}</span></li>
                        <li class="flex gap-2"><span class="text-gray-400">•</span><span>{{ __('Last active') }} <span x-text="activeUser.lastLoginAt"></span></span></li>
                        <template x-for="login in activeUser.logins.slice(0, 5)" :key="login.at">
                            <li class="flex gap-2"><span class="text-gray-400">•</span><span>{{ __('Signed in from') }} <span x-text="login.location"></span> <span class="text-gray-400" x-text="'(' + login.at + ')'"></span></span></li>
                        </template>
                    </ul>
                    <p x-show="activeUser.logins.length === 0" class="text-gray-400 text-sm">{{ __('No recorded activity yet.') }}</p>
                </div>

                {{-- Recent Logins --}}
                <div x-show="activeTab === 'logins'">
                    <template x-for="login in activeUser.logins" :key="login.at + login.ip">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-800 last:border-0">
                            <div>
                                <div class="text-gray-900 dark:text-gray-100" x-text="login.device"></div>
                                <div class="text-xs text-gray-500" x-text="login.location + ' · ' + login.ip"></div>
                            </div>
                            <div class="text-xs text-gray-400" x-text="login.at"></div>
                        </div>
                    </template>
                    <p x-show="activeUser.logins.length === 0" class="text-gray-400 text-sm">{{ __('No login history recorded.') }}</p>
                </div>

                {{-- Devices (derived from login history) --}}
                <div x-show="activeTab === 'devices'">
                    <template x-for="device in [...new Set(activeUser.logins.map(l => l.device))]" :key="device">
                        <div class="flex items-center gap-2 py-2 border-b border-gray-100 dark:border-gray-800 last:border-0">
                            <x-heroicon-o-device-phone-mobile class="w-4 h-4 text-gray-400" />
                            <span x-text="device"></span>
                        </div>
                    </template>
                    <p x-show="activeUser.logins.length === 0" class="text-gray-400 text-sm">{{ __('No known devices yet.') }}</p>
                </div>

                {{-- Sessions (live, from the sessions table) --}}
                <div x-show="activeTab === 'sessions'">
                    <template x-for="session in activeUser.sessions" :key="session.ip + session.lastActivity">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-800 last:border-0">
                            <div>
                                <div class="text-gray-900 dark:text-gray-100" x-text="session.device"></div>
                                <div class="text-xs text-gray-500" x-text="session.ip"></div>
                            </div>
                            <div class="text-xs text-gray-400" x-text="session.lastActivity"></div>
                        </div>
                    </template>
                    <p x-show="activeUser.sessions.length === 0" class="text-gray-400 text-sm">{{ __('No active sessions right now.') }}</p>
                </div>

                {{-- Security --}}
                <div x-show="activeTab === 'security'" class="space-y-3">
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Two Factor Authentication') }}</span><span x-text="activeUser.twoFactorEnabled ? '{{ __('Enabled') }}' : '{{ __('Disabled') }}'"></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Email Verified') }}</span><span x-text="activeUser.emailVerified ? '{{ __('Yes') }}' : '{{ __('No') }}'"></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Phone Verified') }}</span><span x-text="activeUser.phoneVerified ? '{{ __('Yes') }}' : '{{ __('No') }}'"></span></div>
                    <div class="flex justify-between"><span class="text-gray-500">{{ __('Active Sessions') }}</span><span x-text="activeUser.sessions.length"></span></div>
                </div>

                {{-- Notes --}}
                <div x-show="activeTab === 'notes'">
                    <template x-for="note in activeUser.notes" :key="note.at + note.body">
                        <div class="py-2 border-b border-gray-100 dark:border-gray-800 last:border-0">
                            <p class="text-gray-800 dark:text-gray-200" x-text="note.body"></p>
                            <p class="text-xs text-gray-400 mt-1" x-text="note.author + ' · ' + note.at"></p>
                        </div>
                    </template>
                    <p x-show="activeUser.notes.length === 0" class="text-gray-400 text-sm mb-3">{{ __('No notes yet.') }}</p>

                    <form method="POST" :action="'{{ url('admin/users') }}/' + (activeUser ? activeUser.id : '') + '/notes'" class="mt-4">
                        @csrf
                        <textarea name="body" rows="2" placeholder="{{ __('Add a note about this user...') }}" required class="block w-full text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm"></textarea>
                        <button type="submit" class="mt-2 text-sm px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500">{{ __('Add Note') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</x-admin.drawer>
