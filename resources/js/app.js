import Alpine from 'alpinejs';
import { Chart, BarElement, ArcElement, CategoryScale, LinearScale, Tooltip, Legend, DoughnutController, BarController } from 'chart.js';
Chart.register(BarElement, ArcElement, CategoryScale, LinearScale, Tooltip, Legend, DoughnutController, BarController);

window.Alpine = Alpine;

// ─── Dashboard component ──────────────────────────────────────────────────────
Alpine.data('dashboard', () => ({
    cashFlowChartInstance: null,
    categoryChartInstance: null,

    transactions: [
        { id: 1, date: '2026-06-18', description: 'Client payment PT Maju Jaya',    category: 'Other',              type: 'in',  amount: 15000000 },
        { id: 2, date: '2026-06-17', description: 'Monthly office supplies',          category: 'Operating Expenses', type: 'out', amount:   350000 },
        { id: 3, date: '2026-06-16', description: 'Steel bar raw material purchase', category: 'Raw Materials',      type: 'out', amount:  8750000 },
        { id: 4, date: '2026-06-15', description: 'Consulting service revenue',       category: 'Other',              type: 'in',  amount: 25000000 },
        { id: 5, date: '2026-06-14', description: 'Electricity bill — June',          category: 'Operating Expenses', type: 'out', amount:  1250000 },
        { id: 6, date: '2026-06-12', description: 'Operational laptop purchase',      category: 'Assets',             type: 'out', amount: 12500000 },
        { id: 7, date: '2026-06-10', description: 'Project payment PT Nusantara',    category: 'Other',              type: 'in',  amount:  8000000 },
    ],
    assets: [
        { currentValue: 12000000 },
        { currentValue: 165000000 },
        { currentValue: 6000000 },
        { currentValue: 2800000 },
    ],

    get totalIn()         { return this.transactions.filter(t => t.type === 'in').reduce((s, t) => s + t.amount, 0); },
    get totalOut()        { return this.transactions.filter(t => t.type === 'out').reduce((s, t) => s + t.amount, 0); },
    get net()             { return this.totalIn - this.totalOut; },
    get totalAssetValue() { return this.assets.reduce((s, a) => s + a.currentValue, 0); },
    get recentTx()        { return [...this.transactions].sort((a, b) => b.date.localeCompare(a.date)).slice(0, 5); },

    get categoryBreakdown() {
        const map = {};
        this.transactions.filter(t => t.type === 'out').forEach(t => {
            map[t.category] = (map[t.category] || 0) + t.amount;
        });
        return Object.entries(map)
            .map(([name, value]) => ({ name, value, pct: Math.round(value / this.totalOut * 100) }))
            .sort((a, b) => b.value - a.value);
    },

    formatRupiah(n) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);
    },
    formatShort(n) {
        if (n >= 1_000_000) return 'Rp ' + (n / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'M';
        if (n >= 1_000)     return 'Rp ' + (n / 1_000).toFixed(0) + 'K';
        return 'Rp ' + n;
    },
    formatDate(str) {
        return new Date(str + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    },
    categoryColor(name) {
        return { 'Operating Expenses': '#f59e0b', 'Raw Materials': '#a855f7', 'Assets': '#6366f1', 'Other': '#6b7280' }[name] ?? '#6b7280';
    },
    categoryBadgeClass(name) {
        return { 'Operating Expenses': 'bg-amber-50 text-amber-700', 'Raw Materials': 'bg-purple-50 text-purple-700', 'Assets': 'bg-indigo-50 text-indigo-700', 'Other': 'bg-gray-100 text-gray-600' }[name] ?? 'bg-gray-100 text-gray-600';
    },

    init() {
        this.$nextTick(() => {
            this.initCashFlowChart();
            this.initCategoryChart();
        });
    },
    destroy() {
        this.cashFlowChartInstance?.destroy();
        this.categoryChartInstance?.destroy();
    },

    initCashFlowChart() {
        const canvas = this.$refs.cashFlowCanvas;
        if (!canvas) return;
        const dayMap = {};
        this.transactions.forEach(t => {
            if (!dayMap[t.date]) dayMap[t.date] = { in: 0, out: 0 };
            dayMap[t.date][t.type] += t.amount;
        });
        const sorted  = Object.keys(dayMap).sort();
        const labels  = sorted.map(d => this.formatDate(d));
        const inData  = sorted.map(d => dayMap[d].in);
        const outData = sorted.map(d => dayMap[d].out);

        this.cashFlowChartInstance = new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Cash In',  data: inData,  backgroundColor: 'rgba(16,185,129,0.8)',  borderRadius: 6, borderSkipped: false },
                    { label: 'Cash Out', data: outData, backgroundColor: 'rgba(239,68,68,0.65)',  borderRadius: 6, borderSkipped: false },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: ctx => ' ' + this.formatShort(ctx.raw) },
                        backgroundColor: '#1f2937', titleColor: '#9ca3af',
                        bodyColor: '#f9fafb', padding: 10, cornerRadius: 8,
                    },
                },
                scales: {
                    x: { grid: { display: false }, border: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 } } },
                    y: { grid: { color: 'rgba(0,0,0,0.04)' }, border: { display: false }, ticks: { color: '#9ca3af', font: { size: 11 }, callback: v => this.formatShort(v) } },
                },
            },
        });
    },

    initCategoryChart() {
        const canvas = this.$refs.categoryCanvas;
        if (!canvas) return;
        const cats = this.categoryBreakdown;
        this.categoryChartInstance = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: cats.map(c => c.name),
                datasets: [{
                    data: cats.map(c => c.value),
                    backgroundColor: cats.map(c => this.categoryColor(c.name)),
                    borderWidth: 0, hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '74%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: ctx => ' ' + this.formatShort(ctx.raw) + ' (' + Math.round(ctx.raw / this.totalOut * 100) + '%)' },
                        backgroundColor: '#1f2937', titleColor: '#9ca3af',
                        bodyColor: '#f9fafb', padding: 10, cornerRadius: 8,
                    },
                },
            },
        });
    },
}));

// ─── Transactions page component ─────────────────────────────────────────────
Alpine.data('transactions', () => ({

    // ── UI state ──────────────────────────────────────────────────────────────
    showModal:         false,
    showDeleteConfirm: false,
    isEditing:         false,
    deletingId:        null,
    toast:             { show: false, message: '', type: 'success' },

    // ── Filter state ──────────────────────────────────────────────────────────
    search:         '',
    filterType:     'all',
    filterCategory: 'all',
    filterDateFrom: '',
    filterDateTo:   '',

    // ── Form state ────────────────────────────────────────────────────────────
    form: { id: null, date: '', description: '', category: 'Operating Expenses', type: 'out', amount: '', notes: '' },
    categories: ['Operating Expenses', 'Raw Materials', 'Assets', 'Other'],

    // ── Sample data ───────────────────────────────────────────────────────────
    transactions: [
        { id: 1, date: '2026-06-18', description: 'Client payment PT Maju Jaya',    category: 'Other',              type: 'in',  amount: 15000000, notes: 'Invoice #INV-001' },
        { id: 2, date: '2026-06-17', description: 'Monthly office supplies',          category: 'Operating Expenses', type: 'out', amount:   350000, notes: '' },
        { id: 3, date: '2026-06-16', description: 'Steel bar raw material purchase', category: 'Raw Materials',      type: 'out', amount:  8750000, notes: '50 kg @ Rp 175,000' },
        { id: 4, date: '2026-06-15', description: 'Consulting service revenue',       category: 'Other',              type: 'in',  amount: 25000000, notes: 'PT Berkah Abadi' },
        { id: 5, date: '2026-06-14', description: 'Electricity bill — June',          category: 'Operating Expenses', type: 'out', amount:  1250000, notes: '' },
        { id: 6, date: '2026-06-12', description: 'Operational laptop purchase',      category: 'Assets',             type: 'out', amount: 12500000, notes: 'Asus VivoBook 14' },
        { id: 7, date: '2026-06-10', description: 'Project payment PT Nusantara',    category: 'Other',              type: 'in',  amount:  8000000, notes: 'Term 2 of 3' },
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
                if (!t.description.toLowerCase().includes(q) && !t.notes.toLowerCase().includes(q)) return false;
            }
            return true;
        });
    },
    get totalIn()  { return this.filtered.filter(t => t.type === 'in').reduce((s, t) => s + t.amount, 0); },
    get totalOut() { return this.filtered.filter(t => t.type === 'out').reduce((s, t) => s + t.amount, 0); },
    get net()      { return this.totalIn - this.totalOut; },

    // Returns active filter chips (excludes search — it has its own visible clear)
    get activeFilterChips() {
        const chips = [];
        if (this.filterType !== 'all')     chips.push({ key: 'filterType',     label: this.filterType === 'in' ? 'Cash In' : 'Cash Out' });
        if (this.filterCategory !== 'all') chips.push({ key: 'filterCategory', label: this.filterCategory });
        if (this.filterDateFrom)           chips.push({ key: 'filterDateFrom', label: 'From: ' + this.filterDateFrom });
        if (this.filterDateTo)             chips.push({ key: 'filterDateTo',   label: 'To: '   + this.filterDateTo });
        return chips;
    },
    get hasActiveFilters() {
        return this.search || this.filterType !== 'all' ||
               this.filterCategory !== 'all' || this.filterDateFrom || this.filterDateTo;
    },

    // ── Helpers ───────────────────────────────────────────────────────────────
    formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    },
    formatDate(str) {
        if (!str) return '-';
        return new Date(str + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    },
    categoryStyle(cat) {
        return { 'Operating Expenses': 'bg-amber-50 text-amber-700', 'Raw Materials': 'bg-purple-50 text-purple-700', 'Assets': 'bg-blue-50 text-blue-700', 'Other': 'bg-gray-100 text-gray-600' }[cat] ?? 'bg-gray-100 text-gray-600';
    },

    // ── Feedback ──────────────────────────────────────────────────────────────
    notify(message, type = 'success') {
        this.toast = { show: true, message, type };
        setTimeout(() => { this.toast.show = false; }, 3000);
    },

    // ── Lifecycle ─────────────────────────────────────────────────────────────
    init() {
        this._esc = e => {
            if (e.key !== 'Escape') return;
            if (this.showDeleteConfirm) { this.showDeleteConfirm = false; return; }
            if (this.showModal) { this.showModal = false; }
        };
        document.addEventListener('keydown', this._esc);
    },
    destroy() {
        document.removeEventListener('keydown', this._esc);
    },

    // ── Actions ───────────────────────────────────────────────────────────────
    openAdd() {
        this.isEditing = false;
        this.form = { id: null, date: new Date().toISOString().split('T')[0], description: '', category: 'Operating Expenses', type: 'out', amount: '', notes: '' };
        this.showModal = true;
    },
    openEdit(t) {
        this.isEditing = true;
        this.form = { ...t, amount: String(t.amount) };
        this.showModal = true;
    },
    save() {
        const editing = this.isEditing;
        const entry = { ...this.form, amount: Number(this.form.amount) };
        if (editing) {
            const i = this.transactions.findIndex(t => t.id === entry.id);
            if (i !== -1) this.transactions.splice(i, 1, entry);
        } else {
            entry.id = Date.now();
            this.transactions.unshift(entry);
        }
        this.showModal = false;
        this.notify(editing ? 'Transaction updated successfully' : 'Transaction added successfully');
    },
    confirmDelete(id) {
        this.deletingId = id;
        this.showDeleteConfirm = true;
    },
    doDelete() {
        this.transactions = this.transactions.filter(t => t.id !== this.deletingId);
        this.showDeleteConfirm = false;
        this.deletingId = null;
        this.notify('Transaction deleted', 'error');
    },
    removeFilter(key) {
        if (key === 'filterType')     this.filterType     = 'all';
        if (key === 'filterCategory') this.filterCategory = 'all';
        if (key === 'filterDateFrom') this.filterDateFrom = '';
        if (key === 'filterDateTo')   this.filterDateTo   = '';
    },
    resetFilters() {
        this.search = ''; this.filterType = 'all'; this.filterCategory = 'all';
        this.filterDateFrom = ''; this.filterDateTo = '';
    },
}));

// ─── Assets & Materials page component ───────────────────────────────────────
Alpine.data('assetsPage', () => ({

    // ── UI state ──────────────────────────────────────────────────────────────
    activeTab:         'assets',
    showAssetModal:    false,
    showMaterialModal: false,
    showDeleteConfirm: false,
    isEditing:         false,
    deletingId:        null,
    deletingType:      null,
    toast:             { show: false, message: '', type: 'success' },
    searchAssets:      '',
    searchMaterials:   '',

    // ── Forms ─────────────────────────────────────────────────────────────────
    assetForm:    { id: null, name: '', category: 'Electronics', purchaseDate: '', purchaseValue: '', currentValue: '', condition: 'Good', notes: '' },
    materialForm: { id: null, name: '', unit: 'kg', quantity: '', unitPrice: '', notes: '' },

    assetCategories: ['Electronics', 'Vehicles', 'Furniture', 'Equipment', 'Property', 'Other'],
    conditions:      ['Excellent', 'Good', 'Fair', 'Poor'],
    units:           ['kg', 'g', 'ton', 'liter', 'ml', 'm', 'cm', 'pcs', 'unit', 'box', 'roll'],

    // ── Sample data ───────────────────────────────────────────────────────────
    assets: [
        { id: 1, name: 'Asus VivoBook 14',         category: 'Electronics', purchaseDate: '2026-06-12', purchaseValue: 12500000,  currentValue: 12000000,  condition: 'Excellent', notes: 'Operational laptop' },
        { id: 2, name: 'Toyota Avanza',             category: 'Vehicles',    purchaseDate: '2023-01-15', purchaseValue: 200000000, currentValue: 165000000, condition: 'Good',      notes: 'Company vehicle' },
        { id: 3, name: 'Office Desk Set (5 units)', category: 'Furniture',   purchaseDate: '2022-03-10', purchaseValue: 8500000,   currentValue: 6000000,   condition: 'Good',      notes: '' },
        { id: 4, name: 'Server UPS 1500VA',         category: 'Equipment',   purchaseDate: '2024-05-20', purchaseValue: 3200000,   currentValue: 2800000,   condition: 'Good',      notes: 'Data center backup power' },
    ],
    materials: [
        { id: 1, name: 'Steel Bar',      unit: 'kg',  quantity: 500, unitPrice: 175000, notes: 'Grade A' },
        { id: 2, name: 'Aluminum Sheet', unit: 'kg',  quantity: 200, unitPrice: 45000,  notes: '2mm thickness' },
        { id: 3, name: 'PVC Pipe',       unit: 'm',   quantity: 150, unitPrice: 32000,  notes: '2 inch diameter' },
        { id: 4, name: 'Welding Rod',    unit: 'box', quantity: 30,  unitPrice: 125000, notes: 'E6013 type' },
    ],

    // ── Computed ──────────────────────────────────────────────────────────────
    get filteredAssets() {
        if (!this.searchAssets) return this.assets;
        const q = this.searchAssets.toLowerCase();
        return this.assets.filter(a =>
            a.name.toLowerCase().includes(q) || a.category.toLowerCase().includes(q) || a.notes.toLowerCase().includes(q)
        );
    },
    get filteredMaterials() {
        if (!this.searchMaterials) return this.materials;
        const q = this.searchMaterials.toLowerCase();
        return this.materials.filter(m => m.name.toLowerCase().includes(q) || m.notes.toLowerCase().includes(q));
    },
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

    // ── Feedback ──────────────────────────────────────────────────────────────
    notify(message, type = 'success') {
        this.toast = { show: true, message, type };
        setTimeout(() => { this.toast.show = false; }, 3000);
    },

    // ── Lifecycle ─────────────────────────────────────────────────────────────
    init() {
        this._esc = e => {
            if (e.key !== 'Escape') return;
            if (this.showDeleteConfirm) { this.showDeleteConfirm = false; return; }
            if (this.showAssetModal)    { this.showAssetModal = false; return; }
            if (this.showMaterialModal) { this.showMaterialModal = false; }
        };
        document.addEventListener('keydown', this._esc);
    },
    destroy() {
        document.removeEventListener('keydown', this._esc);
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
        const editing = this.isEditing;
        const entry = { ...this.assetForm, purchaseValue: Number(this.assetForm.purchaseValue), currentValue: Number(this.assetForm.currentValue) };
        if (editing) {
            const i = this.assets.findIndex(a => a.id === entry.id);
            if (i !== -1) this.assets.splice(i, 1, entry);
        } else {
            entry.id = Date.now();
            this.assets.unshift(entry);
        }
        this.showAssetModal = false;
        this.notify(editing ? 'Asset updated successfully' : 'Asset added successfully');
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
        const editing = this.isEditing;
        const entry = { ...this.materialForm, quantity: Number(this.materialForm.quantity), unitPrice: Number(this.materialForm.unitPrice) };
        if (editing) {
            const i = this.materials.findIndex(m => m.id === entry.id);
            if (i !== -1) this.materials.splice(i, 1, entry);
        } else {
            entry.id = Date.now();
            this.materials.unshift(entry);
        }
        this.showMaterialModal = false;
        this.notify(editing ? 'Material updated successfully' : 'Material added successfully');
    },

    // ── Delete ────────────────────────────────────────────────────────────────
    confirmDelete(id, type) {
        this.deletingId   = id;
        this.deletingType = type;
        this.showDeleteConfirm = true;
    },
    doDelete() {
        const type = this.deletingType;
        if (type === 'asset')    this.assets    = this.assets.filter(a => a.id !== this.deletingId);
        if (type === 'material') this.materials = this.materials.filter(m => m.id !== this.deletingId);
        this.showDeleteConfirm = false;
        this.deletingId = null;
        this.notify((type === 'asset' ? 'Asset' : 'Material') + ' deleted', 'error');
    },
}));

// ─── Financial Reports page component ────────────────────────────────────────
Alpine.data('reportsPage', () => ({
    reportType:   'cashflow',
    period:       'this_month',
    customFrom:   '',
    customTo:     '',
    showToast:    false,
    toastMessage: '',

    allTransactions: [
        { id: 1, date: '2026-06-18', description: 'Client payment PT Maju Jaya',    category: 'Other',              type: 'in',  amount: 15000000, notes: 'Invoice #INV-001' },
        { id: 2, date: '2026-06-17', description: 'Monthly office supplies',          category: 'Operating Expenses', type: 'out', amount:   350000, notes: '' },
        { id: 3, date: '2026-06-16', description: 'Steel bar raw material purchase', category: 'Raw Materials',      type: 'out', amount:  8750000, notes: '50 kg @ Rp 175,000' },
        { id: 4, date: '2026-06-15', description: 'Consulting service revenue',       category: 'Other',              type: 'in',  amount: 25000000, notes: 'PT Berkah Abadi' },
        { id: 5, date: '2026-06-14', description: 'Electricity bill — June',          category: 'Operating Expenses', type: 'out', amount:  1250000, notes: '' },
        { id: 6, date: '2026-06-12', description: 'Operational laptop purchase',      category: 'Assets',             type: 'out', amount: 12500000, notes: 'Asus VivoBook 14' },
        { id: 7, date: '2026-06-10', description: 'Project payment PT Nusantara',    category: 'Other',              type: 'in',  amount:  8000000, notes: 'Term 2 of 3' },
    ],

    get dateRange() {
        const today = new Date('2026-06-18');
        const y = today.getFullYear(), m = today.getMonth();
        if (this.period === 'this_month')  return { from: `${y}-${String(m+1).padStart(2,'0')}-01`, to: `${y}-${String(m+1).padStart(2,'0')}-31` };
        if (this.period === 'last_month')  { const d = new Date(y, m-1, 1); return { from: `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-01`, to: `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-31` }; }
        if (this.period === 'this_quarter') { const q = Math.floor(m/3), f = new Date(y,q*3,1), t2 = new Date(y,q*3+3,0); return { from: f.toISOString().split('T')[0], to: t2.toISOString().split('T')[0] }; }
        return { from: this.customFrom, to: this.customTo };
    },
    get periodLabel() {
        const { from, to } = this.dateRange;
        if (!from) return 'All periods';
        if (this.period === 'this_month' || this.period === 'last_month')
            return new Date(from + 'T00:00:00').toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
        const fmt = s => new Date(s + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        return `${fmt(from)} – ${fmt(to)}`;
    },
    get filtered() {
        const { from, to } = this.dateRange;
        return this.allTransactions.filter(t => {
            if (from && t.date < from) return false;
            if (to   && t.date > to)   return false;
            return true;
        });
    },
    get totalIn()  { return this.filtered.filter(t => t.type === 'in').reduce((s, t) => s + t.amount, 0); },
    get totalOut() { return this.filtered.filter(t => t.type === 'out').reduce((s, t) => s + t.amount, 0); },
    get net()      { return this.totalIn - this.totalOut; },
    get inByCategory() {
        const map = {};
        this.filtered.filter(t => t.type === 'in').forEach(t => {
            if (!map[t.category]) map[t.category] = { category: t.category, items: [], total: 0 };
            map[t.category].items.push(t); map[t.category].total += t.amount;
        });
        return Object.values(map);
    },
    get outByCategory() {
        const map = {};
        this.filtered.filter(t => t.type === 'out').forEach(t => {
            if (!map[t.category]) map[t.category] = { category: t.category, items: [], total: 0 };
            map[t.category].items.push(t); map[t.category].total += t.amount;
        });
        return Object.values(map);
    },
    get recapByCategory() {
        const map = {};
        this.filtered.forEach(t => {
            if (!map[t.category]) map[t.category] = { category: t.category, items: [], totalIn: 0, totalOut: 0 };
            map[t.category].items.push(t);
            if (t.type === 'in')  map[t.category].totalIn  += t.amount;
            if (t.type === 'out') map[t.category].totalOut += t.amount;
        });
        return Object.values(map);
    },
    formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    },
    formatDate(str) {
        if (!str) return '-';
        return new Date(str + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    },
    printReport() { window.print(); },
    exportNotice(format) {
        this.toastMessage = `Export to ${format} will be available after backend integration.`;
        this.showToast = true;
        setTimeout(() => { this.showToast = false; }, 3500);
    },
}));

Alpine.start();
