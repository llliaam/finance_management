@extends('layouts.app')

@section('page-title', 'Assets & Materials')

@section('header-actions')
    <button class="flex items-center gap-2 rounded-lg bg-indigo-600 px-3.5 py-2 text-sm font-semibold text-white hover:bg-indigo-700 active:scale-[0.99] transition-all">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
            <path d="M12 5v14M5 12h14"/>
        </svg>
        Add Item
    </button>
@endsection

@section('content')
<div class="flex flex-col items-center justify-center py-24 text-center">
    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 mb-4">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
        </svg>
    </div>
    <p class="text-base font-semibold text-gray-700">Assets & Materials</p>
    <p class="text-sm text-gray-400 mt-1 max-w-xs">Asset inventory, raw material costs, and change history will be built here.</p>
</div>
@endsection
