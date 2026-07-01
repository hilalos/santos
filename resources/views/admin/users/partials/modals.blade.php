{{-- Global modals --}}
<x-modal name="create-user" focusable maxWidth="lg">
    <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
        @csrf

        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Create User') }}</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
            <div class="sm:col-span-2">
                <x-input-label for="create_name" value="{{ __('Name') }}" />
                <x-text-input id="create_name" name="name" class="block mt-1 w-full" required />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="create_email" value="{{ __('Email') }}" />
                <x-text-input id="create_email" name="email" type="email" class="block mt-1 w-full" required />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="create_password" value="{{ __('Password') }}" />
                <x-text-input id="create_password" name="password" type="password" class="block mt-1 w-full" required />
            </div>

            <div>
                <x-input-label for="create_role" value="{{ __('Role') }}" />
                <select id="create_role" name="role" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm">
                    @foreach (\App\Enums\UserRole::cases() as $role)
                        <option value="{{ $role->value }}">{{ $role->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-input-label for="create_plan" value="{{ __('Plan') }}" />
                <select id="create_plan" name="plan" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm">
                    @foreach (\App\Enums\UserPlan::cases() as $plan)
                        <option value="{{ $plan->value }}">{{ $plan->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-input-label for="create_status" value="{{ __('Status') }}" />
                <select id="create_status" name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm">
                    @foreach (\App\Enums\UserStatus::cases() as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-input-label for="create_country" value="{{ __('Country') }}" />
                <select id="create_country" name="country" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm">
                    <option value="">—</option>
                    @foreach (config('countries') as $code => $countryName)
                        <option value="{{ $code }}">{{ $countryName }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-primary-button type="submit">{{ __('Create User') }}</x-primary-button>
        </div>
    </form>
</x-modal>

<x-modal name="import-users" focusable maxWidth="md">
    <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data" class="p-6">
        @csrf

        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Import Users') }}</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Upload a CSV with a header row containing name, email and (optionally) country. Rows with an existing email are skipped.') }}
        </p>

        <input type="file" name="file" accept=".csv,.txt" required class="mt-4 block w-full text-sm text-gray-600 dark:text-gray-300">

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-primary-button type="submit">{{ __('Import') }}</x-primary-button>
        </div>
    </form>
</x-modal>

{{-- Per-row modals --}}
@foreach ($users as $user)
    <x-modal name="edit-user-{{ $user->id }}" focusable maxWidth="lg">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6">
            @csrf
            @method('PATCH')

            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Edit :name', ['name' => $user->name]) }}</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                <div class="sm:col-span-2">
                    <x-input-label value="{{ __('Name') }}" />
                    <x-text-input name="name" class="block mt-1 w-full" value="{{ $user->name }}" required />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label value="{{ __('Email') }}" />
                    <x-text-input name="email" type="email" class="block mt-1 w-full" value="{{ $user->email }}" required />
                </div>

                <div>
                    <x-input-label value="{{ __('Phone') }}" />
                    <x-text-input name="phone" class="block mt-1 w-full" value="{{ $user->phone }}" />
                </div>

                <div>
                    <x-input-label value="{{ __('Role') }}" />
                    <select name="role" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm">
                        @foreach (\App\Enums\UserRole::cases() as $role)
                            <option value="{{ $role->value }}" @selected($user->role === $role)>{{ $role->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label value="{{ __('Plan') }}" />
                    <select name="plan" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm">
                        @foreach (\App\Enums\UserPlan::cases() as $plan)
                            <option value="{{ $plan->value }}" @selected($user->plan === $plan)>{{ $plan->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label value="{{ __('Country') }}" />
                    <select name="country" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm">
                        <option value="">—</option>
                        @foreach (config('countries') as $code => $countryName)
                            <option value="{{ $code }}" @selected($user->country === $code)>{{ $countryName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button type="submit">{{ __('Save Changes') }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="change-plan-{{ $user->id }}" focusable maxWidth="sm">
        <form method="POST" action="{{ route('admin.users.plan', $user) }}" class="p-6">
            @csrf

            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Change Plan for :name', ['name' => $user->name]) }}</h2>

            <select name="plan" class="mt-4 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm">
                @foreach (\App\Enums\UserPlan::cases() as $plan)
                    <option value="{{ $plan->value }}" @selected($user->plan === $plan)>{{ $plan->label() }}</option>
                @endforeach
            </select>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button type="submit">{{ __('Save') }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="notify-{{ $user->id }}" focusable maxWidth="md">
        <form method="POST" action="{{ route('admin.users.notify', $user) }}" class="p-6">
            @csrf

            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Message :name', ['name' => $user->name]) }}</h2>

            <div class="mt-4">
                <x-input-label value="{{ __('Subject') }}" />
                <x-text-input name="subject" class="block mt-1 w-full" required />
            </div>

            <div class="mt-4">
                <x-input-label value="{{ __('Message') }}" />
                <textarea name="body" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm"></textarea>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button type="submit">{{ __('Send') }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-admin.confirm-modal
        name="suspend-{{ $user->id }}"
        title="{{ __('Suspend :name?', ['name' => $user->name]) }}"
        message="{{ __('They will be unable to sign in until reactivated. This can be undone at any time.') }}"
        action="{{ route('admin.users.status', $user) }}"
        confirm-label="{{ __('Suspend') }}"
    >
        <x-slot:extraFields>
            <input type="hidden" name="status" value="suspended">
        </x-slot:extraFields>
    </x-admin.confirm-modal>

    <x-admin.confirm-modal
        name="ban-{{ $user->id }}"
        title="{{ __('Ban :name?', ['name' => $user->name]) }}"
        message="{{ __('This permanently blocks their access unless manually reversed. Use for serious violations.') }}"
        action="{{ route('admin.users.status', $user) }}"
        confirm-label="{{ __('Ban') }}"
    >
        <x-slot:extraFields>
            <input type="hidden" name="status" value="banned">
        </x-slot:extraFields>
    </x-admin.confirm-modal>

    <x-admin.confirm-modal
        name="delete-{{ $user->id }}"
        title="{{ __('Delete :name?', ['name' => $user->name]) }}"
        message="{{ __('This removes them from all lists. The record is kept for recovery by a database administrator.') }}"
        action="{{ route('admin.users.destroy', $user) }}"
        method="DELETE"
        confirm-label="{{ __('Delete') }}"
    />
@endforeach
