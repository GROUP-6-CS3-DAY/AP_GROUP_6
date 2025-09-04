# InnoTrack Web - Innovation Tracking System

A Laravel Blade MVC application for tracking innovation programs, facilities, services, equipment, projects, and outcomes.

## 🚀 Tech Stack

-   **Framework**: Laravel 12 (PHP 8.2+)
-   **Views**: Blade templates
-   **Styling**: Bootstrap (plus Tailwind via CDN on some views)
-   **Database**: MySQL (Eloquent ORM)
-   **Auth**: Laravel session authentication (routes scaffold in `routes/web.php`)
-   **Build tools**: None required for Blade-only views (optional Vite not used)

## 📁 Project Structure (key directories)

```
innotrack-web/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── FacilityController.php
│   │   │   ├── ServiceController.php
│   │   │   └── EquipmentController.php
│   │   └── Middleware/ ...
│   └── Models/
│       ├── Facility.php
│       ├── Service.php
│       ├── Equipment.php
│       ├── Program.php
│       ├── Project.php
│       └── Outcome.php
├── database/
│   └── migrations/
│       ├── 2025_08_26_150736_create_program_table.php
│       ├── 2025_08_26_160000_create_facilities_table.php
│       ├── 2025_08_26_160100_create_project_table.php
│       ├── 2025_08_26_160100_create_services_table.php
│       └── 2025_08_26_160200_create_equipment_table.php
├── resources/
│   ├── views/
│   │   ├── layouts/app.blade.php
│   │   ├── welcome.blade.php
│   │   ├── dashboard/overview.blade.php
│   │   ├── facilities/ (index|create|edit|show).blade.php
│   │   ├── services/ (index|create|edit|show).blade.php
│   │   └── equipment/ (index|create|edit|show).blade.php
│   └── js/ (bootstrap.js, app.jsx present but not required for Blade)
├── routes/
│   └── web.php
└── public/
    └── index.php
```

## 🔀 Routes (high-level)

-   `/` → `welcome`
-   `/dashboard` → `dashboard.overview`
-   `/facilities` → `FacilityController` (index, create, store, show, edit, update, destroy)
-   `/services` → `ServiceController` (index, create, store, show, edit, update, destroy)
-   `/equipment` → `EquipmentController` (index, create, store, show, edit, update, destroy)

Counts on the dashboard are computed in the `/dashboard` route and passed to `resources/views/dashboard/overview.blade.php`.

## 🧱 Database & Migrations

-   All tables use `id` as the primary key.
-   Foreign keys use `foreignId()->constrained('<table>')->onDelete('cascade')`.
-   Fixes applied for FK order and table names (e.g., `projects` depends on `programs` and `facilities`).

Run migrations:

```bash
php artisan migrate
```

If you need a clean slate:

```bash
php artisan migrate:fresh
```

## 🧭 Development Setup

### Prerequisites

-   PHP 8.2+
-   Composer
-   MySQL
-   Git

### Install dependencies

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure your `.env` database section, then run:

```bash
php artisan migrate
```

### Running the app

If you installed PHP 8.2 with MacPorts (path is `/opt/local/bin/php82`):

```bash
/opt/local/bin/php82 artisan serve --host=127.0.0.1 --port=8000
```

If `php` already points to 8.2+:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Note: If you see a Herd path error (e.g., `.../Herd/bin/php does not exist`), run the command using the full PHP 8.2 path (`/opt/local/bin/php82`) as shown above.

## ✅ Validation Highlights

-   Facilities
    -   `name` unique across facilities (create/update)
    -   `capabilities` accepted as array or JSON string (normalized in controller)
-   Services
    -   `name` unique per facility (can repeat across different facilities)
    -   `facility_id` must exist in `facilities(id)`

## 🧩 Conventions

-   Blade-only UI (no Vite dev server required). Tailwind is used via CDN in `welcome` (optional).
-   Eloquent relationships are defined in models (`Facility` ↔ `Service`/`Equipment`).
-   Controllers handle filtering, validation, and pagination.

## 🔒 Authentication

Routes are currently public for development. If you enable auth, wrap resource routes in middleware and scaffold views accordingly.

## 🧪 Testing

```bash
php artisan test
```

## 📝 Git Workflow

-   Feature branches off `kiggundu` (remote)
-   Local branch `ayman` tracks `origin/kiggundu`
    -   Set up: `git branch --set-upstream-to=origin/kiggundu ayman`
    -   Push: `git push`
    -   Pull: `git pull`

## 📧 Support

For questions/issues, open a GitHub issue.
