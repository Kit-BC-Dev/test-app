# Ordering App API

A RESTful API for product inventory management and order processing built with Laravel.

---

## Requirements

- PHP >= 8.4
- Composer
- MySQL / SQLite

---

## Setup

```bash
git clone <repository-url>
cd ordering-app

composer install

cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`, then run:

```bash
php artisan migrate
```

---

## Running Tests

```bash
php artisan test
```

To run a specific test group:

```bash
php artisan test tests/Feature/V1/Order
php artisan test tests/Feature/V1/Product
php artisan test tests/Feature/V1/Auth
```

---

## API Reference

All endpoints are prefixed with `/api/v1`.

### Auth

| Method | Endpoint        | Description      | Auth |
|--------|-----------------|------------------|------|
| POST   | `/register`     | Register a user  | No   |
| POST   | `/login`        | Login            | No   |

### Products

| Method | Endpoint                      | Description              | Auth |
|--------|-------------------------------|--------------------------|------|
| GET    | `/product`                    | List all products        | Yes  |
| POST   | `/product`                    | Create a product         | Yes  |
| GET    | `/product/{id}`               | Get a product            | Yes  |
| PUT    | `/product/{id}`               | Update a product         | Yes  |
| DELETE | `/product/{id}`               | Delete a product         | Yes  |
| GET    | `/users/{user}/products`      | Products by user         | Yes  |

### Orders

| Method | Endpoint                                  | Description              | Auth |
|--------|-------------------------------------------|--------------------------|------|
| GET    | `/orders`                                 | List my orders           | Yes  |
| POST   | `/orders`                                 | Create an order          | Yes  |
| GET    | `/orders/{order}`                         | View an order            | Yes  |
| PATCH  | `/orders/{order}/confirm`                 | Confirm an order         | Yes  |
| PATCH  | `/orders/{order}/cancel`                  | Cancel full order        | Yes  |
| PATCH  | `/orders/{order}/items/{item}/cancel`     | Cancel a single item     | Yes  |

### Reports

| Method | Endpoint            | Description                              | Auth |
|--------|---------------------|------------------------------------------|------|
| GET    | `/reports/summary`  | Orders, revenue, and inventory overview  | Yes  |

---

## Architecture

The codebase follows a strict **layered architecture** applied consistently across every domain:

```
Request → Controller → Service → Repository → Model
```

### Design Patterns

#### Repository Pattern
All data access is abstracted behind repositories. A `BaseRepository` implements `BasicCRUDInterface` (index, show, create, update, delete), so concrete repositories only add domain-specific queries.

#### Pipeline Pattern
Complex multi-step processes are broken into isolated, single-responsibility Pipeline classes rather than fat service methods. Each step receives a payload array, does one thing, and passes it forward.

- **Order Creation:** `CreateOrder` → `AttachOrderItems`
- **Order Confirmation:** `ValidateStock` → `DeductInventory` → `CalculateTotal` → `MarkOrderConfirmed`
- **Order Cancellation:** `RestoreInventory` → `CancelOrderItems` → `RecalculateTotal`
- **User Registration:** `SaveUser` → `SaveUserInformation` → `SaveAddress`

All pipelines run inside a **database transaction** to guarantee data integrity.

#### Observer Pattern
Side effects are decoupled from business logic using Observers:
- `ProductObserver` — logs inventory changes (additions, deductions, restores) to `inventory_logs`.
- `OrderObserver` — logs order lifecycle events (created, confirmed, cancelled) to `inventory_logs`.

This gives a full, automatic activity timeline without polluting service or pipeline code.

#### Policy Pattern
Authorization is enforced via Laravel Policies:
- `OrderPolicy` — ensures users can only view, confirm, or cancel their own orders, and only in valid states (e.g. cannot confirm an already-confirmed order).
- `ProductPolicy` — guards product mutation endpoints.

### Key Technical Decisions

| Decision | Rationale |
|---|---|
| **Laravel Sanctum** | Lightweight token auth appropriate for API-only projects |
| **SoftDeletes** | Orders and products are soft-deleted to preserve history and audit integrity |
| **DB Transactions** | Every pipeline runs in a transaction — partial failures roll back completely |
| **Pipelines over fat services** | Each step is independently testable and the sequence is easy to read and extend |
| **`CarbonImmutable`** | Set globally in `AppServiceProvider` to prevent accidental date mutation |
| **Versioned routes (`/v1`)** | Allows future API versions without breaking existing clients |

