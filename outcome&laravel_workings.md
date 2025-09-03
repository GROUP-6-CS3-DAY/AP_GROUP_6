# InnoTrack Web Application

## Laravel & MVC Overview

Laravel is a modern PHP framework that uses the MVC (Model-View-Controller) architectural pattern. MVC separates application logic into three main components:
- **Model:** Handles data and business logic (database tables, Eloquent models).
- **View:** Displays data to the user (Blade templates).
- **Controller:** Processes user requests, interacts with models, and returns views.

This separation makes code organized, maintainable, and scalable.

## Outcome Feature Implementation

The Outcome feature tracks results or deliverables for projects. Here’s how it is implemented using Laravel’s MVC structure:

### 1. Migration
- The `outcomes` table is defined in a migration file, specifying columns like `outcome_ID`, `project_ID`, `title`, `description`, etc.
- Foreign key constraints ensure each outcome is linked to a project.

### 2. Model
- The `Outcome` Eloquent model represents the `outcomes` table.
- It defines fillable fields and a relationship to the `Project` model (`belongsTo`).

### 3. Factory & Seeder
- The `OutcomeFactory` generates fake data for testing and seeding.
- The `DatabaseSeeder` creates projects and outcomes, ensuring each outcome is linked to a valid project.

### 4. Controller
- The `OutcomeController` handles all HTTP requests for outcomes:
  - `index()`: Lists all outcomes.
  - `create()`: Shows a form to add a new outcome (with a project dropdown).
  - `store()`: Validates and saves a new outcome.
  - `show()`: Displays details of a single outcome.
  - `edit()`: Shows a form to edit an outcome.
  - `update()`: Validates and updates an outcome.
  - `destroy()`: Deletes an outcome.

### 5. Views
- Blade templates in `resources/views/outcomes/` provide user interfaces for listing, creating, editing, and viewing outcomes.
- Forms use dropdowns to select related projects and display all relevant outcome fields.

### 6. Routes
- Resource routes in `routes/web.php` map URLs to controller actions for outcomes (CRUD operations).

## Summary

The Outcome feature demonstrates how Laravel’s MVC pattern organizes code for database-driven features. Models define data, controllers handle logic, and views present information, all connected through clear routes and migrations.
