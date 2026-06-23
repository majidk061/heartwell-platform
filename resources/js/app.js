import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('mobileNav', () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    },
}));

Alpine.data('pathwayAccordion', (initialOpen = null) => ({
    openItem: initialOpen,
    toggle(slug) {
        this.openItem = this.openItem === slug ? null : slug;
    },
    isOpen(slug) {
        return this.openItem === slug;
    },
}));

Alpine.data('formHandler', () => ({
    submitting: false,
    success: false,
    error: false,
    async submit(event) {
        this.submitting = true;
        this.success = false;
        this.error = false;

        try {
            const form = event.target;
            const response = await fetch(form.action, {
                method: form.method || 'POST',
                body: new FormData(form),
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error('Form submission failed');
            }

            this.success = true;
            form.reset();
        } catch {
            this.error = true;
        } finally {
            this.submitting = false;
        }
    },
}));

Alpine.start();
