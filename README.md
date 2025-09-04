# InnoTrack Web - Innovation Tracking System

A comprehensive Laravel Blade MVC application enabling multidisciplinary student teams from Computer Science/Software Engineering and Engineering to collaborate on real-world projects carried out at government facilities. The system provides a unified platform to manage programs, facilities, services, equipment, projects, participants, and outcomes, ensuring that projects are well-organized, properly resourced, and aligned with Uganda's NDPIII, Digital Transformation Roadmap (2023–2028), and 4IR Strategy.

Each project team consists of five students, ensuring diversity of skills and balanced workloads. For effective accountability and evaluation, teams are required to use project management tools such as ClickUp or GitHub Projects to track weekly contributions.

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

## 🎯 Core Entities

### Programs

-   Collaboration umbrella under which projects run
-   Links to NDPIII, Roadmap, or 4IR goals
-   Focus areas: IoT, automation, renewable energy, cross-skilling, collaboration, technical skills, prototyping, commercialization

### Facilities

-   Government places where projects are executed
-   Partner organizations: UniPod, UIRI, Lwera Lab, SCIT, CEDAT
-   Types: Workshop, Testing Center, Laboratory, Maker Space, Innovation Hub, Research Center
-   Capabilities: CNC machining, PCB fabrication, materials testing, 3D printing, welding, electronics testing, software development, IoT prototyping, renewable energy, automation

### Services

-   Types of work a facility can perform
-   Categories: Machining, Testing, Training, Prototyping, Fabrication, Analysis, Consultation
-   Skill types: Hardware, Software, Integration, Business, Research

### Equipment

-   Machinery/tools available at facilities
-   Usage domains: Electronics, Mechanical, IoT
-   Support phases: Training, Prototyping, Testing, Commercialization

### Projects

-   Work carried out by student teams
-   Innovation focus: IoT devices, smart home, renewable energy
-   Prototype stages: Concept, Prototype, MVP, Market Launch
-   Commercialization plans and testing requirements

### Participants

-   Students, lecturers, and collaborators
-   Affiliations: CS, SE, Engineering, Other
-   Specializations: Software, hardware, business
-   Cross-skill training tracking

### Outcomes

-   Project deliverables: CAD files, PCB designs, prototypes, reports, business plans
-   Quality certifications and commercialization status tracking

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

## 🛠️ Development Setup

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

-   Feature branches off `main` (remote)
-   Local branch `ayman` tracks `origin/main`
    -   Set up: `git branch --set-upstream-to=origin/main ayman`
    -   Push: `git push`
    -   Pull: `git pull`

## 📅 Development Timeline

### Month 1: Web Application (Weeks 1-4)

-   **Weeks 1-2**: Core CRUD operations for all entities (Facilities, Services, Equipment, Projects, Participants, Outcomes)
-   **Weeks 3-4**: Unit and integration testing, CI/CD setup

### Month 2: Mobile Application (Weeks 5-8)

-   **Weeks 5-6**: Cross-platform mobile app foundation and core features
-   **Weeks 7-8**: Feature parity with web app and deployment

## 📧 Support

For questions/issues, open a GitHub issue.
