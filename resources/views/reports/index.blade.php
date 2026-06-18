@extends('layouts.app')

@section('page-title', 'Financial Reports')

@section('content')

{{-- Print-only styles: hide everything except the report document --}}
<style>
    @media print {
        body * { visibility: hidden; }
        #report-print-area, #report-print-area * { visibility: visible; }
        #report-print-area {
            position: fixed; inset: 0;
            padding: 32px 48px;
            background: white;
        }
    }
</style>

<div x-data="reportsPage">

    {{-- ── Toast notification ──────────────────────────────────────────────── --}}
    <div
        x-show="showToast"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-6 left-1/2 z-50 -translate-x-1/2 rounded-xl bg-gray-900 px-5 py-3 text-sm text-white shadow-lg"
        x-text="toastMessage"
    ></div>

    {{-- ── Page header ─────────────────────────────────────────────────────── --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900">Financial Reports</h2>
            <p class="mt-0.5 text-sm text-gray-400">Generate, preview, and export financial statements</p>
        </div>
        <div class="flex shrink-0 items-center gap-2">
            <button
                @click="exportNotice('Excel')"
                class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50 active:scale-[0.99]"
            >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                    <path d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Excel
            </button>
            <button
                @click="exportNotice('PDF')"
                class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50 active:scale-[0.99]"
            >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                    <path d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                </svg>
                PDF
            </button>
            <button
                @click="printReport()"
                class="flex items-center gap-2 rounded-lg bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white transition-all hover:bg-indigo-700 active:scale-[0.99]"
            >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                    <path d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z"/>
                </svg>
                Print
            </button>
        </div>
    </div>

    {{-- ── Controls ─────────────────────────────────────────────────────────── --}}
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">

        {{-- Report type toggle --}}
        <div class="flex gap-1 rounded-xl border border-gray-100 bg-white p-1">
            <button
                @click="reportType = 'cashflow'"
                class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-all"
                :class="reportType === 'cashflow' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
            >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                    <path d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/>
                </svg>
                Cash Flow Statement
            </button>
            <button
                @click="reportType = 'recap'"
                class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-all"
                :class="reportType === 'recap' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
            >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                    <path d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                </svg>
                Transaction Recap
            </button>
        </div>

        {{-- Period selector --}}
        <div class="flex flex-wrap items-center gap-2 sm:ml-auto">
            <template x-for="opt in [
                { value: 'this_month',   label: 'This Month' },
                { value: 'last_month',   label: 'Last Month' },
                { value: 'this_quarter', label: 'This Quarter' },
                { value: 'custom',       label: 'Custom' },
            ]" :key="opt.value">
                <button
                    @click="period = opt.value"
                    class="rounded-lg border px-3 py-1.5 text-xs font-medium transition-all"
                    :class="period === opt.value
                        ? 'border-indigo-200 bg-indigo-50 text-indigo-700'
                        : 'border-gray-200 bg-white text-gray-500 hover:bg-gray-50'"
                    x-text="opt.label"
                ></button>
            </template>

            {{-- Custom date inputs --}}
            <template x-if="period === 'custom'">
                <div class="flex items-center gap-2">
                    <input type="date" x-model="customFrom" class="form-input py-1.5 text-xs sm:w-32">
                    <span class="text-xs text-gray-400">to</span>
                    <input type="date" x-model="customTo"   class="form-input py-1.5 text-xs sm:w-32">
                </div>
            </template>
        </div>

    </div>

    {{-- ════════════════════════════════════════════════════════════════════════
         REPORT DOCUMENT
    ════════════════════════════════════════════════════════════════════════════ --}}
    <div id="report-print-area" class="rounded-2xl border border-gray-100 bg-white">

        {{-- Document header --}}
        <div class="border-b border-gray-100 px-8 py-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-widest text-indigo-500">Finance Management</p>
                    <h1 class="mt-1 text-xl font-bold text-gray-900"
                        x-text="reportType === 'cashflow' ? 'Cash Flow Statement' : 'Transaction Recap'"></h1>
                    <p class="mt-1 text-sm text-gray-400">Period: <span class="font-medium text-gray-600" x-text="periodLabel"></span></p>
                </div>
                <div class="hidden text-right sm:block">
                    <p class="text-xs text-gray-400">Generated on</p>
                    <p class="text-sm font-medium text-gray-700">18 Juni 2026</p>
                </div>
            </div>
        </div>

        {{-- ── Empty state ──────────────────────────────────────────────────── --}}
        <template x-if="filtered.length === 0">
            <div class="px-8 py-20 text-center">
                <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-gray-50">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
                        <path d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-500">No transactions in this period</p>
                <p class="mt-1 text-xs text-gray-400">Try selecting a different date range</p>
            </div>
        </template>

        {{-- ════════════════════════════════════════════════════════════════
             CASH FLOW STATEMENT
        ════════════════════════════════════════════════════════════════════ --}}
        <div x-show="reportType === 'cashflow' && filtered.length > 0" class="px-8 py-6">

            {{-- Summary row --}}
            <div class="mb-8 grid grid-cols-3 gap-4">
                <div class="rounded-xl bg-emerald-50 px-5 py-4 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-emerald-600">Total Cash In</p>
                    <p class="mt-2 text-lg font-bold text-emerald-700" x-text="formatRupiah(totalIn)"></p>
                </div>
                <div class="rounded-xl bg-red-50 px-5 py-4 text-center">
                    <p class="text-xs font-medium uppercase tracking-wider text-red-500">Total Cash Out</p>
                    <p class="mt-2 text-lg font-bold text-red-600" x-text="formatRupiah(totalOut)"></p>
                </div>
                <div class="rounded-xl px-5 py-4 text-center"
                     :class="net >= 0 ? 'bg-indigo-50' : 'bg-orange-50'">
                    <p class="text-xs font-medium uppercase tracking-wider"
                       :class="net >= 0 ? 'text-indigo-600' : 'text-orange-600'">Net Cash Flow</p>
                    <p class="mt-2 text-lg font-bold"
                       :class="net >= 0 ? 'text-indigo-700' : 'text-orange-700'"
                       x-text="(net < 0 ? '- ' : '') + formatRupiah(Math.abs(net))"></p>
                </div>
            </div>

            {{-- CASH INFLOWS section --}}
            <div class="mb-6">
                <div class="mb-3 flex items-center gap-3">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12l7-7 7 7"/></svg>
                    </span>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Cash Inflows</h3>
                </div>

                <template x-if="inByCategory.length === 0">
                    <p class="py-3 pl-8 text-sm text-gray-400 italic">No cash inflows in this period</p>
                </template>

                <template x-for="group in inByCategory" :key="group.category">
                    <div class="mb-1">
                        <div class="flex items-center justify-between border-b border-dashed border-gray-100 py-2.5 pl-2 pr-0">
                            <span class="text-sm font-medium text-gray-700" x-text="group.category"></span>
                            <span class="text-sm font-semibold text-emerald-600 tabular-nums" x-text="formatRupiah(group.total)"></span>
                        </div>
                        <template x-for="t in group.items" :key="t.id">
                            <div class="flex items-center justify-between py-1.5 pl-6 pr-0">
                                <div class="flex items-center gap-3">
                                    <span class="text-xs text-gray-400" x-text="formatDate(t.date)"></span>
                                    <span class="text-xs text-gray-600" x-text="t.description"></span>
                                </div>
                                <span class="text-xs text-emerald-500 tabular-nums" x-text="formatRupiah(t.amount)"></span>
                            </div>
                        </template>
                    </div>
                </template>

                <div class="mt-2 flex items-center justify-between rounded-lg bg-emerald-50/60 px-4 py-2.5">
                    <span class="text-sm font-semibold text-gray-700">Total Cash Inflows</span>
                    <span class="text-sm font-bold text-emerald-700 tabular-nums" x-text="formatRupiah(totalIn)"></span>
                </div>
            </div>

            {{-- CASH OUTFLOWS section --}}
            <div class="mb-6">
                <div class="mb-3 flex items-center gap-3">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-red-100">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
                    </span>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500">Cash Outflows</h3>
                </div>

                <template x-if="outByCategory.length === 0">
                    <p class="py-3 pl-8 text-sm text-gray-400 italic">No cash outflows in this period</p>
                </template>

                <template x-for="group in outByCategory" :key="group.category">
                    <div class="mb-1">
                        <div class="flex items-center justify-between border-b border-dashed border-gray-100 py-2.5 pl-2 pr-0">
                            <span class="text-sm font-medium text-gray-700" x-text="group.category"></span>
                            <span class="text-sm font-semibold text-red-500 tabular-nums" x-text="formatRupiah(group.total)"></span>
                        </div>
                        <template x-for="t in group.items" :key="t.id">
                            <div class="flex items-center justify-between py-1.5 pl-6 pr-0">
                                <div class="flex items-center gap-3">
                                    <span class="text-xs text-gray-400" x-text="formatDate(t.date)"></span>
                                    <span class="text-xs text-gray-600" x-text="t.description"></span>
                                </div>
                                <span class="text-xs text-red-400 tabular-nums" x-text="formatRupiah(t.amount)"></span>
                            </div>
                        </template>
                    </div>
                </template>

                <div class="mt-2 flex items-center justify-between rounded-lg bg-red-50/60 px-4 py-2.5">
                    <span class="text-sm font-semibold text-gray-700">Total Cash Outflows</span>
                    <span class="text-sm font-bold text-red-600 tabular-nums" x-text="formatRupiah(totalOut)"></span>
                </div>
            </div>

            {{-- NET CASH FLOW --}}
            <div class="rounded-xl border-2 px-6 py-4"
                 :class="net >= 0 ? 'border-indigo-200 bg-indigo-50' : 'border-orange-200 bg-orange-50'">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider"
                           :class="net >= 0 ? 'text-indigo-500' : 'text-orange-500'">Net Cash Flow</p>
                        <p class="mt-0.5 text-xs"
                           :class="net >= 0 ? 'text-indigo-400' : 'text-orange-400'"
                           x-text="net >= 0 ? 'Positive cash position' : 'Negative cash position'"></p>
                    </div>
                    <p class="text-xl font-bold tabular-nums"
                       :class="net >= 0 ? 'text-indigo-700' : 'text-orange-700'"
                       x-text="(net < 0 ? '- ' : '+ ') + formatRupiah(Math.abs(net))"></p>
                </div>
            </div>

        </div>

        {{-- ════════════════════════════════════════════════════════════════
             TRANSACTION RECAP
        ════════════════════════════════════════════════════════════════════ --}}
        <div x-show="reportType === 'recap' && filtered.length > 0" x-cloak class="px-8 py-6">

            {{-- Transaction count --}}
            <p class="mb-5 text-xs text-gray-400">
                <span class="font-semibold text-gray-700" x-text="filtered.length"></span> transactions found in this period
            </p>

            <template x-for="group in recapByCategory" :key="group.category">
                <div class="mb-6">
                    {{-- Category header --}}
                    <div class="mb-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="h-2 w-2 rounded-full bg-indigo-400"></div>
                            <h3 class="text-sm font-semibold text-gray-800" x-text="group.category"></h3>
                            <span class="rounded-full bg-gray-100 px-1.5 py-0.5 text-[10px] font-medium text-gray-500"
                                  x-text="group.items.length + ' txn'"></span>
                        </div>
                    </div>

                    {{-- Transactions table --}}
                    <div class="overflow-hidden rounded-xl border border-gray-100">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50/60">
                                    <th class="px-4 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400">Date</th>
                                    <th class="px-4 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400">Description</th>
                                    <th class="px-4 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-400">Type</th>
                                    <th class="px-4 py-2.5 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-400">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <template x-for="t in group.items" :key="t.id">
                                    <tr>
                                        <td class="whitespace-nowrap px-4 py-2.5 text-xs text-gray-500" x-text="formatDate(t.date)"></td>
                                        <td class="px-4 py-2.5">
                                            <p class="text-xs font-medium text-gray-800" x-text="t.description"></p>
                                            <p class="text-[11px] text-gray-400" x-show="t.notes" x-text="t.notes"></p>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-2.5 text-center">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium"
                                                  :class="t.type === 'in' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600'"
                                                  x-text="t.type === 'in' ? 'Cash In' : 'Cash Out'"></span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-2.5 text-right text-xs font-semibold tabular-nums"
                                            :class="t.type === 'in' ? 'text-emerald-600' : 'text-red-500'"
                                            x-text="(t.type === 'out' ? '- ' : '+ ') + formatRupiah(t.amount)"></td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot>
                                <tr class="border-t border-gray-200 bg-gray-50/60">
                                    <td colspan="3" class="px-4 py-2.5 text-xs font-semibold text-gray-600">Category subtotal</td>
                                    <td class="px-4 py-2.5 text-right text-xs tabular-nums">
                                        <span x-show="group.totalIn > 0"  class="block font-semibold text-emerald-600" x-text="'+ ' + formatRupiah(group.totalIn)"></span>
                                        <span x-show="group.totalOut > 0" class="block font-semibold text-red-500"    x-text="'- ' + formatRupiah(group.totalOut)"></span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </template>

            {{-- Grand total --}}
            <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 px-6 py-4">
                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Grand Total</p>
                <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                    <span class="text-sm text-gray-600">Total Cash In</span>
                    <span class="text-sm font-semibold text-emerald-600 tabular-nums" x-text="formatRupiah(totalIn)"></span>
                </div>
                <div class="flex items-center justify-between border-b border-gray-200 py-2">
                    <span class="text-sm text-gray-600">Total Cash Out</span>
                    <span class="text-sm font-semibold text-red-500 tabular-nums" x-text="formatRupiah(totalOut)"></span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-sm font-bold text-gray-900">Net</span>
                    <span class="text-sm font-bold tabular-nums"
                          :class="net >= 0 ? 'text-indigo-700' : 'text-orange-600'"
                          x-text="(net < 0 ? '- ' : '') + formatRupiah(Math.abs(net))"></span>
                </div>
            </div>

        </div>

        {{-- Document footer --}}
        <div class="flex items-center justify-between border-t border-gray-100 px-8 py-4">
            <p class="text-xs text-gray-400">Finance Management System &mdash; Auto-generated report</p>
            <p class="text-xs text-gray-400" x-text="'Period: ' + periodLabel"></p>
        </div>

    </div>

</div>
@endsection
