# Payment API Laravel with Stripe
### Overview
This project is a Stripe-based Laravel Payment API that allows creating orders and initiating payments using two different Stripe integrations.

<details>
  <summary>Stripe 1 </summary>
    
### Features:

1- Create orders (POST /api/v1/orders)

2- Initiate Stripe payments (POST /api/v1/payments/initiate)

3- webhook (POST /api/v1/stripe/webhook)

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
#### 3. Stripe Webhook
- Endpoint: POST /api/v1/stripe/webhook
- Body type: raw JSON
```php
{
  "id": "evt_test_webhook",
  "type": "checkout.session.completed",
  "data": {
    "object": {
      "id": "cs_test_a19Tk2VEF2k4FauSofvLhIZws77Y9dDkyRcKBdGRs8KJqUJ8axPki5TlJb",
      "payment_intent": "pi_test_example123",
      "metadata": {
        "order_id": 6
      }
    }
  }
}
```
</details> 

<details>
  <summary>Stripe 2 </summary>
    
### Features:

1- Create payment intent (POST /api/v1/stripe/create-payment-intent)

2- confirm-payment (POST /api/v1/stripe/confirm-payment)

### API Endpoints

#### 1.  Create payment intent
- Endpoint: POST /api/v1/stripe/create-payment-intent
- body :
```php
{
  "order_id": 1
}
```
#### 2.  confirm-payment
- Endpoint: POST /api/v1/stripe/confirm-payment
- body :
```php
{
  "order_id": 1,
  "payment_method_id": "pm_card_visa"
}
```
- [READ](https://medium.com/@AbdullahTellawi/flutter-stripe-with-laravel-apple-pay-877a39d12a1a)

</details> 

<details>
  <summary> Project Structure </summary>
    
```
app/
├── Modules/
│   └── Payments/
│       ├── Stripe1/
│       ├── Stripe2/
├── Models/
├── Traits/
```
</details> 


### API Documentation
- [Postman Documentation](https://documenter.getpostman.com/view/50716080/2sBXVcksdT)
