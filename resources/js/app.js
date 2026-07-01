import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;

Alpine.plugin(focus);
Alpine.plugin(collapse);

Alpine.start();

// Global Ctrl/Cmd+K shortcut: focuses the page's primary search input, if any.
document.addEventListener('keydown', (event) => {
    if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
        const search = document.getElementById('global-search');

        if (search) {
            event.preventDefault();
            search.focus();
            search.select();
        }
    }
});
