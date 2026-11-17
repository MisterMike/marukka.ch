export default () => ({
    label: 'Copy code',
    announcement: '',
    timer: null,
    async copy() {
        try {
            if (!navigator.clipboard?.writeText) throw new Error('Clipboard unavailable');
            await navigator.clipboard.writeText(this.$refs.code.textContent);
            this.label = 'Copied';
            this.announcement = 'Code copied to clipboard.';
        } catch {
            this.label = 'Select code';
            this.announcement = 'Copy is unavailable. The code is selected; use your keyboard to copy it.';
            const selection = window.getSelection();
            const range = document.createRange();
            range.selectNodeContents(this.$refs.code);
            selection.removeAllRanges();
            selection.addRange(range);
        }
        clearTimeout(this.timer);
        this.timer = setTimeout(() => { this.label = 'Copy code'; this.announcement = ''; }, 3000);
    },
    destroy() { clearTimeout(this.timer); },
});
