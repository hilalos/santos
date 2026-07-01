@props(['open' => 'drawerOpen'])

<div
    x-show="{{ $open }}"
    x-cloak
    class="fixed inset-0 z-40"
    x-trap.noscroll="{{ $open }}"
>
    <div
        x-show="{{ $open }}"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="{{ $open }} = false"
        class="absolute inset-0 bg-gray-900/50"
    ></div>

    <div
        x-show="{{ $open }}"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        @keydown.escape.window="{{ $open }} = false"
        class="absolute inset-y-0 right-0 w-full sm:w-[28rem] bg-white dark:bg-gray-900 shadow-xl flex flex-col"
        role="dialog"
        aria-modal="true"
    >
        {{ $slot }}
    </div>
</div>
