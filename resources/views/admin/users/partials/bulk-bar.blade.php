{{-- Hidden POST form used by every bulk action; hydrated with the current selection before submit --}}
<form method="POST" action="{{ route('admin.users.bulk') }}" x-ref="bulkForm" class="hidden">
    @csrf
    <input type="hidden" name="action" :value="bulkAction">
    <template x-for="id in selected" :key="id">
        <input type="hidden" name="user_ids[]" :value="id">
    </template>
    <input type="hidden" name="plan" :value="bulkPlan">
    <input type="hidden" name="role" :value="bulkRole">
    <input type="hidden" name="subject" :value="bulkSubject">
    <input type="hidden" name="body" :value="bulkBody">
</form>

{{-- Hidden GET form used for exporting only the selected rows --}}
<form method="GET" action="{{ route('admin.users.export') }}" x-ref="bulkExportForm" target="_blank" class="hidden">
    <template x-for="id in selected" :key="id">
        <input type="hidden" name="ids[]" :value="id">
    </template>
</form>

<div
    x-show="selected.length > 0"
    x-transition
    x-cloak
    class="fixed bottom-6 inset-x-0 z-30 flex justify-center px-4"
>
    <div class="bg-gray-900 text-white rounded-xl shadow-lg px-4 py-3 flex flex-wrap items-center gap-2">
        <span class="text-sm font-medium pr-2" x-text="selected.length + ' {{ __('selected') }}'"></span>

        <button type="button" @click="$refs.bulkExportForm.requestSubmit()" class="px-3 py-1.5 text-sm rounded-lg hover:bg-white/10">{{ __('Export') }}</button>
        <button type="button" @click="$dispatch('open-modal', 'bulk-assign-plan')" class="px-3 py-1.5 text-sm rounded-lg hover:bg-white/10">{{ __('Assign Plan') }}</button>
        <button type="button" @click="$dispatch('open-modal', 'bulk-assign-role')" class="px-3 py-1.5 text-sm rounded-lg hover:bg-white/10">{{ __('Assign Role') }}</button>
        <button type="button" @click="submitBulk('verify_email')" class="px-3 py-1.5 text-sm rounded-lg hover:bg-white/10">{{ __('Verify Emails') }}</button>
        <button type="button" @click="$dispatch('open-modal', 'bulk-suspend')" class="px-3 py-1.5 text-sm rounded-lg hover:bg-white/10">{{ __('Suspend') }}</button>
        <button type="button" @click="submitBulk('activate')" class="px-3 py-1.5 text-sm rounded-lg hover:bg-white/10">{{ __('Activate') }}</button>
        <button type="button" @click="$dispatch('open-modal', 'bulk-notify')" class="px-3 py-1.5 text-sm rounded-lg hover:bg-white/10">{{ __('Send Notification') }}</button>
        <button type="button" @click="$dispatch('open-modal', 'bulk-delete')" class="px-3 py-1.5 text-sm rounded-lg bg-red-600 hover:bg-red-500">{{ __('Delete') }}</button>

        <button type="button" @click="selected = []" class="ml-1 p-1.5 rounded-lg hover:bg-white/10" aria-label="{{ __('Clear selection') }}">
            <x-heroicon-m-x-mark class="w-4 h-4" />
        </button>
    </div>
</div>

<x-modal name="bulk-assign-plan" focusable maxWidth="sm">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Assign Plan') }}</h2>
        <p class="mt-1 text-sm text-gray-500" x-text="selected.length + ' {{ __('users selected') }}'"></p>

        <select x-model="bulkPlan" class="mt-4 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm">
            @foreach (\App\Enums\UserPlan::cases() as $plan)
                <option value="{{ $plan->value }}">{{ $plan->label() }}</option>
            @endforeach
        </select>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-primary-button type="button" @click="$dispatch('close'); submitBulk('assign_plan')">{{ __('Apply') }}</x-primary-button>
        </div>
    </div>
</x-modal>

<x-modal name="bulk-assign-role" focusable maxWidth="sm">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Assign Role') }}</h2>
        <p class="mt-1 text-sm text-gray-500" x-text="selected.length + ' {{ __('users selected') }}'"></p>

        <select x-model="bulkRole" class="mt-4 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm">
            @foreach (\App\Enums\UserRole::cases() as $role)
                <option value="{{ $role->value }}">{{ $role->label() }}</option>
            @endforeach
        </select>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-primary-button type="button" @click="$dispatch('close'); submitBulk('assign_role')">{{ __('Apply') }}</x-primary-button>
        </div>
    </div>
</x-modal>

<x-modal name="bulk-notify" focusable maxWidth="md">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Send Notification') }}</h2>
        <p class="mt-1 text-sm text-gray-500" x-text="selected.length + ' {{ __('users selected') }}'"></p>

        <div class="mt-4">
            <x-input-label value="{{ __('Subject') }}" />
            <x-text-input x-model="bulkSubject" class="block mt-1 w-full" />
        </div>

        <div class="mt-4">
            <x-input-label value="{{ __('Message') }}" />
            <textarea x-model="bulkBody" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 shadow-sm"></textarea>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-primary-button type="button" @click="$dispatch('close'); submitBulk('notify')">{{ __('Send') }}</x-primary-button>
        </div>
    </div>
</x-modal>

<x-modal name="bulk-suspend" focusable maxWidth="sm">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Suspend selected users?') }}</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400" x-text="selected.length + ' {{ __('users will be suspended.') }}'"></p>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-danger-button type="button" @click="$dispatch('close'); submitBulk('suspend')">{{ __('Suspend') }}</x-danger-button>
        </div>
    </div>
</x-modal>

<x-modal name="bulk-delete" focusable maxWidth="sm">
    <div class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Delete selected users?') }}</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400" x-text="selected.length + ' {{ __('users will be deleted. This can be recovered by a database administrator.') }}'"></p>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-danger-button type="button" @click="$dispatch('close'); submitBulk('delete')">{{ __('Delete') }}</x-danger-button>
        </div>
    </div>
</x-modal>
