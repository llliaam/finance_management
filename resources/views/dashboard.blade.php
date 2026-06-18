@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

    <div class="rounded-xl border border-gray-100 bg-white p-5">
        <div class="flex items-center justify-between">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Cash In</p>
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/>
                </svg>
            </div>
        </div>
        <p class="mt-3 text-2xl font-bold tracking-tight text-gray-900">Rp 0</p>
        <p class="mt-1 text-xs text-gray-400">This month</p>
    </div>

    <div class="rounded-xl border border-gray-100 bg-white p-5">
        <div class="flex items-center justify-between">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Cash Out</p>
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2.25 6 9 12.75l4.306-4.306a11.95 11.95 0 0 1 5.814 5.518l2.74 1.22m0 0-5.94 2.281m5.94-2.28-2.28-5.941"/>
                </svg>
            </div>
        </div>
        <p class="mt-3 text-2xl font-bold tracking-tight text-gray-900">Rp 0</p>
        <p class="mt-1 text-xs text-gray-400">This month</p>
    </div>

    <div class="rounded-xl border border-gray-100 bg-white p-5">
        <div class="flex items-center justify-between">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Assets</p>
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                </svg>
            </div>
        </div>
        <p class="mt-3 text-2xl font-bold tracking-tight text-gray-900">0 items</p>
        <p class="mt-1 text-xs text-gray-400">Registered assets</p>
    </div>

    <div class="rounded-xl border border-gray-100 bg-white p-5">
        <div class="flex items-center justify-between">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Net Balance</p>
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>
        </div>
        <p class="mt-3 text-2xl font-bold tracking-tight text-gray-900">Rp 0</p>
        <p class="mt-1 text-xs text-gray-400">Cash in minus cash out</p>
    </div>

</div>

{{-- Chart + Recent Transactions --}}
<div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- Chart placeholder --}}
    <div class="lg:col-span-2 rounded-xl border border-gray-100 bg-white p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-sm font-semibold text-gray-900">Cash Flow Overview</h2>
                <p class="text-xs text-gray-400 mt-0.5">Income vs expenses over time</p>
            </div>
            <span class="text-xs text-gray-400 bg-gray-50 border border-gray-100 rounded-lg px-3 py-1.5">This month</span>
        </div>
        <div class="flex h-48 items-center justify-center rounded-lg border border-dashed border-gray-200 bg-gray-50">
            <div class="text-center">
                <svg class="mx-auto mb-2 text-gray-300" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                </svg>
                <p class="text-xs text-gray-400">Chart will render here</p>
            </div>
        </div>
    </div>

    {{-- Top categories placeholder --}}
    <div class="rounded-xl border border-gray-100 bg-white p-6">
        <div class="mb-6">
            <h2 class="text-sm font-semibold text-gray-900">Top Categories</h2>
            <p class="text-xs text-gray-400 mt-0.5">Spending breakdown</p>
        </div>
        <div class="space-y-3">
            @foreach (['Operating Expenses', 'Raw Materials', 'Assets', 'Other'] as $category)
            <div class="flex items-center gap-3">
                <div class="h-2 w-2 rounded-full bg-gray-200 flex-shrink-0"></div>
                <span class="flex-1 text-sm text-gray-500">{{ $category }}</span>
                <span class="text-sm font-medium text-gray-400">Rp 0</span>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Recent Transactions --}}
<div class="mt-6 rounded-xl border border-gray-100 bg-white">
    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
        <div>
            <h2 class="text-sm font-semibold text-gray-900">Recent Transactions</h2>
            <p class="text-xs text-gray-400 mt-0.5">Latest activity</p>
        </div>
        <a href="{{ route('transactions.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
            View all
        </a>
    </div>
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-50 mb-3">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/>
            </svg>
        </div>
        <p class="text-sm font-medium text-gray-500">No transactions yet</p>
        <p class="text-xs text-gray-400 mt-1">Transactions will appear here once recorded</p>
    </div>
</div>

@endsection
