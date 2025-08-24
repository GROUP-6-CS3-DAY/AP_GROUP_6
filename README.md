# InnoTrack Web - Innovation Tracking System

A comprehensive web application for managing multidisciplinary student projects at government facilities, aligned with Uganda's NDPIII, Digital Transformation Roadmap, and 4IR Strategy.

## 🚀 Tech Stack

-   **Backend**: Laravel 10 (PHP 8.1+)
-   **Frontend**: React 18 + Inertia.js
-   **Styling**: Tailwind CSS
-   **Database**: MySQL/PostgreSQL
-   **Authentication**: Laravel Breeze + Sanctum
-   **Testing**: PHPUnit + Pest

## 📋 Project Structure

```
innotrack-web/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/           # API Controllers
│   │   │   ├── Admin/         # Admin Controllers
│   │   │   └── Dashboard/     # Dashboard Controllers
│   │   ├── Requests/          # Form Requests
│   │   └── Resources/         # API Resources
│   ├── Models/                # Eloquent Models
│   ├── Services/              # Business Logic
│   └── Policies/              # Authorization Policies
├── database/
│   ├── migrations/            # Database Migrations
│   ├── seeders/               # Database Seeders
│   └── factories/             # Model Factories
├── resources/
│   └── js/
│       ├── Components/        # Reusable React Components
│       ├── Pages/             # Page Components
│       ├── Layouts/           # Layout Components
│       ├── Hooks/             # Custom React Hooks
│       ├── Services/          # API Services
│       └── Utils/             # Utility Functions
└── tests/
    ├── Feature/               # Feature Tests
    ├── Unit/                  # Unit Tests
    └── Browser/               # Browser Tests
```

## 🛠️ Setup Instructions

### Prerequisites

-   PHP 8.1+
-   Composer
-   Node.js 18+
-   MySQL/PostgreSQL
-   Git

### Installation

1. **Clone the repository**

    ```bash
    git clone <repository-url>
    cd innotrack-web
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install Node.js dependencies**

    ```bash
    npm install
    ```

4. **Environment setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Database setup**

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

6. **Start development servers**

    ```bash
    # Terminal 1: Laravel development server
    php artisan serve

    # Terminal 2: Vite development server
    npm run dev
    ```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run tests with coverage
php artisan test --coverage

# Run specific test suite
php artisan test --testsuite=Feature
```

## 📚 API Documentation

The API follows RESTful conventions and includes:

-   **Authentication**: Bearer token via Laravel Sanctum
-   **Validation**: Form Request classes for input validation
-   **Resources**: API Resources for consistent response formatting
-   **Pagination**: Laravel's built-in pagination

### Core Endpoints

-   `GET /api/programs` - List all programs
-   `GET /api/facilities` - List all facilities
-   `GET /api/projects` - List all projects
-   `GET /api/participants` - List all participants
-   `POST /api/projects` - Create a new project
-   `PUT /api/projects/{id}` - Update a project
-   `DELETE /api/projects/{id}` - Delete a project

## 🎯 Development Guidelines

### Code Style

-   Follow PSR-12 for PHP code
-   Use ESLint and Prettier for JavaScript/React
-   Write meaningful commit messages

### Testing Strategy

-   Unit tests for models and services
-   Feature tests for API endpoints
-   Browser tests for critical user flows

### Git Workflow

-   Feature branches for new development
-   Pull requests for code review
-   Squash commits before merging

## 📅 Development Timeline

### Month 1: Web Application (Weeks 1-4)

-   **Weeks 1-2**: Core CRUD operations for all entities
-   **Weeks 3-4**: Testing, API documentation, and deployment

### Month 2: Mobile Application (Weeks 5-8)

-   **Weeks 5-6**: Mobile app foundation and core features
-   **Weeks 7-8**: Feature parity and deployment

## 👥 Team Responsibilities

See the detailed task breakdown in the project documentation.

## 📞 Support

For questions or issues, please create an issue in the repository or contact the development team.
