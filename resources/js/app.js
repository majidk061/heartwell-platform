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

Alpine.data('testimonialCarousel', (config) => ({
    index: 0,
    visible: config.visible ?? 1,
    total: config.total ?? 0,
    autoplay: config.autoplay ?? false,
    interval: (config.interval ?? 6) * 1000,
    timer: null,
    get maxIndex() {
        return Math.max(0, this.total - this.visible);
    },
    init() {
        if (this.autoplay && ! window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            this.timer = setInterval(() => {
                this.index = this.index >= this.maxIndex ? 0 : this.index + 1;
            }, this.interval);
        }
    },
    destroy() {
        if (this.timer) {
            clearInterval(this.timer);
        }
    },
    next() {
        this.index = Math.min(this.index + 1, this.maxIndex);
    },
    prev() {
        this.index = Math.max(this.index - 1, 0);
    },
}));

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const track = (name, params = {}) => {
        if (typeof gtag === 'function') {
            gtag('event', name, params);
        }
    };

    document.querySelectorAll('form[action*="contact"]').forEach((form) => {
        form.addEventListener('submit', () => {
            const id = form.getAttribute('id') || form.action;
            track('form_submit', { form_id: id });
        });
    });

    document.querySelectorAll('a[href*="#book"], a[href*="#consultation"], a[href*="#waitlist"]').forEach((link) => {
        link.addEventListener('click', () => {
            track('cta_click', { link_url: link.getAttribute('href') });
        });
    });
});
