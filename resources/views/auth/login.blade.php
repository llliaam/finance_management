@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="min-h-screen flex">

    {{-- ───────────────────────────────────────────────
         Left: Brand Panel (desktop only)
    ─────────────────────────────────────────────────── --}}
    <div class="hidden lg:flex lg:w-[44%] auth-brand-panel flex-col justify-between p-12 overflow-hidden">

        {{-- Logo --}}
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <polyline points="2,12 5.5,7.5 8.5,9.5 13,4" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <span class="text-white font-semibold text-sm tracking-tight">{{ config('app.name') }}</span>
        </div>

        {{-- Tagline --}}
        <div>
            <h1 class="text-white text-[2rem] font-bold tracking-tight leading-[1.2]">
                Manage your company<br>finances with clarity.
            </h1>
            <p class="text-indigo-200/60 mt-4 text-sm leading-relaxed max-w-[17rem]">
                Track cash flow, monitor assets, and generate financial reports in one place.
            </p>

            <div class="flex flex-wrap gap-2 mt-8">
                <span class="text-xs text-indigo-200/50 bg-white/5 border border-white/10 rounded-full px-3 py-1">Cash Flow</span>
                <span class="text-xs text-indigo-200/50 bg-white/5 border border-white/10 rounded-full px-3 py-1">Asset Tracking</span>
                <span class="text-xs text-indigo-200/50 bg-white/5 border border-white/10 rounded-full px-3 py-1">Financial Reports</span>
            </div>
        </div>

        {{-- Copyright --}}
        <p class="text-indigo-300/30 text-xs">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>

    {{-- ───────────────────────────────────────────────
         Right: Form Panel
    ─────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col items-center justify-center px-6 py-12 bg-white">

        {{-- Mobile logo --}}
        <div class="lg:hidden mb-10 flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <polyline points="2,12 5.5,7.5 8.5,9.5 13,4" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <span class="text-gray-900 font-semibold text-sm tracking-tight">{{ config('app.name') }}</span>
        </div>

        <div class="w-full max-w-[22rem]">

            {{-- Heading --}}
            <div class="mb-7">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Welcome back</h2>
                <p class="text-gray-400 mt-1 text-sm">Sign in to your account to continue</p>
            </div>

            {{-- Session / General Error --}}
            @if (session('error'))
                <div class="mb-5 flex items-start gap-2.5 bg-red-50 border border-red-100 text-red-600 text-sm rounded-lg px-4 py-3">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="/login" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        placeholder="you@company.com"
                        class="form-input @error('email') border-red-400 focus:border-red-400 @enderror"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div x-data="{ show: false }">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input
                            :type="show ? 'text' : 'password'"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            required
                            placeholder="••••••••"
                            class="form-input pr-10 @error('password') border-red-400 @enderror"
                        >
                        {{-- Show / hide toggle --}}
                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors"
                            :aria-label="show ? 'Hide password' : 'Show password'"
                        >
                            {{-- Eye open --}}
                            <svg x-show="!show" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            {{-- Eye crossed --}}
                            <svg x-show="show" x-cloak width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500/30 cursor-pointer"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-600 cursor-pointer select-none">
                        Remember me for 30 days
                    </label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition cursor-pointer"
                >
                    Sign In
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
