## Payments API Documentation

### `Get payments`

```http request
GET api/v1/payments
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

*Needs to be administrator*

This will response a something like this

```json
[
  {
    "id": 1,
    "payment_raw_response": "Not yet",
    "payment_response": false,
    "payment_type": "MP",
    "origin_ip": "127.0.0.1",
    "delivered": false,
    "created_at": "2020-04-11T18:02:34.000000Z",
    "updated_at": "2020-04-11T18:02:34.000000Z",
    "user_name": "LorenzooG",
    "user": {
      "id": 1,
      "name": "Lorenzo",
      "is_admin": false,
      "user_name": "LorenzooG",
      "email": "lorenzo@gmail.com",
      "email_verified_at": "2020-04-11T18:02:34.000000Z",
      "created_at": "2020-04-11T18:02:34.000000Z",
      "updated_at": "2020-04-11T18:02:34.000000Z"
    },
    "products": [
      {
        "product": {
          "id": 1,
          "name": "Product",
          "image": "http://localhost/api/products/1/image",
          "description": "none",
          "command": "say nothing",
          "price": 10,
          "created_at": "2020-04-11T18:02:34.000000Z",
          "updated_at": "2020-04-11T18:02:34.000000Z"
        },
        "amount": 1
      }
    ]
  }
]
```

---

### `Get one payment`

```http request
GET api/v1/payments/1
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

*Needs to be administrator*

This will response a something like this

```json
{
  "id": 1,
  "payment_raw_response": "Not yet",
  "payment_response": false,
  "payment_type": "MP",
  "origin_ip": "127.0.0.1",
  "delivered": false,
  "created_at": "2020-04-11T18:02:34.000000Z",
  "updated_at": "2020-04-11T18:02:34.000000Z",
  "user_name": "LorenzooG",
  "user": {
    "id": 1,
    "name": "Lorenzo",
    "email": "lorenzo@gmail.com",
    "is_admin": false,
    "user_name": "LorenzooG",
    "email_verified_at": "2020-04-11T18:02:34.000000Z",
    "created_at": "2020-04-11T18:02:34.000000Z",
    "updated_at": "2020-04-11T18:02:34.000000Z"
  },
  "products": [
    {
      "product": {
        "id": 1,
        "name": "Product",
        "image": "http://localhost/api/products/1/image",
        "description": "none",
        "command": "say nothing",
        "price": 10,
        "created_at": "2020-04-11T18:02:34.000000Z",
        "updated_at": "2020-04-11T18:02:34.000000Z"
      },
      "amount": 1
    }
  ]
}
```

---

### `Get all user payments`

```http request
GET api/v1/user/payments
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

**Not implemented yet**

*Needs to be logged in your account*

Then will response a something like this

```json
[
  {
    "id": 1,
    "payment_raw_response": "Not yet",
    "payment_response": false,
    "payment_type": "MP",
    "origin_ip": "127.0.0.1",
    "delivered": false,
    "created_at": "2020-04-11T18:02:34.000000Z",
    "updated_at": "2020-04-11T18:02:34.000000Z",
    "user_name": "LorenzooG",
    "user": {
      "id": 1,
      "name": "Lorenzo",
      "is_admin": false,
      "user_name": "LorenzooG",
      "email": "lorenzo@gmail.com",
      "email_verified_at": "2020-04-11T18:02:34.000000Z",
      "created_at": "2020-04-11T18:02:34.000000Z",
      "updated_at": "2020-04-11T18:02:34.000000Z"
    },
    "products": [
      {
        "product": {
          "id": 1,
          "name": "Product",
          "image": "http://localhost/api/products/1/image",
          "description": "none",
          "price": 10,
          "created_at": "2020-04-11T18:02:34.000000Z",
          "updated_at": "2020-04-11T18:02:34.000000Z"
        },
        "amount": 1
      }
    ]
  }
]
```

---

### `Checkout payment`

```http request
POST api/v1/user/payments
Authorization: Bearer YOUR_TOKEN
Accept: application/json
Content-Type: application/json

{
  "payment_type": "MP",
  "user_name": "LorenzooG",
  "products": [
    {
      "amount": 42,
      "product": 2
    }
  ]
}
```

*Needs to be logged in your account*

Then will response a something like this

```json
{
  "id": 1,
  "original_id": "25873-246137-216216",
  "link": "https://www.mercadopago.com.br/checkout/v1/redirect?pref_id=25873-246137-216216"
}
```
