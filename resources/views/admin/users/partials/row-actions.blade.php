{{-- Expects $user from the parent loop --}}

<x-dropdown align="right" width="56">
    <x-slot name="trigger">
        <button type="button" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
            <x-heroicon-m-ellipsis-horizontal class="w-5 h-5" />
        </button>
    </x-slot>

    <x-slot name="content">
        <x-dropdown-link href="#" @click.prevent="openDrawer({{ $user->id }})">{{ __('View Profile') }}</x-dropdown-link>
        <x-dropdown-link href="#" @click.prevent="$dispatch('open-modal', 'edit-user-{{ $user->id }}')">{{ __('Edit User') }}</x-dropdown-link>

        @unless ($user->is_admin || $user->id === auth()->id())
            <x-admin.dropdown-action action="{{ route('admin.users.impersonate', $user) }}">{{ __('Login As User') }}</x-admin.dropdown-action>
        @endunless

        <x-admin.dropdown-action action="{{ route('admin.users.reset-password', $user) }}">{{ __('Reset Password') }}</x-admin.dropdown-action>

        <x-dropdown-link href="#" @click.prevent="$dispatch('open-modal', 'change-plan-{{ $user->id }}')">{{ __('Change Plan') }}</x-dropdown-link>

        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

        <x-dropdown-link href="{{ url('admin/billing/subscriptions') }}">{{ __('Manage Subscription') }}</x-dropdown-link>
        <x-dropdown-link href="{{ url('admin/billing/orders') }}">{{ __('View Orders') }}</x-dropdown-link>
        <x-dropdown-link href="{{ url('admin/billing/invoices') }}">{{ __('View Invoices') }}</x-dropdown-link>
        <x-dropdown-link href="{{ url('admin/finance/transactions') }}">{{ __('View Transactions') }}</x-dropdown-link>

        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

        <x-dropdown-link href="#" @click.prevent="$dispatch('open-modal', 'notify-{{ $user->id }}')">{{ __('Send Notification') }}</x-dropdown-link>
        <x-dropdown-link href="#" @click.prevent="$dispatch('open-modal', 'notify-{{ $user->id }}')">{{ __('Send Email') }}</x-dropdown-link>

        @unless ($user->email_verified_at)
            <x-admin.dropdown-action action="{{ route('admin.users.verify-email', $user) }}">{{ __('Verify Email') }}</x-admin.dropdown-action>
        @endunless

        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

        <x-dropdown-link href="#" class="text-amber-600" @click.prevent="$dispatch('open-modal', 'suspend-{{ $user->id }}')">{{ __('Suspend') }}</x-dropdown-link>
        <x-dropdown-link href="#" class="text-red-600" @click.prevent="$dispatch('open-modal', 'ban-{{ $user->id }}')">{{ __('Ban') }}</x-dropdown-link>
        <x-dropdown-link href="#" class="text-red-600" @click.prevent="$dispatch('open-modal', 'delete-{{ $user->id }}')">{{ __('Delete') }}</x-dropdown-link>
    </x-slot>
</x-dropdown>
