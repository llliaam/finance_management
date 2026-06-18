import Alpine from 'alpinejs';

window.Alpine = Alpine;

// ─── Transactions page component ─────────────────────────────────────────────
Alpine.data('transactions', () => ({

    // ── UI state ──────────────────────────────────────────────────────────────
    showModal:         false,
    showDeleteConfirm: false,
    isEditing:         false,
    deletingId:        null,

    // ── Filter state ──────────────────────────────────────────────────────────
    search:         '',
    filterType:     'all',   // 'all' | 'in' | 'out'
    filterCategory: 'all',
    filterDateFrom: '',
    filterDateTo:   '',

    // ── Form state ────────────────────────────────────────────────────────────
    form: {
        id:          null,
        date:        '',
        description: '',
        category:    'Operating Expenses',
        type:        'out',
        amount:      '',
        notes:       '',
    },

    categories: ['Operating Expenses', 'Raw Materials', 'Assets', 'Other'],

    // ── Sample data (replaced by backend data later) ──────────────────────────
    transactions: [
        { id: 1, date: '2026-06-18', description: 'Client payment PT Maju Jaya',    category: 'Other',                type: 'in',  amount: 15000000, notes: 'Invoice #INV-001' },
        { id: 2, date: '2026-06-17', description: 'Monthly office supplies',          category: 'Operating Expenses',   type: 'out', amount:   350000, notes: '' },
        { id: 3, date: '2026-06-16', description: 'Steel bar raw material purchase', category: 'Raw Materials',         type: 'out', amount:  8750000, notes: '50 kg @ Rp 175,000' },
        { id: 4, date: '2026-06-15', description: 'Consulting service revenue',       category: 'Other',                type: 'in',  amount: 25000000, notes: 'PT Berkah Abadi' },
        { id: 5, date: '2026-06-14', description: 'Electricity bill — June',          category: 'Operating Expenses',   type: 'out', amount:  1250000, notes: '' },
        { id: 6, date: '2026-06-12', description: 'Operational laptop purchase',      category: 'Assets',               type: 'out', amount: 12500000, notes: 'Asus VivoBook 14' },
        { id: 7, date: '2026-06-10', description: 'Project payment PT Nusantara',    category: 'Other',                type: 'in',  amount:  8000000, notes: 'Term 2 of 3' },
    ],

    // ── Computed ──────────────────────────────────────────────────────────────
    get filtered() {
        return this.transactions.filter(t => {
            if (this.filterType !== 'all' && t.type !== this.filterType) return false;
            if (this.filterCategory !== 'all' && t.category !== this.filterCategory) return false;
            if (this.filterDateFrom && t.date < this.filterDateFrom) return false;
            if (this.filterDateTo   && t.date > this.filterDateTo)   return false;
            if (this.search) {
                const q = this.search.toLowerCase();
                if (!t.description.toLowerCase().includes(q) &&
                    !t.notes.toLowerCase().includes(q)) return false;
            }
            return true;
        });
    },

    get totalIn()  { return this.filtered.filter(t => t.type === 'in').reduce((s, t) => s + t.amount, 0); },
    get totalOut() { return this.filtered.filter(t => t.type === 'out').reduce((s, t) => s + t.amount, 0); },
    get net()      { return this.totalIn - this.totalOut; },

    // ── Helpers ───────────────────────────────────────────────────────────────
    formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency', currency: 'IDR', minimumFractionDigits: 0,
        }).format(amount);
    },

    formatDate(str) {
        if (!str) return '-';
        return new Date(str + 'T00:00:00').toLocaleDateString('id-ID', {
            day: 'numeric', month: 'short', year: 'numeric',
        });
    },

    categoryStyle(cat) {
        return {
            'Operating Expenses': 'bg-amber-50 text-amber-700',
            'Raw Materials':      'bg-purple-50 text-purple-700',
            'Assets':             'bg-blue-50 text-blue-700',
            'Other':              'bg-gray-100 text-gray-600',
        }[cat] ?? 'bg-gray-100 text-gray-600';
    },

    // ── Actions ───────────────────────────────────────────────────────────────
    openAdd() {
        this.isEditing = false;
        this.form = {
            id: null,
            date: new Date().toISOString().split('T')[0],
            description: '',
            category: 'Operating Expenses',
            type: 'out',
            amount: '',
            notes: '',
        };
        this.showModal = true;
    },

    openEdit(t) {
        this.isEditing = true;
        this.form = { ...t, amount: String(t.amount) };
        this.showModal = true;
    },

    save() {
        const entry = { ...this.form, amount: Number(this.form.amount) };
        if (this.isEditing) {
            const i = this.transactions.findIndex(t => t.id === entry.id);
            if (i !== -1) this.transactions.splice(i, 1, entry);
        } else {
            entry.id = Date.now();
            this.transactions.unshift(entry);
        }
        this.showModal = false;
    },

    confirmDelete(id) {
        this.deletingId = id;
        this.showDeleteConfirm = true;
    },

    doDelete() {
        this.transactions = this.transactions.filter(t => t.id !== this.deletingId);
        this.showDeleteConfirm = false;
        this.deletingId = null;
    },

    resetFilters() {
        this.search = '';
        this.filterType = 'all';
        this.filterCategory = 'all';
        this.filterDateFrom = '';
        this.filterDateTo = '';
    },

    get hasActiveFilters() {
        return this.search || this.filterType !== 'all' ||
               this.filterCategory !== 'all' || this.filterDateFrom || this.filterDateTo;
    },
}));

Alpine.start();
