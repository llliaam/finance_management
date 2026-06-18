# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.


What's are the main goal for this project?

## The Problem and Solution
The client currently faces challenges in its financial management and administration processes, as transaction recording, asset management, raw material cost tracking, and financial record-keeping are still done manually using Excel files and can only be accessed via specific computers or laptops. This situation results in inefficient data management, increases the risk of recording errors (human error), causes delays in data updates, and makes it difficult to monitor the company’s financial condition in real time and ensure integration among finance staff.
As a solution, we offer the development of a responsive, web-based financial management application that is fully integrated online and accessible to finance staff accounts. There is no multi-level role system — all users are finance staff with the same access. This application is designed to help companies manage cash inflows and outflows, automatically categorize transactions based on categories such as assets, operating expenses, and raw materials, and provide real-time financial reports and insights. Additionally, the application will include a feature to export financial reports to various formats, such as Excel and PDF, making the financial management process more effective, accurate, and flexible, and supporting faster and more precise corporate decision-making.

## Feature in this project (Scope of Work)
1. Account Authentication System : User login and logout and user authentication security (single role: finance staff only, no permission levels)

2. Cash Flow & Transaction Categorization Module : Recording of cash inflows and outflows,
Automatic transaction categorization, Transaction history and data search and Transaction filtering by date/category

3. Asset & Raw Material Management : Company asset inventory, Asset value monitoring, Raw Material Cost Management and Asset Data Change History

4. Analytics Dashboard : Financial Status Summary, Income and Expense Charts, Transaction Statistics and Real-Time Financial Insights

5. Financial Report Generator : Cash Flow Statements, Transaction Recap, Export Reports to Excel and PDF Formats and Print Financial Reports



## Development Commands

**Start all dev services (server + queue + logs + Vite) in one command:**
```bash
composer dev
```
Runs `php artisan serve`, `php artisan queue:listen`, `php artisan pail`, and `npm run dev` concurrently via `concurrently`.

**Run tests:**
```bash
composer test
```
Single test file or method:
```bash
php artisan test tests/Feature/ExampleTest.php
php artisan test --filter=test_method_name
```

**Fix code style:**
```bash
vendor/bin/pint
```

**Build frontend assets:**
```bash
npm run build   # production build
npm run dev     # Vite dev server with HMR
```

**Fresh project setup (after cloning):**
```bash
composer setup
```
This runs `composer install`, copies `.env.example` to `.env`, generates the app key, runs migrations, installs npm packages, and builds assets. The SQLite database file must exist at `database/database.sqlite` before running migrations — create it manually if missing (`touch database/database.sqlite` or equivalent on Windows).

## Architecture

**Laravel 13** application — currently a blank skeleton with no finance-specific models, controllers, or routes yet. The only view is the default `welcome.blade.php`.

**Stack:**
- PHP 8.4, Laravel 13
- SQLite (`database/database.sqlite`) for local dev; session, cache, and queue all use the database driver
- Tailwind CSS v4 via `@tailwindcss/vite` — no `tailwind.config.js`; configured entirely in `resources/css/app.css` via `@import "tailwindcss"`
- Vite 8 with `laravel-vite-plugin`; entrypoints are `resources/css/app.css` and `resources/js/app.js`
- Font: "Instrument Sans" (400/500/600) via Bunny Fonts CDN, configured in `vite.config.js` using the `bunny()` helper. The `@fonts` Blade directive (provided by `laravel/pao`) injects the font `<link>` tag — use it in the `<head>` of every layout.

**Testing:**
- PHPUnit with `Unit` and `Feature` suites
- Tests run against an in-memory SQLite DB (`DB_DATABASE=:memory:`) — no separate test database needed
- Cache/session use `array` driver and queue uses `sync` in test runs

**PHP extensions required:**
- `pdo_sqlite` and `sqlite3` must be enabled in `php.ini` (`C:\php\php-8.4.7-Win32-vs17-x64\php.ini`)

## Frontend Design Skills

Design skills from `Leonxlnx/taste-skill` are installed in `.agents/skills/` and symlinked into Claude Code. See `SKILL.md` for the full reference. The primary skill for building UI is `design-taste-frontend`.
