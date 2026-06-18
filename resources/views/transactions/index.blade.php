@extends('layouts.app')

@section('page-title', 'Transactions')

@section('content')
<div x-data="transactions">

    {{-- ── Page header ─────────────────────────────────────────────────────── --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900">Cash Flow</h2>
            <p class="mt-0.5 text-sm text-gray-400">Record and track all cash inflows and outflows</p>
        </div>
        <button
            @click="openAdd()"
            class="flex shrink-0 items-center gap-2 self-start rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition-all hover:bg-indigo-700 active:scale-[0.99] sm:self-auto"
        >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" aria-hidden="true">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Add Transaction
        </button>
    </div>

    {{-- ── Summary cards ───────────────────────────────────────────────────── --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Cash In</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M12 5v14M5 12l7-7 7 7"/>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-xl font-bold text-emerald-600" x-text="formatRupiah(totalIn)"></p>
            <p class="mt-0.5 text-xs text-gray-400">
                <span x-text="filtered.filter(t => t.type === 'in').length"></span> transactions
            </p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Cash Out</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-50">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M12 5v14M19 12l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-xl font-bold text-red-500" x-text="formatRupiah(totalOut)"></p>
            <p class="mt-0.5 text-xs text-gray-400">
                <span x-text="filtered.filter(t => t.type === 'out').length"></span> transactions
            </p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Net Balance</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-xl font-bold"
               :class="net >= 0 ? 'text-gray-900' : 'text-red-500'"
               x-text="formatRupiah(net)">
            </p>
            <p class="mt-0.5 text-xs text-gray-400">Cash in minus cash out</p>
        </div>

    </div>

    {{-- ── Filter bar ──────────────────────────────────────────────────────── --}}
    <div class="mb-4 rounded-xl border border-gray-100 bg-white p-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">

            {{-- Search --}}
            <div class="relative flex-1">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="text-gray-400" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                </div>
                <input
                    type="text"
                    x-model="search"
                    placeholder="Search transactions..."
                    class="form-input py-2 pl-8"
                >
            </div>

            {{-- Type --}}
            <select x-model="filterType" class="form-input py-2 sm:w-36">
                <option value="all">All Types</option>
                <option value="in">Cash In</option>
                <option value="out">Cash Out</option>
            </select>

            {{-- Category --}}
            <select x-model="filterCategory" class="form-input py-2 sm:w-48">
                <option value="all">All Categories</option>
                <template x-for="cat in categories" :key="cat">
                    <option :value="cat" x-text="cat"></option>
                </template>
            </select>

            {{-- Date range --}}
            <input type="date" x-model="filterDateFrom" class="form-input py-2 sm:w-36" title="From date">
            <input type="date" x-model="filterDateTo"   class="form-input py-2 sm:w-36" title="To date">

            {{-- Clear filters --}}
            <button
                x-show="hasActiveFilters"
                x-cloak
                @click="resetFilters()"
                class="flex shrink-0 items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-500 transition-colors hover:bg-gray-50 hover:text-gray-700"
            >
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" aria-hidden="true">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
                Clear
            </button>
        </div>
    </div>

    {{-- ── Table ───────────────────────────────────────────────────────────── --}}
    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/70">
                        <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400">Date</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400">Description</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400">Category</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400">Type</th>
                        <th class="px-5 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-400">Amount</th>
                        <th class="px-5 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">

                    {{-- Empty state --}}
                    <template x-if="filtered.length === 0">
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-gray-50">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
                                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">No transactions found</p>
                                    <p class="mt-1 text-xs text-gray-400">Try adjusting your filters or add a new transaction</p>
                                </div>
                            </td>
                        </tr>
                    </template>

                    {{-- Rows --}}
                    <template x-for="t in filtered" :key="t.id">
                        <tr class="transition-colors hover:bg-gray-50/60">

                            {{-- Date --}}
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500" x-text="formatDate(t.date)"></td>

                            {{-- Description + notes --}}
                            <td class="px-5 py-4">
                                <p class="font-medium text-gray-900" x-text="t.description"></p>
                                <p class="mt-0.5 text-xs text-gray-400" x-show="t.notes" x-text="t.notes"></p>
                            </td>

                            {{-- Category badge --}}
                            <td class="whitespace-nowrap px-5 py-4">
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                    :class="categoryStyle(t.category)"
                                    x-text="t.category"
                                ></span>
                            </td>

                            {{-- Type badge --}}
                            <td class="whitespace-nowrap px-5 py-4">
                                <span
                                    class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium"
                                    :class="t.type === 'in' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600'"
                                >
                                    <span x-text="t.type === 'in' ? '↑' : '↓'"></span>
                                    <span x-text="t.type === 'in' ? 'Cash In' : 'Cash Out'"></span>
                                </span>
                            </td>

                            {{-- Amount --}}
                            <td
                                class="whitespace-nowrap px-5 py-4 text-right font-semibold tabular-nums"
                                :class="t.type === 'in' ? 'text-emerald-600' : 'text-red-500'"
                                x-text="(t.type === 'out' ? '- ' : '+ ') + formatRupiah(t.amount)"
                            ></td>

                            {{-- Actions --}}
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <button
                                        @click="openEdit(t)"
                                        class="flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-700"
                                        title="Edit transaction"
                                    >
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </button>
                                    <button
                                        @click="confirmDelete(t.id)"
                                        class="flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500"
                                        title="Delete transaction"
                                    >
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                            <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    </template>

                </tbody>
            </table>
        </div>

        {{-- Table footer --}}
        <div class="border-t border-gray-100 bg-gray-50/40 px-5 py-3">
            <p class="text-xs text-gray-400">
                Showing <span class="font-medium text-gray-600" x-text="filtered.length"></span>
                of <span class="font-medium text-gray-600" x-text="transactions.length"></span> transactions
            </p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         Add / Edit Modal
    ═══════════════════════════════════════════════════════════════════════════ --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/25 backdrop-blur-[2px]" @click="showModal = false"></div>

        {{-- Card --}}
        <div
            class="relative w-full max-w-md rounded-2xl bg-white shadow-xl"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-[0.97]"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-[0.97]"
            @click.stop
        >
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-gray-900" x-text="isEditing ? 'Edit Transaction' : 'Add Transaction'"></h3>
                <button @click="showModal = false" class="flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600" aria-label="Close">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="space-y-4 px-6 py-5">

                {{-- Type toggle --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Type</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button
                            type="button"
                            @click="form.type = 'in'"
                            class="flex items-center justify-center gap-2 rounded-lg border px-4 py-2.5 text-sm font-medium transition-all"
                            :class="form.type === 'in'
                                ? 'border-emerald-500 bg-emerald-50 text-emerald-700'
                                : 'border-gray-200 text-gray-500 hover:bg-gray-50'"
                        >
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12l7-7 7 7"/></svg>
                            Cash In
                        </button>
                        <button
                            type="button"
                            @click="form.type = 'out'"
                            class="flex items-center justify-center gap-2 rounded-lg border px-4 py-2.5 text-sm font-medium transition-all"
                            :class="form.type === 'out'
                                ? 'border-red-400 bg-red-50 text-red-600'
                                : 'border-gray-200 text-gray-500 hover:bg-gray-50'"
                        >
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                            Cash Out
                        </button>
                    </div>
                </div>

                {{-- Date + Amount --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" x-model="form.date" class="form-input py-2">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Amount (Rp)</label>
                        <input type="number" x-model="form.amount" placeholder="0" min="0" class="form-input py-2">
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" x-model="form.description" placeholder="Brief description" class="form-input py-2">
                </div>

                {{-- Category --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Category</label>
                    <select x-model="form.category" class="form-input py-2">
                        <template x-for="cat in categories" :key="cat">
                            <option :value="cat" x-text="cat"></option>
                        </template>
                    </select>
                </div>

                {{-- Notes --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Notes
                        <span class="font-normal text-gray-400">(optional)</span>
                    </label>
                    <textarea x-model="form.notes" rows="2" placeholder="Additional notes..." class="form-input resize-none py-2"></textarea>
                </div>

            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-6 py-4">
                <button
                    type="button"
                    @click="showModal = false"
                    class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50"
                >
                    Cancel
                </button>
                <button
                    type="button"
                    @click="save()"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:bg-indigo-700 active:scale-[0.99]"
                    :class="(!form.description || !form.amount || !form.date) && 'opacity-50 pointer-events-none'"
                    x-text="isEditing ? 'Save Changes' : 'Add Transaction'"
                ></button>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         Delete Confirmation Modal
    ═══════════════════════════════════════════════════════════════════════════ --}}
    <div x-show="showDeleteConfirm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-black/25 backdrop-blur-[2px]" @click="showDeleteConfirm = false"></div>

        <div
            class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-[0.97]"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-[0.97]"
            @click.stop
        >
            <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-red-50">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                    <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">Delete transaction?</h3>
            <p class="mt-1 text-sm text-gray-400">This action cannot be undone.</p>
            <div class="mt-5 flex gap-3">
                <button
                    @click="showDeleteConfirm = false"
                    class="flex-1 rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50"
                >
                    Cancel
                </button>
                <button
                    @click="doDelete()"
                    class="flex-1 rounded-lg bg-red-500 px-4 py-2 text-sm font-semibold text-white transition-all hover:bg-red-600 active:scale-[0.99]"
                >
                    Delete
                </button>
            </div>
        </div>

    </div>

</div>
@endsection
