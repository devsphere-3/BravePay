import Alpine from 'alpinejs';

window.Alpine = Alpine;

// ─── Alpine Components ────────────────────────────────────────

// Mobile navbar
Alpine.data('navbar', () => ({
    open: false,
    scrolled: false,
    init() {
        window.addEventListener('scroll', () => {
            this.scrolled = window.scrollY > 20;
        });
    },
    toggle() { this.open = !this.open; },
    close() { this.open = false; },
}));

// FAQ accordion
Alpine.data('faq', () => ({
    active: null,
    toggle(index) {
        this.active = this.active === index ? null : index;
    },
}));

// Registration form
Alpine.data('registrationForm', (pricePerParticipant = 0, minPurchase = 1, unitLabel = 'peserta', flashMessage = '', competitionName = '') => ({
    participantCount: Math.max(1, minPurchase),
    participants: [],
    pricePerParticipant,
    minPurchase,
    unitLabel,
    competitionName,
    // Modal state — intentionally NOT set via flashMessage here;
    // we initialise in init() so x-show has full control from the start.
    minErrorOpen: false,
    minErrorMessage: '',
    minErrorIsInfo: false,   // true = info on page load, false = warning on submit
    get total() {
        return this.participantCount * this.pricePerParticipant;
    },
    get formattedTotal() {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(this.total);
    },
    /**
     * Tampilkan modal peringatan minimum.
     * @param {string} message  - pesan kustom (opsional)
     * @param {boolean} isInfo  - true = info awal halaman, false = warning submit
     */
    showMinError(message = '', isInfo = false) {
        const name = this.competitionName ? `"${this.competitionName}"` : 'event ini';
        const unitText = this.minPurchase === 1
            ? `1 ${this.unitLabel}`
            : `${this.minPurchase} ${this.unitLabel}`;

        this.minErrorMessage = message || (
            isInfo
                ? `Event ${name} mengharuskan pendaftaran minimal ${unitText} dalam satu transaksi. Jumlah peserta sudah otomatis disesuaikan.`
                : `Minimal pembelian untuk event ${name} adalah ${unitText}. Silakan sesuaikan jumlah peserta sebelum melanjutkan.`
        );
        this.minErrorIsInfo = isInfo;
        this.minErrorOpen = true;
    },
    closeMinError() {
        this.minErrorOpen = false;
        this.minErrorMessage = '';
    },
    syncParticipants() {
        const count = Math.max(this.minPurchase || 1, Number(this.participantCount) || this.minPurchase || 1);
        this.participantCount = count;
        while (this.participants.length < count) {
            this.participants.push({ name: '', date_of_birth: '' });
        }
        this.participants = this.participants.slice(0, count);
    },
    validateCount(value) {
        const safeValue = Number.parseInt(value, 10);
        const min = Math.max(1, this.minPurchase || 1);

        if (Number.isFinite(safeValue) && safeValue < min) {
            this.participantCount = min;
            this.showMinError('', false);
            this.syncParticipants();
            return;
        }

        const nextValue = Number.isFinite(safeValue) ? Math.max(min, safeValue) : min;
        this.participantCount = nextValue;
        this.syncParticipants();
    },
    updateCount(value) {
        this.validateCount(value);
    },
    init() {
        this.syncParticipants();

        // Jika ada flash error dari server, tampilkan sebagai warning
        if (flashMessage) {
            this.$nextTick(() => {
                this.minErrorMessage = flashMessage;
                this.minErrorIsInfo = false;
                this.minErrorOpen = true;
            });
            return;
        }

        // Jika min_purchase > 1, tampilkan info saat halaman pertama dibuka
        if (this.minPurchase > 1) {
            this.$nextTick(() => this.showMinError('', true));
        }
    },
}));

// Payment countdown
Alpine.data('paymentCountdown', (expiresAt) => ({
    timeLeft: 0,
    interval: null,
    get minutes() { return Math.floor(this.timeLeft / 60); },
    get seconds() { return this.timeLeft % 60; },
    get formattedTime() {
        return `${String(this.minutes).padStart(2,'0')}:${String(this.seconds).padStart(2,'0')}`;
    },
    get isExpired() { return this.timeLeft <= 0; },
    get isUrgent() { return this.timeLeft <= 300 && this.timeLeft > 0; },
    init() {
        const end = new Date(expiresAt).getTime();
        const update = () => {
            const now = Date.now();
            this.timeLeft = Math.max(0, Math.floor((end - now) / 1000));
            if (this.isExpired && this.interval) {
                clearInterval(this.interval);
            }
        };
        update();
        this.interval = setInterval(update, 1000);
    },
    destroy() {
        if (this.interval) clearInterval(this.interval);
    },
}));

// Help center floating widget
Alpine.data('helpCenter', () => ({
    open: false,
    activeTab: 'resend',
    phone: '',
    orderCode: '',
    loading: false,
    message: '',
    messageType: '',
    toggle() { this.open = !this.open; this.message = ''; },
    async resendTicket() {
        if (!this.phone) return;
        this.loading = true;
        this.message = '';
        try {
            const res = await fetch('/help/resend-ticket', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ phone: this.phone, order_code: this.orderCode }),
            });
            const data = await res.json();
            if (res.ok) {
                this.message = data.message || 'E-Ticket berhasil dikirim ulang!';
                this.messageType = 'success';
            } else {
                this.message = data.message || 'Data tidak ditemukan.';
                this.messageType = 'error';
            }
        } catch {
            this.message = 'Terjadi kesalahan. Coba lagi.';
            this.messageType = 'error';
        } finally {
            this.loading = false;
        }
    },
}));

// Admin scanner
Alpine.data('scanner', () => ({
    scanning: false,
    result: null,
    status: null,
    async checkIn(ticketCode) {
        try {
            const res = await fetch(`/admin/checkin/${ticketCode}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
            });
            this.result = await res.json();
            this.status = res.ok ? (this.result.status || 'valid') : 'invalid';
        } catch {
            this.status = 'error';
        }
    },
    reset() {
        this.result = null;
        this.status = null;
    },
}));

// Fade up on scroll
Alpine.data('fadeObserver', () => ({
    init() {
        const els = document.querySelectorAll('.fade-up');
        if (!els.length) return;
        const observer = new IntersectionObserver(
            (entries) => entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            }),
            { threshold: 0.12 }
        );
        els.forEach(el => observer.observe(el));
    },
}));

Alpine.start();

// ─── Format currency helper ───────────────────────────────────
window.formatRupiah = (amount) => new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
}).format(amount);
