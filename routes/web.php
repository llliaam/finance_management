<?php

use Illuminate\Support\Facades\Route;

// ─── Auth ────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', fn () => view('auth.login'))->name('login');

Route::post('/login', function () {
    // Backend auth will be wired here later
    return back()->withInput();
});

Route::post('/logout', fn () => redirect()->route('login'))->name('logout');

// ─── App ─────────────────────────────────────────────────
// Note: auth middleware will be added once backend auth is implemented

Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

Route::get('/transactions', fn () => view('transactions.index'))->name('transactions.index');

Route::get('/assets', fn () => view('assets.index'))->name('assets.index');

Route::get('/reports', fn () => view('reports.index'))->name('reports.index');
