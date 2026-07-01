@props([
    'name',
    'title',
    'message',
    'action',
    'method' => 'POST',
    'confirmLabel' => 'Confirm',
    'danger' => true,
])

<x-modal :name="$name" focusable maxWidth="md">
    <form method="POST" action="{{ $action }}" class="p-6">
        @csrf
        @if (strtoupper($method) !== 'POST')
            @method($method)
        @endif

        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ $title }}
        </h2>

        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ $message }}
        </p>

        {{ $extraFields ?? '' }}

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            @if ($danger)
                <x-danger-button type="submit">
                    {{ $confirmLabel }}
                </x-danger-button>
            @else
                <x-primary-button type="submit">
                    {{ $confirmLabel }}
                </x-primary-button>
            @endif
        </div>
    </form>
</x-modal>
