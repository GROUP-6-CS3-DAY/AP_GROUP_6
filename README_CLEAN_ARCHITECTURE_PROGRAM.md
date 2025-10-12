# Clean Architecture Implementation - Program Entity

This document explains how all the files work together to implement clean architecture for the Program entity in our Laravel application.

## Architecture Overview

Our clean architecture follows the **Domain-Driven Design (DDD)** pattern with clear separation of concerns:

```
┌─────────────────────────────────────────────────────────────┐
│                    Presentation Layer                        │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐ │
│  │   Controllers   │  │     Views       │  │  Requests   │ │
│  └─────────────────┘  └─────────────────┘  └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                   Application Layer                          │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐ │
│  │   Use Cases     │  │      DTOs       │  │ Interfaces  │ │
│  └─────────────────┘  └─────────────────┘  └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                     Domain Layer                             │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐ │
│  │   Entities      │  │ Value Objects   │  │ Repository  │ │
│  │                 │  │                 │  │ Interfaces  │ │
│  └─────────────────┘  └─────────────────┘  └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                Infrastructure Layer                          │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐ │
│  │  Repositories   │  │   Eloquent      │  │   Service   │ │
│  │ (Concrete)      │  │    Models       │  │  Provider   │ │
│  └─────────────────┘  └─────────────────┘  └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

## File Structure and Flow

### 1. **Domain Layer** - Core Business Logic

#### A. Domain Entity
**File**: `app/Domain/Entities/Program.php`
```php
class Program {
    // Business logic and domain rules
    // No dependencies on external frameworks
    // Pure PHP with business validation
}
```

**Purpose**: 
- Contains core business logic
- Encapsulates business rules and validation
- Framework-independent
- Methods like `update()`, `isActive()`, `canAcceptProjects()`

#### B. Value Objects
**File**: `app/Domain/ValueObjects/ProgramPhase.php`
```php
class ProgramPhase {
    // Immutable objects representing domain concepts
    // Self-validating with business rules
}
```

**Purpose**:
- Represent domain concepts (like program phases)
- Self-validating (throws exceptions for invalid values)
- Immutable once created
- Provides display names and validation options

#### C. Repository Interface
**File**: `app/Domain/Repositories/ProgramRepositoryInterface.php`
```php
interface ProgramRepositoryInterface {
    // Contract for data access - no implementation details
    public function findById(string $id): ?Program;
    public function findAll(): array;
    // ...
}
```

**Purpose**:
- Defines contract for data access
- Domain layer doesn't know about databases
- Allows dependency inversion

### 2. **Application Layer** - Use Cases and DTOs

#### A. Use Cases
**Files**: 
- `app/Application/UseCases/CreateProgramUseCase.php`
- `app/Application/UseCases/UpdateProgramUseCase.php`

```php
class CreateProgramUseCase {
    public function execute(CreateProgramDTO $dto): string {
        // 1. Create domain entity with business validation
        // 2. Use repository to persist
        // 3. Return result
    }
}
```

**Purpose**:
- Orchestrates business operations
- Converts DTOs to domain entities
- Handles business workflows
- Independent of presentation layer

#### B. Data Transfer Objects (DTOs)
**Files**:
- `app/Application/DTOs/CreateProgramDTO.php`
- `app/Application/DTOs/UpdateProgramDTO.php`

```php
class CreateProgramDTO {
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        // ... other properties
    ) {}
}
```

**Purpose**:
- Transfer data between layers
- Immutable data containers
- No business logic
- Type-safe data transfer

### 3. **Infrastructure Layer** - External Concerns

#### A. Repository Implementation
**File**: `app/Infrastructure/Repositories/EloquentProgramRepository.php`

```php
class EloquentProgramRepository implements ProgramRepositoryInterface {
    public function findById(string $id): ?Program {
        $model = ProgramModel::find($id);
        return $this->mapToEntity($model); // Convert Eloquent to Domain
    }
    
    private function mapToEntity(ProgramModel $model): Program {
        // Maps Eloquent model to Domain entity
    }
}
```

**Purpose**:
- Implements repository interface
- Handles database operations using Eloquent
- Maps between Eloquent models and Domain entities
- Isolates persistence concerns

#### B. Eloquent Model
**File**: `app/Models/Program.php`

```php
class Program extends Model {
    // Standard Laravel Eloquent model
    // Database-specific concerns only
}
```

**Purpose**:
- Laravel's ORM representation
- Database schema mapping
- Relationships and queries
- Framework-specific concerns

#### C. Service Provider
**File**: `app/Providers/AppServiceProvider.php`

```php
class AppServiceProvider extends ServiceProvider {
    public function register(): void {
        $this->app->bind(
            ProgramRepositoryInterface::class, 
            EloquentProgramRepository::class
        );
    }
}
```

**Purpose**:
- **Dependency Injection Configuration**
- Tells Laravel which concrete class to use when interface is requested
- Enables **Dependency Inversion Principle**
- Allows easy swapping of implementations (e.g., from Eloquent to API)

### 4. **Presentation Layer** - User Interface

#### A. Controller
**File**: `app/Presentation/Http/Controllers/ProgramController.php`

```php
class ProgramController extends Controller {
    public function __construct(
        private ProgramRepositoryInterface $programRepository,
        private CreateProgramUseCase $createProgramUseCase,
        // ...
    ) {}
    
    public function store(CreateProgramRequest $request) {
        $dto = new CreateProgramDTO(/* ... */);
        $this->createProgramUseCase->execute($dto);
    }
}
```

**Purpose**:
- Handles HTTP requests/responses
- Converts HTTP data to DTOs
- Calls appropriate use cases
- Returns views or redirects

#### B. Form Requests
**Files**:
- `app/Presentation/Requests/CreateProgramRequest.php`
- `app/Presentation/Requests/UpdateProgramRequest.php`

```php
class CreateProgramRequest extends FormRequest {
    public function rules(): array {
        // HTTP validation rules
        // Input sanitization
    }
}
```

**Purpose**:
- HTTP-level validation
- Input sanitization
- Authorization checks
- Prepares data for DTOs

#### C. Views
**Files**: `resources/views/programs/*.blade.php`

```php
// Uses domain entities through getter methods
{{ $program->getName() }}
{{ $program->getDescription() }}
```

**Purpose**:
- User interface rendering
- Uses domain entities via getter methods
- No business logic in views

## Data Flow Example: Creating a Program

Let's trace how creating a program works through all layers:

### 1. HTTP Request Comes In
```
POST /programs
{
    "name": "Innovation Program 2024",
    "description": "Advanced technology program",
    "national_alignment": "Digital Transformation",
    "focus_areas": ["AI", "IoT"],
    "phases": ["planning", "development"]
}
```

### 2. Route Processing
**File**: `routes/web.php`
```php
Route::resource('programs', ProgramController::class);
// This routes POST /programs to ProgramController@store
```

### 3. Controller Receives Request
**File**: `ProgramController.php`
```php
public function store(CreateProgramRequest $request) {}
    // $request automatically validated by CreateProgramRequest
```

### 4. Form Request Validation
**File**: `CreateProgramRequest.php`
```php
public function rules(): array {
    return [
        'name' => 'required|string|min:3|max:255',
        'description' => 'required|string|min:10',
        // ... validation rules
    ];
}
// If validation fails, Laravel automatically returns errors
// If validation passes, controller method continues
```

### 5. Controller Creates DTO
```php
$dto = new CreateProgramDTO(
    name: $request->validated('name'),
    description: $request->validated('description'),
    nationalAlignment: $request->validated('national_alignment'),
    focusAreas: $request->validated('focus_areas'),
    phases: $request->validated('phases')
);
```

### 6. Controller Calls Use Case
```php
$this->createProgramUseCase->execute($dto);
```

### 7. Use Case Creates Domain Entity
**File**: `CreateProgramUseCase.php`
```php
public function execute(CreateProgramDTO $dto): string {
    $program = new Program(
        id: Str::uuid()->toString(),
        name: $dto->name,
        description: $dto->description,
        nationalAlignment: $dto->nationalAlignment,
        focusAreas: $dto->focusAreas,
        phases: array_map(fn($phase) => new ProgramPhase($phase), $dto->phases)
    );
    // Program constructor runs business validation
}
```

### 8. Domain Entity Validation
**File**: `Program.php`
```php
public function __construct(...) {
    // Business rules validation happens here
    if (strlen($name) < 3) {
        throw new \DomainException('Program name must be at least 3 characters');
    }
    // ...
}
```

### 9. Use Case Saves via Repository
```php
$this->programRepository->save($program);
return $program->getId();
```

### 10. Repository Implementation Persists
**File**: `EloquentProgramRepository.php`
```php
public function save(Program $program): void {
    $model = ProgramModel::find($program->getId()) ?? new ProgramModel();
    
    $model->fill([
        'id' => $program->getId(),
        'name' => $program->getName(),
        'description' => $program->getDescription(),
        'national_alignment' => $program->getNationalAlignment(),
        'focus_areas' => $program->getFocusAreasAsString(),
        'phases' => $program->getPhasesAsString(),
    ]);

    $model->save(); // Laravel Eloquent saves to database
}
```

### 11. Controller Returns Response
```php
return redirect()->route('programs.index')
    ->with('success', 'Program created successfully');
```

## Key Benefits of This Architecture

### 1. **Separation of Concerns**
- Each layer has a single responsibility
- Business logic is isolated in Domain layer
- Database concerns are in Infrastructure layer
- HTTP concerns are in Presentation layer

### 2. **Testability**
```php
// Easy to unit test business logic
$program = new Program(/* test data */);
$this->assertTrue($program->isActive());

// Easy to mock dependencies
$mockRepository = $this->createMock(ProgramRepositoryInterface::class);
$useCase = new CreateProgramUseCase($mockRepository);
```

### 3. **Flexibility**
- Can swap database implementations without changing business logic
- Can change presentation layer (web to API) without touching domain
- Easy to add new use cases

### 4. **Domain-Driven Design**
- Business rules are explicit in domain entities
- Value objects prevent invalid states
- Ubiquitous language throughout codebase

### 5. **SOLID Principles**
- **S**ingle Responsibility: Each class has one job
- **O**pen/Closed: Easy to extend without modification
- **L**iskov Substitution: Interfaces can be swapped
- **I**nterface Segregation: Small, focused interfaces
- **D**ependency Inversion: Depend on abstractions, not concretions

## How AppServiceProvider.php Works

The `AppServiceProvider.php` is crucial for **Dependency Injection**:

```php
$this->app->bind(
    ProgramRepositoryInterface::class,    // When someone asks for this interface
    EloquentProgramRepository::class      // Give them this concrete class
);
```

**When Laravel sees this in a constructor:**
```php
public function __construct(ProgramRepositoryInterface $programRepository) {}
```

**Laravel automatically provides:**
```php
new EloquentProgramRepository() // Because of the binding
```

This allows us to:
- Change implementations easily (swap EloquentProgramRepository for ApiProgramRepository)
- Test with mock implementations
- Follow Dependency Inversion Principle

## Summary

This clean architecture ensures:
1. **Business logic** is protected in the Domain layer
2. **Database concerns** are isolated in Infrastructure layer  
3. **HTTP concerns** are handled in Presentation layer
4. **Use cases** orchestrate operations in Application layer
5. **Dependencies flow inward** (outer layers depend on inner layers, never reverse)

The result is maintainable, testable, and flexible code that can evolve with business requirements.
