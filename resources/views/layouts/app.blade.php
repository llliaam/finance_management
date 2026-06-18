<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('page-title', 'Dashboard') — {{ config('app.name') }}</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50" x-data="{ sidebarOpen: false }">

        {{-- ─────────────────────────────────────────────
             Mobile Overlay
        ───────────────────────────────────────────────── --}}
        <div
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black/25 backdrop-blur-[2px] lg:hidden"
        ></div>

        {{-- ─────────────────────────────────────────────
             Sidebar
        ───────────────────────────────────────────────── --}}
        <aside
            class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col bg-white border-r border-gray-100
                   transition-transform duration-200 ease-in-out
                   -translate-x-full lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : ''"
        >
            {{-- Logo --}}
            <div class="flex h-16 flex-shrink-0 items-center gap-2.5 border-b border-gray-100 px-5">
                <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-600">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                        <polyline points="2,12 5.5,7.5 8.5,9.5 13,4" stroke="white" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold tracking-tight text-gray-900">{{ config('app.name') }}</span>

                {{-- Mobile close button --}}
                <button
                    @click="sidebarOpen = false"
                    class="ml-auto flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors lg:hidden"
                    aria-label="Close sidebar"
                >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">

                {{-- Dashboard --}}
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors
                           {{ request()->routeIs('dashboard') ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                >
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                    </svg>
                    Dashboard
                </a>

                {{-- Section: Finance --}}
                <p class="px-3 pb-1 pt-4 text-[10.5px] font-medium uppercase tracking-widest text-gray-400">Finance</p>

                <a
                    href="{{ route('transactions.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors
                           {{ request()->routeIs('transactions.*') ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                >
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/>
                    </svg>
                    Transactions
                </a>

                {{-- Section: Management --}}
                <p class="px-3 pb-1 pt-4 text-[10.5px] font-medium uppercase tracking-widest text-gray-400">Management</p>

                <a
                    href="{{ route('assets.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors
                           {{ request()->routeIs('assets.*') ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                >
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                    </svg>
                    Assets & Materials
                </a>

                {{-- Section: Reporting --}}
                <p class="px-3 pb-1 pt-4 text-[10.5px] font-medium uppercase tracking-widest text-gray-400">Reporting</p>

                <a
                    href="{{ route('reports.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors
                           {{ request()->routeIs('reports.*') ? 'bg-indigo-50 font-medium text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                >
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                    </svg>
                    Financial Reports
                </a>

            </nav>

            {{-- User Profile --}}
            <div class="flex-shrink-0 border-t border-gray-100 p-3" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left transition-colors hover:bg-gray-50"
                    aria-haspopup="true"
                    :aria-expanded="open"
                >
                    <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700">
                        FS
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-gray-900">Finance Staff</p>
                        <p class="truncate text-xs text-gray-400">staff@company.com</p>
                    </div>
                    <svg
                        width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
                        class="flex-shrink-0 text-gray-400 transition-transform"
                        :class="open ? 'rotate-180' : ''"
                        aria-hidden="true"
                    >
                        <path d="m19 9-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown --}}
                <div
                    x-show="open"
                    x-cloak
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="absolute bottom-[4.5rem] left-3 right-3 rounded-xl border border-gray-100 bg-white py-1 shadow-lg shadow-gray-200/60"
                >
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 transition-colors hover:bg-red-50"
                        >
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        {{-- ─────────────────────────────────────────────
             Main Area
        ───────────────────────────────────────────────── --}}
        <div class="flex min-h-screen flex-col lg:pl-64">

            {{-- Top Header --}}
            <header class="sticky top-0 z-10 flex h-16 flex-shrink-0 items-center gap-4 border-b border-gray-100 bg-white px-6">

                {{-- Mobile hamburger --}}
                <button
                    @click="sidebarOpen = true"
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors lg:hidden"
                    aria-label="Open sidebar"
                >
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                        <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>

                {{-- Page title --}}
                <h1 class="text-sm font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>

                {{-- Right side actions --}}
                <div class="ml-auto flex items-center gap-3">
                    @yield('header-actions')
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 p-6">
                @yield('content')
            </main>

        </div>

    </body>
</html>
