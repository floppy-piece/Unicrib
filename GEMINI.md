# Laravel Project Guidelines & Architecture

## Core Technology Stack
- **Framework:** Laravel 11.x (PHP 8.3+)
- **Database:** PostgreSQL (with complex JSONB operations and indexing)
- **Caching & Queues:** Redis (Horizon for queue management)
- **Frontend / API:** Dual-mode (Blade/Alpine.js for admin, RESTful API for mobile app clients)
- **Testing:** Pest PHP (Unit & Feature suites)
- **Analysis & Linting:** PHPStan (Level 8), Laravel Pint (PSR-12/Laravel code style)

## Architecture & Code Conventions

### 1. Thin Controllers, Fat Models, Strategic Services
- **Controllers:** Keep controllers strictly responsible for request handling and response formatting. Use Form Requests for validation.
- **Services:** Place complex, multi-model business logic inside dedicated Service classes (`app/Services`).
- **Repositories:** Use data repositories (`app/Repositories`) only for heavy database queries requiring abstraction or complex caching logic.
- **Models:** Utilize Eloquent models for relationships, scopes, and attributes. Avoid placing complex multi-entity business logic inside models.

### 2. Database & Eloquent
- **Migrations:** Every migration must include a `down()` rollback method. Add indexes explicitly on foreign keys and frequently queried columns.
- **Query Optimization:** Never allow N+1 queries. Always use `with()` for eager loading. Ensure `Model::preventLazyLoading(!app()->isProduction())` is respected.
- **Transactions:** Always wrap multi-row mutations inside `DB::transaction()` blocks.

### 3. Asynchronous & Queue Management
- **Jobs:** Long-running processes, external API calls, and email dispatches must be queued.
- **Traits:** Always implement `ShouldQueue`, `Queueable`, and `SerializesModels` on Jobs.
- **Idempotency:** Ensure all jobs are idempotent to avoid side effects upon retries.

### 4. Code Quality & Formatting
- **Strict Typing:** Always declare strict types (`declare(strict_types=1);`) at the top of every PHP file.
- **Type Hinting:** Explicitly define argument types and method return types everywhere. Use PHPDoc annotations only when generics or complex array shapes are needed.
- **Naming Conventions:**
  - Controllers: Singular or Plural with suffix (`UserController.php`).
  - Services: Descriptive action or domain-focused (`PaymentProcessingService.php`).
  - Data Transfer Objects (DTOs): Use read-only classes for complex payload passing.

## Development & Operational Commands

### Environment Setup & Maintenance
- `composer install && npm install` - Install dependencies
- `php artisan migrate --seed` - Run migrations and populate initial seeding
- `php artisan optimize:clear` - Clear all application caches

### Quality Assurance & Testing
- `composer pint` - Code style alignment and formatting
- `composer phpstan` - Static code analysis execution
- `php artisan test` or `vendor/bin/pest` - Execute test runner

## Security Protocols
- **Mass Assignment:** Maintain strict security by keeping `$guarded = []` disabled globally; explicitly define fillable arrays (`protected $fillable`).
- **Authorization:** Enforce security boundaries via Laravel Policies (`php artisan make:policy`). Check permissions inside Form Requests or Controller initializations.
- **Data Protection:** Encrypt sensitive user data fields or PII at rest using Laravel’s built-in casting parameters (`encrypted`).
