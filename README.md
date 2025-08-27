# InnoTrack Web - Innovation Tracking System

A comprehensive web application for managing multidisciplinary student projects at government facilities, aligned with Uganda's NDPIII, Digital Transformation Roadmap, and 4IR Strategy.

## 🚀 Tech Stack

-   **Backend**: Laravel 12 (PHP 8.2+)
-   **Frontend**: Laravel Blade Templates
-   **Styling**: Tailwind CSS 4.0
-   **Database**: MySQL/PostgreSQL
-   **Authentication**: Laravel Breeze
-   **Testing**: PHPUnit + Pest
-   **Asset Compilation**: Vite

## 📋 Project Structure

```
innotrack-web/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # MVC Controllers
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
│   ├── views/                 # Blade Templates
│   │   ├── layouts/           # Layout Templates
│   │   ├── components/        # Blade Components
│   │   ├── pages/             # Page Templates
│   │   └── partials/          # Reusable Template Parts
│   ├── css/                   # Stylesheets
│   └── js/                    # JavaScript Files
└── tests/
    ├── Feature/               # Feature Tests
    ├── Unit/                  # Unit Tests
    └── Browser/               # Browser Tests
```

## 🛠️ Setup Instructions

### Prerequisites

-   PHP 8.2+
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

6. **Start development server**

    ```bash
    # Start Laravel development server
    php artisan serve

    # In another terminal, start Vite for asset compilation
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

-   **Authentication**: Session-based authentication
-   **Validation**: Form Request classes for input validation
-   **Resources**: API Resources for consistent response formatting
-   **Pagination**: Laravel's built-in pagination

### Core Endpoints

-   `GET /programs` - List all programs
-   `GET /facilities` - List all facilities
-   `GET /projects` - List all projects
-   `GET /participants` - List all participants
-   `POST /projects` - Create a new project
-   `PUT /projects/{id}` - Update a project
-   `DELETE /projects/{id}` - Delete a project

## 🎯 Development Guidelines

### Code Style

-   Follow PSR-12 for PHP code
-   Use Laravel Pint for code formatting
-   Write meaningful commit messages

### Testing Strategy

-   Unit tests for models and services
-   Feature tests for web routes
-   Browser tests for critical user flows

### Git Workflow

-   Feature branches for new development
-   Pull requests for code review
-   Squash commits before merging

## 📅 Development Timeline

### Month 1: Web Application (Weeks 1-4)

-   **Weeks 1-2**: Core CRUD operations for all entities
-   **Weeks 3-4**: Testing, documentation, and deployment

### Month 2: Mobile Application (Weeks 5-8)

-   **Weeks 5-6**: Mobile app foundation and core features
-   **Weeks 7-8**: Feature parity and deployment

## 👥 Team Responsibilities

See the detailed task breakdown in the project documentation.

## 📞 Support

For questions or issues, please create an issue in the repository or contact the development team.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
