## Products API Documentation

### `Get all products`

```http request
GET api/v1/products
Accept: application/json
```

Then will response a something like this

```json
[
  {
    "id": 1,
    "name": "Product",
    "image": "http://localhost/api/products/1/image",
    "description": "**test**",
    "price": 10,
    "created_at": "2020-04-11T18:18:32.000000Z",
    "updated_at": "2020-04-11T18:18:32.000000Z"
  }
]
```

So if you send and you are administrator, the response will be like this

```json
[
  {
    "id": 1,
    "name": "Product",
    "image": "http://localhost/api/products/1/image",
    "description": "**test**",
    "command": "say test",
    "price": 10,
    "created_at": "2020-04-11T18:18:32.000000Z",
    "updated_at": "2020-04-11T18:18:32.000000Z"
  }
]
```

---

### `Get onde product`

```http request
GET api/v1/products/1
Accept: application/json
```

Then will response a something like this

```json
{
  "id": 1,
  "name": "Product",
  "image": "http://localhost/api/v1/products/1/image",
  "description": "**test**",
  "price": 10,
  "created_at": "2020-04-11T18:18:32.000000Z",
  "updated_at": "2020-04-11T18:18:32.000000Z"
}
```

So if you send and you are administrator, the response will be like this

```json
{
  "id": 1,
  "name": "Product",
  "image": "http://localhost/api/v1/products/1/image",
  "description": "**test**",
  "command": "say test",
  "price": 10,
  "created_at": "2020-04-11T18:18:32.000000Z",
  "updated_at": "2020-04-11T18:18:32.000000Z"
}
```

---

### `Create product`

```http request
POST api/v1/products
Accept: application/json
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "name": "Product",
  "command": "say test",
  "price": 10.0,
  "description": "**markdown**"
}
```

*Do not forget to upload file also*
*Needs administrator*

Then will response a something like this

```json
{
  "id": 1,
  "name": "Product",
  "image": "http://localhost/api/v1/products/1/image",
  "description": "**markdown**",
  "command": "say test",
  "price": 10,
  "created_at": "2020-04-11T18:18:32.000000Z",
  "updated_at": "2020-04-11T18:18:32.000000Z"
}
```

---

### `Update product`

```http request
PUT api/v1/products/1
Accept: application/json
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "name": "Product2",
  "command": "say test2",
  "price": 13.0,
  "description": "**markdown**"
}
```

*Needs administrator*

Then will response a something like this

```json
{
  "id": 1,
  "name": "Product2",
  "image": "http://localhost/api/v1/products/1/image",
  "description": "**markdown**",
  "command": "say test2",
  "price": 13,
  "created_at": "2020-04-11T18:18:32.000000Z",
  "updated_at": "2020-04-11T18:18:32.000000Z"
}
```

---

### `Delete product`

```http request
DELETE api/v1/products/1
Accept: application/json
Authorization: Bearer YOUR_TOKEN
```

*Needs administrator*

Then will response 204(no content) if success

