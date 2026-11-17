export default () => ({
    open: false,
    toggle() {
        if (this.open) return this.close(true);
        this.open = true;
        this.setPageLocked(true);
        this.$nextTick(() => this.$refs.panel.querySelector('a')?.focus());
    },
    close(restoreFocus = false) {
        if (!this.open) return;
        this.open = false;
        this.setPageLocked(false);
        if (restoreFocus) this.$refs.toggle.focus();
    },
    setPageLocked(locked) {
        document.documentElement.classList.toggle('mobile-menu-open', locked);
        document.querySelectorAll('body > main, body > footer').forEach(element => {
            element.inert = locked;
        });
    },
    trapFocus(event) {
        if (!this.open || event.key !== 'Tab') return;
        const items = [...this.$el.querySelectorAll('a[href], button, input, select, [tabindex="0"]')]
            .filter(element => !element.disabled && element.getClientRects().length);
        const first = items[0];
        const last = items.at(-1);
        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    },
    onResize() { if (matchMedia('(min-width: 1001px)').matches) this.close(); },
    destroy() { this.setPageLocked(false); },
});
