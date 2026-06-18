@extends('layouts.app')

@section('page-title', 'Assets & Materials')

@section('content')
<div x-data="assetsPage">

    {{-- ── Page header ─────────────────────────────────────────────────────── --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-900">Assets & Raw Materials</h2>
            <p class="mt-0.5 text-sm text-gray-400">Track company assets and raw material inventory</p>
        </div>
        <div class="flex shrink-0 items-center gap-2">
            <button
                x-show="activeTab === 'assets'"
                @click="openAddAsset()"
                class="flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition-all hover:bg-indigo-700 active:scale-[0.99]"
            >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Add Asset
            </button>
            <button
                x-show="activeTab === 'materials'"
                x-cloak
                @click="openAddMaterial()"
                class="flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition-all hover:bg-indigo-700 active:scale-[0.99]"
            >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Add Material
            </button>
        </div>
    </div>

    {{-- ── Summary cards ───────────────────────────────────────────────────── --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Asset Value</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                        <path d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-xl font-bold text-gray-900" x-text="formatRupiah(totalAssetValue)"></p>
            <p class="mt-0.5 text-xs text-gray-400"><span x-text="assets.length"></span> assets recorded</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Raw Material Value</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-50">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                        <path d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-xl font-bold text-gray-900" x-text="formatRupiah(totalMaterialValue)"></p>
            <p class="mt-0.5 text-xs text-gray-400"><span x-text="materials.length"></span> material types</p>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium uppercase tracking-wider text-gray-400">Total Net Worth</p>
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                        <path d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-xl font-bold text-emerald-600" x-text="formatRupiah(totalNetworth)"></p>
            <p class="mt-0.5 text-xs text-gray-400">Assets + raw materials</p>
        </div>

    </div>

    {{-- ── Tab navigation ──────────────────────────────────────────────────── --}}
    <div class="mb-4 flex gap-1 rounded-xl border border-gray-100 bg-white p-1 sm:w-fit">
        <button
            @click="activeTab = 'assets'"
            class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-all"
            :class="activeTab === 'assets' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
        >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                <path d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
            </svg>
            Company Assets
            <span class="rounded-full px-1.5 py-0.5 text-[10px] font-semibold leading-none"
                  :class="activeTab === 'assets' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500'"
                  x-text="assets.length"></span>
        </button>
        <button
            @click="activeTab = 'materials'"
            class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-all"
            :class="activeTab === 'materials' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
        >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true">
                <path d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
            </svg>
            Raw Materials
            <span class="rounded-full px-1.5 py-0.5 text-[10px] font-semibold leading-none"
                  :class="activeTab === 'materials' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500'"
                  x-text="materials.length"></span>
        </button>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════════
         ASSETS TABLE
    ════════════════════════════════════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'assets'" class="overflow-hidden rounded-xl border border-gray-100 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/70">
                        <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400">Asset Name</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400">Category</th>
                        <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400">Purchase Date</th>
                        <th class="px-5 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-400">Purchase Value</th>
                        <th class="px-5 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-400">Current Value</th>
                        <th class="px-5 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-400">Condition</th>
                        <th class="px-5 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">

                    <template x-if="assets.length === 0">
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-gray-50">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
                                            <path d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">No assets recorded</p>
                                    <p class="mt-1 text-xs text-gray-400">Add your first company asset</p>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <template x-for="a in assets" :key="a.id">
                        <tr class="transition-colors hover:bg-gray-50/60">
                            <td class="px-5 py-4">
                                <p class="font-medium text-gray-900" x-text="a.name"></p>
                                <p class="mt-0.5 text-xs text-gray-400" x-show="a.notes" x-text="a.notes"></p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                      :class="categoryStyle(a.category)"
                                      x-text="a.category"></span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500" x-text="formatDate(a.purchaseDate)"></td>
                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-gray-500 tabular-nums" x-text="formatRupiah(a.purchaseValue)"></td>
                            <td class="whitespace-nowrap px-5 py-4 text-right tabular-nums">
                                <p class="font-semibold text-gray-900" x-text="formatRupiah(a.currentValue)"></p>
                                <p class="mt-0.5 text-[11px]"
                                   :class="depreciation(a) > 0 ? 'text-red-400' : 'text-emerald-500'"
                                   x-show="a.purchaseValue > 0"
                                   x-text="depreciation(a) + '% depreciated'"></p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                      :class="conditionStyle(a.condition)"
                                      x-text="a.condition"></span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="openEditAsset(a)"
                                            class="flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-700"
                                            title="Edit">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </button>
                                    <button @click="confirmDelete(a.id, 'asset')"
                                            class="flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500"
                                            title="Delete">
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
        <div class="border-t border-gray-100 bg-gray-50/40 px-5 py-3">
            <p class="text-xs text-gray-400">
                <span class="font-medium text-gray-600" x-text="assets.length"></span> assets &mdash; total current value
                <span class="font-medium text-gray-600" x-text="formatRupiah(totalAssetValue)"></span>
            </p>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════════
         MATERIALS TABLE
    ════════════════════════════════════════════════════════════════════════════ --}}
    <div x-show="activeTab === 'materials'" x-cloak class="overflow-hidden rounded-xl border border-gray-100 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/70">
                        <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wider text-gray-400">Material Name</th>
                        <th class="px-5 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-400">Unit</th>
                        <th class="px-5 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-400">Quantity</th>
                        <th class="px-5 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-400">Unit Price</th>
                        <th class="px-5 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-400">Total Value</th>
                        <th class="px-5 py-3.5 text-right text-[11px] font-semibold uppercase tracking-wider text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">

                    <template x-if="materials.length === 0">
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-gray-50">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
                                            <path d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">No materials recorded</p>
                                    <p class="mt-1 text-xs text-gray-400">Add your first raw material entry</p>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <template x-for="m in materials" :key="m.id">
                        <tr class="transition-colors hover:bg-gray-50/60">
                            <td class="px-5 py-4">
                                <p class="font-medium text-gray-900" x-text="m.name"></p>
                                <p class="mt-0.5 text-xs text-gray-400" x-show="m.notes" x-text="m.notes"></p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-center">
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600" x-text="m.unit"></span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right font-semibold text-gray-900 tabular-nums" x-text="m.quantity.toLocaleString('id-ID')"></td>
                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-gray-500 tabular-nums" x-text="formatRupiah(m.unitPrice)"></td>
                            <td class="whitespace-nowrap px-5 py-4 text-right font-semibold text-purple-600 tabular-nums" x-text="formatRupiah(m.quantity * m.unitPrice)"></td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="openEditMaterial(m)"
                                            class="flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-700"
                                            title="Edit">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </button>
                                    <button @click="confirmDelete(m.id, 'material')"
                                            class="flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-red-50 hover:text-red-500"
                                            title="Delete">
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
        <div class="border-t border-gray-100 bg-gray-50/40 px-5 py-3">
            <p class="text-xs text-gray-400">
                <span class="font-medium text-gray-600" x-text="materials.length"></span> material types &mdash; total value
                <span class="font-medium text-gray-600" x-text="formatRupiah(totalMaterialValue)"></span>
            </p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         Add / Edit Asset Modal
    ═══════════════════════════════════════════════════════════════════════════ --}}
    <div x-show="showAssetModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/25 backdrop-blur-[2px]" @click="showAssetModal = false"></div>
        <div
            class="relative w-full max-w-lg rounded-2xl bg-white shadow-xl"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-[0.97]"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-[0.97]"
            @click.stop
        >
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-gray-900" x-text="isEditing ? 'Edit Asset' : 'Add Asset'"></h3>
                <button @click="showAssetModal = false" class="flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-100" aria-label="Close">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="space-y-4 px-6 py-5">

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Asset Name</label>
                    <input type="text" x-model="assetForm.name" placeholder="e.g. Toyota Avanza" class="form-input py-2">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Category</label>
                        <select x-model="assetForm.category" class="form-input py-2">
                            <template x-for="cat in assetCategories" :key="cat">
                                <option :value="cat" x-text="cat"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Condition</label>
                        <select x-model="assetForm.condition" class="form-input py-2">
                            <template x-for="c in conditions" :key="c">
                                <option :value="c" x-text="c"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Purchase Date</label>
                    <input type="date" x-model="assetForm.purchaseDate" class="form-input py-2">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Purchase Value (Rp)</label>
                        <input type="number" x-model="assetForm.purchaseValue" placeholder="0" min="0" class="form-input py-2">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Current Value (Rp)</label>
                        <input type="number" x-model="assetForm.currentValue" placeholder="0" min="0" class="form-input py-2">
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Notes <span class="font-normal text-gray-400">(optional)</span>
                    </label>
                    <textarea x-model="assetForm.notes" rows="2" placeholder="Additional notes..." class="form-input resize-none py-2"></textarea>
                </div>

            </div>
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-6 py-4">
                <button @click="showAssetModal = false" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50">Cancel</button>
                <button
                    @click="saveAsset()"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:bg-indigo-700 active:scale-[0.99]"
                    :class="(!assetForm.name || !assetForm.purchaseValue) && 'opacity-50 pointer-events-none'"
                    x-text="isEditing ? 'Save Changes' : 'Add Asset'"
                ></button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         Add / Edit Material Modal
    ═══════════════════════════════════════════════════════════════════════════ --}}
    <div x-show="showMaterialModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/25 backdrop-blur-[2px]" @click="showMaterialModal = false"></div>
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
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="text-sm font-semibold text-gray-900" x-text="isEditing ? 'Edit Material' : 'Add Material'"></h3>
                <button @click="showMaterialModal = false" class="flex h-7 w-7 items-center justify-center rounded-lg text-gray-400 transition-colors hover:bg-gray-100" aria-label="Close">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="space-y-4 px-6 py-5">

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Material Name</label>
                    <input type="text" x-model="materialForm.name" placeholder="e.g. Steel Bar" class="form-input py-2">
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Unit</label>
                        <select x-model="materialForm.unit" class="form-input py-2">
                            <template x-for="u in units" :key="u">
                                <option :value="u" x-text="u"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" x-model="materialForm.quantity" placeholder="0" min="0" class="form-input py-2">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700">Unit Price (Rp)</label>
                        <input type="number" x-model="materialForm.unitPrice" placeholder="0" min="0" class="form-input py-2">
                    </div>
                </div>

                <div x-show="materialForm.quantity && materialForm.unitPrice" class="rounded-lg bg-purple-50 px-4 py-3">
                    <p class="text-xs text-purple-600">
                        Total value: <span class="font-semibold" x-text="formatRupiah(materialForm.quantity * materialForm.unitPrice)"></span>
                    </p>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Notes <span class="font-normal text-gray-400">(optional)</span>
                    </label>
                    <textarea x-model="materialForm.notes" rows="2" placeholder="Grade, specification, supplier..." class="form-input resize-none py-2"></textarea>
                </div>

            </div>
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-6 py-4">
                <button @click="showMaterialModal = false" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50">Cancel</button>
                <button
                    @click="saveMaterial()"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:bg-indigo-700 active:scale-[0.99]"
                    :class="(!materialForm.name || !materialForm.quantity || !materialForm.unitPrice) && 'opacity-50 pointer-events-none'"
                    x-text="isEditing ? 'Save Changes' : 'Add Material'"
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
            <h3 class="text-sm font-semibold text-gray-900">
                Delete <span x-text="deletingType === 'asset' ? 'asset' : 'material'"></span>?
            </h3>
            <p class="mt-1 text-sm text-gray-400">This action cannot be undone.</p>
            <div class="mt-5 flex gap-3">
                <button @click="showDeleteConfirm = false" class="flex-1 rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50">Cancel</button>
                <button @click="doDelete()" class="flex-1 rounded-lg bg-red-500 px-4 py-2 text-sm font-semibold text-white transition-all hover:bg-red-600 active:scale-[0.99]">Delete</button>
            </div>
        </div>
    </div>

</div>
@endsection
