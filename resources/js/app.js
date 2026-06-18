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

// ─── Assets & Materials page component ───────────────────────────────────────
Alpine.data('assetsPage', () => ({

    // ── UI state ──────────────────────────────────────────────────────────────
    activeTab:         'assets',  // 'assets' | 'materials'
    showAssetModal:    false,
    showMaterialModal: false,
    showDeleteConfirm: false,
    isEditing:         false,
    deletingId:        null,
    deletingType:      null,      // 'asset' | 'material'

    // ── Forms ─────────────────────────────────────────────────────────────────
    assetForm: {
        id: null, name: '', category: 'Electronics',
        purchaseDate: '', purchaseValue: '', currentValue: '',
        condition: 'Good', notes: '',
    },
    materialForm: {
        id: null, name: '', unit: 'kg',
        quantity: '', unitPrice: '', notes: '',
    },

    assetCategories: ['Electronics', 'Vehicles', 'Furniture', 'Equipment', 'Property', 'Other'],
    conditions:      ['Excellent', 'Good', 'Fair', 'Poor'],
    units:           ['kg', 'g', 'ton', 'liter', 'ml', 'm', 'cm', 'pcs', 'unit', 'box', 'roll'],

    // ── Sample data ───────────────────────────────────────────────────────────
    assets: [
        { id: 1, name: 'Asus VivoBook 14',        category: 'Electronics', purchaseDate: '2026-06-12', purchaseValue: 12500000,  currentValue: 12000000,  condition: 'Excellent', notes: 'Operational laptop' },
        { id: 2, name: 'Toyota Avanza',            category: 'Vehicles',    purchaseDate: '2023-01-15', purchaseValue: 200000000, currentValue: 165000000, condition: 'Good',      notes: 'Company vehicle' },
        { id: 3, name: 'Office Desk Set (5 units)', category: 'Furniture',  purchaseDate: '2022-03-10', purchaseValue: 8500000,   currentValue: 6000000,   condition: 'Good',      notes: '' },
        { id: 4, name: 'Server UPS 1500VA',        category: 'Equipment',   purchaseDate: '2024-05-20', purchaseValue: 3200000,   currentValue: 2800000,   condition: 'Good',      notes: 'Data center backup power' },
    ],
    materials: [
        { id: 1, name: 'Steel Bar',      unit: 'kg',  quantity: 500, unitPrice: 175000, notes: 'Grade A' },
        { id: 2, name: 'Aluminum Sheet', unit: 'kg',  quantity: 200, unitPrice: 45000,  notes: '2mm thickness' },
        { id: 3, name: 'PVC Pipe',       unit: 'm',   quantity: 150, unitPrice: 32000,  notes: '2 inch diameter' },
        { id: 4, name: 'Welding Rod',    unit: 'box', quantity: 30,  unitPrice: 125000, notes: 'E6013 type' },
    ],

    // ── Computed ──────────────────────────────────────────────────────────────
    get totalAssetValue()    { return this.assets.reduce((s, a) => s + a.currentValue, 0); },
    get totalMaterialValue() { return this.materials.reduce((s, m) => s + m.quantity * m.unitPrice, 0); },
    get totalNetworth()      { return this.totalAssetValue + this.totalMaterialValue; },

    // ── Helpers ───────────────────────────────────────────────────────────────
    formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    },
    formatDate(str) {
        if (!str) return '-';
        return new Date(str + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    },
    conditionStyle(c) {
        return { Excellent: 'bg-emerald-50 text-emerald-700', Good: 'bg-blue-50 text-blue-700', Fair: 'bg-amber-50 text-amber-700', Poor: 'bg-red-50 text-red-600' }[c] ?? 'bg-gray-100 text-gray-600';
    },
    categoryStyle(c) {
        return { Electronics: 'bg-indigo-50 text-indigo-700', Vehicles: 'bg-cyan-50 text-cyan-700', Furniture: 'bg-amber-50 text-amber-700', Equipment: 'bg-purple-50 text-purple-700', Property: 'bg-emerald-50 text-emerald-700', Other: 'bg-gray-100 text-gray-600' }[c] ?? 'bg-gray-100 text-gray-600';
    },
    depreciation(a) {
        if (!a.purchaseValue) return 0;
        return Math.round((1 - a.currentValue / a.purchaseValue) * 100);
    },

    // ── Asset actions ─────────────────────────────────────────────────────────
    openAddAsset() {
        this.isEditing = false;
        this.assetForm = { id: null, name: '', category: 'Electronics', purchaseDate: new Date().toISOString().split('T')[0], purchaseValue: '', currentValue: '', condition: 'Good', notes: '' };
        this.showAssetModal = true;
    },
    openEditAsset(a) {
        this.isEditing = true;
        this.assetForm = { ...a, purchaseValue: String(a.purchaseValue), currentValue: String(a.currentValue) };
        this.showAssetModal = true;
    },
    saveAsset() {
        const entry = { ...this.assetForm, purchaseValue: Number(this.assetForm.purchaseValue), currentValue: Number(this.assetForm.currentValue) };
        if (this.isEditing) {
            const i = this.assets.findIndex(a => a.id === entry.id);
            if (i !== -1) this.assets.splice(i, 1, entry);
        } else {
            entry.id = Date.now();
            this.assets.unshift(entry);
        }
        this.showAssetModal = false;
    },

    // ── Material actions ──────────────────────────────────────────────────────
    openAddMaterial() {
        this.isEditing = false;
        this.materialForm = { id: null, name: '', unit: 'kg', quantity: '', unitPrice: '', notes: '' };
        this.showMaterialModal = true;
    },
    openEditMaterial(m) {
        this.isEditing = true;
        this.materialForm = { ...m, quantity: String(m.quantity), unitPrice: String(m.unitPrice) };
        this.showMaterialModal = true;
    },
    saveMaterial() {
        const entry = { ...this.materialForm, quantity: Number(this.materialForm.quantity), unitPrice: Number(this.materialForm.unitPrice) };
        if (this.isEditing) {
            const i = this.materials.findIndex(m => m.id === entry.id);
            if (i !== -1) this.materials.splice(i, 1, entry);
        } else {
            entry.id = Date.now();
            this.materials.unshift(entry);
        }
        this.showMaterialModal = false;
    },

    // ── Delete ────────────────────────────────────────────────────────────────
    confirmDelete(id, type) {
        this.deletingId   = id;
        this.deletingType = type;
        this.showDeleteConfirm = true;
    },
    doDelete() {
        if (this.deletingType === 'asset')    this.assets    = this.assets.filter(a => a.id !== this.deletingId);
        if (this.deletingType === 'material') this.materials = this.materials.filter(m => m.id !== this.deletingId);
        this.showDeleteConfirm = false;
        this.deletingId = null;
    },
}));

Alpine.start();
