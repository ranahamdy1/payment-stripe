# Payment API Laravel with Stripe
### Overview

This project is a Laravel-based Payment API that allows creating orders and initiating payments using Stripe Checkout.
It uses a clean architecture with Service and Repository layers, and standardizes JSON responses using a reusable ApiResponse trait.

### Features:

1- Create orders (POST /api/orders)

2- Initiate Stripe payments (POST /api/payments/initiate)

- Clean API responses (success, error, validationError, notFound, etc.)

- Service and Repository pattern for clean code organization.

### API Endpoints

#### 1. Create Order
- Endpoint: POST /api/v1/orders
- body :
```php
{
  "amount": 199.99,
  "currency": "USD",
  "customer_email": "customer@example.com"
}
```

#### 2. Initiate Payment
- Endpoint: POST /api/v1/payments/initiate
- body :
```php
{
  "order_id": 1
}
```
### Project Structure
```
app/
├── Modules/
│   └── Payments/
│       ├── Controllers/
│       ├── Repositories/
│       ├── Requests/
│       ├── Resources/
│       └── Services/
├── Models/
├── Traits/
```
### API Testing
Use the Postman collection to test payment APIs: [PaymentAPIs.json](postman/Payment.postman_collection.json)
