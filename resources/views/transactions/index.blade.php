@extends('layouts.app')

@section('page-title', 'Transactions')

@section('header-actions')
    <button class="flex items-center gap-2 rounded-lg bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white hover:bg-indigo-700 active:scale-[0.99] transition-all">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
            <path d="M12 5v14M5 12h14"/>
        </svg>
        Add Transaction
    </button>
@endsection

@section('content')
<div class="flex flex-col items-center justify-center py-24 text-center">
    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 mb-4">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/>
        </svg>
    </div>
    <p class="text-base font-semibold text-gray-700">Transactions</p>
    <p class="text-sm text-gray-400 mt-1 max-w-xs">This module is coming next. Cash inflows, outflows, categories and filters will be built here.</p>
</div>
@endsection
