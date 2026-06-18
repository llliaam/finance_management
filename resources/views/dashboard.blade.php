@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div x-data="dashboard">

    {{-- ── Contextual header (Miller's Law: orient user with date + quick actions) ──
         Reduces navigation burden by surfacing the most common actions here. --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900">Financial Overview</h2>
            <p class="mt-0.5 text-sm text-gray-400">Thursday, 18 June 2026 &mdash; June 2026</p>
        </div>
        {{-- Quick actions: most frequent tasks accessible without navigation --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('transactions.index') }}"
               class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 active:scale-[0.99]">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/></svg>
                Transactions
            </a>
            <a href="{{ route('reports.index') }}"
               class="flex items-center gap-2 rounded-lg bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white transition-all hover:bg-indigo-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 active:scale-[0.99]">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                    <path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                </svg>
                View Reports
            </a>
        </div>
    </div>

    {{-- ── Financial health alert (Visibility of system status: warn when cash is negative) --}}
    <div x-show="net < 0"
         x-cloak
         class="mb-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-100">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-amber-800">Negative cash flow this month</p>
            <p class="mt-0.5 text-xs text-amber-600">
                Cash outflows exceed inflows by <span class="font-semibold" x-text="formatRupiah(Math.abs(net))"></span>. Review your expenses.
            </p>
        </div>
    </div>

    {{-- ── Stat cards ──────────────────────────────────────────────────────────
         Chunked into 4 groups (Miller's Law). Each card answers one key question. --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Cash In</p>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12l7-7 7 7"/></svg>
                </div>
            </div>
            <p class="mt-4 text-2xl font-bold tracking-tight text-gray-900" x-text="formatRupiah(totalIn)"></p>
            <div class="mt-2 flex items-center gap-2 border-t border-gray-50 pt-2">
                <span class="flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-emerald-600">
                    <span x-text="transactions.filter(t => t.type === 'in').length"></span> transactions
                </span>
                <span class="text-xs text-gray-400">this month</span>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Cash Out</p>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-50">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                </div>
            </div>
            <p class="mt-4 text-2xl font-bold tracking-tight text-gray-900" x-text="formatRupiah(totalOut)"></p>
            <div class="mt-2 flex items-center gap-2 border-t border-gray-50 pt-2">
                <span class="flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-[11px] font-semibold text-red-500">
                    <span x-text="transactions.filter(t => t.type === 'out').length"></span> transactions
                </span>
                <span class="text-xs text-gray-400">this month</span>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Net Balance</p>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl"
                     :class="net >= 0 ? 'bg-indigo-50' : 'bg-orange-50'">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                         :stroke="net >= 0 ? '#4f46e5' : '#ea580c'"
                         stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                        <path d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-2xl font-bold tracking-tight"
               :class="net >= 0 ? 'text-gray-900' : 'text-red-500'"
               x-text="formatRupiah(net)"></p>
            <div class="mt-2 border-t border-gray-50 pt-2">
                <p class="text-xs" :class="net >= 0 ? 'text-emerald-500' : 'text-red-400'"
                   x-text="net >= 0 ? '↑ Positive cash flow' : '↓ Negative — expenses exceed income'"></p>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Assets</p>
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                        <path d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-2xl font-bold tracking-tight text-gray-900" x-text="formatRupiah(totalAssetValue)"></p>
            <div class="mt-2 border-t border-gray-50 pt-2">
                <p class="text-xs text-gray-400"><span x-text="assets.length"></span> registered assets</p>
            </div>
        </div>

    </div>

    {{-- ── Charts row ───────────────────────────────────────────────────────── --}}
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">

        {{-- Cash Flow Bar Chart --}}
        <div class="rounded-xl border border-gray-100 bg-white p-6 lg:col-span-2">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Cash Flow Overview</h3>
                    <p class="mt-0.5 text-xs text-gray-400">Daily cash in vs cash out &mdash; June 2026</p>
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-emerald-400"></span>Cash In</span>
                    <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-red-400"></span>Cash Out</span>
                </div>
            </div>
            <div class="h-56">
                <canvas x-ref="cashFlowCanvas"></canvas>
            </div>
        </div>

        {{-- Category Doughnut --}}
        <div class="rounded-xl border border-gray-100 bg-white p-6">
            <div class="mb-4">
                <h3 class="text-sm font-semibold text-gray-900">Spending by Category</h3>
                <p class="mt-0.5 text-xs text-gray-400">Cash outflow breakdown</p>
            </div>
            <div class="relative mx-auto mb-5 h-40 w-40">
                <canvas x-ref="categoryCanvas"></canvas>
                <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                    <p class="text-[11px] font-medium text-gray-400">Total Out</p>
                    <p class="text-sm font-bold text-gray-900" x-text="formatShort(totalOut)"></p>
                </div>
            </div>
            <div class="space-y-2.5">
                <template x-for="cat in categoryBreakdown" :key="cat.name">
                    <div class="flex items-center gap-2.5">
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full" :style="'background-color:' + categoryColor(cat.name)"></span>
                        <span class="flex-1 truncate text-xs text-gray-600" x-text="cat.name"></span>
                        <span class="text-xs font-semibold tabular-nums text-gray-700" x-text="formatShort(cat.value)"></span>
                        <span class="w-8 text-right text-[11px] text-gray-400" x-text="cat.pct + '%'"></span>
                    </div>
                </template>
            </div>
        </div>

    </div>

    {{-- ── Recent Transactions ──────────────────────────────────────────────── --}}
    <div class="mt-5 overflow-hidden rounded-xl border border-gray-100 bg-white">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Recent Transactions</h3>
                <p class="mt-0.5 text-xs text-gray-400">Latest 5 entries</p>
            </div>
            <a href="{{ route('transactions.index') }}"
               class="flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-medium text-indigo-600 transition-colors hover:bg-indigo-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                View all
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
            </a>
        </div>

        <div class="divide-y divide-gray-50">
            <template x-for="t in recentTx" :key="t.id">
                <div class="flex items-center gap-4 px-6 py-4 transition-colors hover:bg-gray-50/60">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl"
                         :class="t.type === 'in' ? 'bg-emerald-50' : 'bg-red-50'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                             :stroke="t.type === 'in' ? '#059669' : '#dc2626'"
                             stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <template x-if="t.type === 'in'"><path d="M12 5v14M5 12l7-7 7 7"/></template>
                            <template x-if="t.type === 'out'"><path d="M12 5v14M19 12l-7 7-7-7"/></template>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-gray-900" x-text="t.description"></p>
                        <div class="mt-0.5 flex items-center gap-2">
                            <span class="text-xs text-gray-400" x-text="formatDate(t.date)"></span>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium"
                                  :class="categoryBadgeClass(t.category)"
                                  x-text="t.category"></span>
                        </div>
                    </div>
                    <p class="shrink-0 text-sm font-semibold tabular-nums"
                       :class="t.type === 'in' ? 'text-emerald-600' : 'text-red-500'"
                       x-text="(t.type === 'out' ? '- ' : '+ ') + formatRupiah(t.amount)"></p>
                </div>
            </template>
        </div>

        <div class="border-t border-gray-100 bg-gray-50/40 px-6 py-3 text-xs text-gray-400">
            Showing <span class="font-medium text-gray-600" x-text="recentTx.length"></span> of
            <span class="font-medium text-gray-600" x-text="transactions.length"></span> transactions this month
        </div>
    </div>

</div>
@endsection
