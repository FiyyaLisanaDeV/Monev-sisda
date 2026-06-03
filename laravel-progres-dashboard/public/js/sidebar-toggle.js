(function () {
    const storageKey = 'sisda-sidebar-mode';
    const autoClass = 'sisda-sidebar-autohide';
    const buttonClass = 'sisda-sidebar-toggle';

    function isDesktop() {
        return window.matchMedia('(min-width: 1024px)').matches;
    }

    function isAutoHide() {
        return localStorage.getItem(storageKey) !== 'pinned';
    }

    function applyState(button) {
        const autoHide = isDesktop() && isAutoHide();

        document.body.classList.toggle(autoClass, autoHide);

        if (!button) {
            return;
        }

        button.setAttribute('aria-pressed', autoHide ? 'true' : 'false');
        button.setAttribute('aria-label', autoHide ? 'Pin sidebar' : 'Aktifkan auto-hide sidebar');
        button.setAttribute('title', autoHide ? 'Pin sidebar' : 'Aktifkan auto-hide sidebar');

        const icon = button.querySelector('.material-symbols-outlined');
        const text = button.querySelector('.sisda-sidebar-toggle__text');

        if (icon) {
            icon.textContent = autoHide ? 'dock_to_right' : 'left_panel_close';
        }

        if (text) {
            text.textContent = autoHide ? 'Pin sidebar' : 'Auto-hide';
        }
    }

    function ensureButton() {
        if (!isDesktop()) {
            return null;
        }

        const topbar = document.querySelector('.fi-topbar');
        if (!topbar) {
            return null;
        }

        const existing = topbar.querySelector(`.${buttonClass}`);
        if (existing) {
            applyState(existing);
            return existing;
        }

        const button = document.createElement('button');
        button.type = 'button';
        button.className = buttonClass;
        button.innerHTML = [
            '<span class="material-symbols-outlined" aria-hidden="true"></span>',
            '<span class="sisda-sidebar-toggle__text"></span>',
        ].join('');

        button.addEventListener('click', function () {
            localStorage.setItem(storageKey, isAutoHide() ? 'pinned' : 'auto');
            applyState(button);
        });

        topbar.insertBefore(button, topbar.firstElementChild);
        applyState(button);

        return button;
    }

    function boot() {
        const button = ensureButton();
        applyState(button);
    }

    document.addEventListener('DOMContentLoaded', boot);
    document.addEventListener('livewire:navigated', boot);
    window.addEventListener('resize', boot);
})();
