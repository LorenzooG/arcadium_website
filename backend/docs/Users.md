## Users API Documentation

### `Get all users`

```http request
GET api/v1/users
Accept: application/json
```

Then will response a something like this

```json
[
  {
    "id": 1,
    "name": "Lorenzo Guimarães",
    "user_name": "LorenzooG",
    "is_admin": false,
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
    "name": "Lorenzo Guimarães",
    "user_name": "LorenzooG",
    "is_admin": false,
    "email": "lorenzo@gmail.com",
    "created_at": "2020-04-11T18:18:32.000000Z",
    "updated_at": "2020-04-11T18:18:32.000000Z"
  }
]
```

---

### `Get one user`

```http request
GET api/v1/users/1
Accept: application/json
```

This will response a something like this

```json
{
  "id": 1,
  "name": "Lorenzo Guimarães",
  "user_name": "LorenzooG",
  "created_at": "2020-04-11T18:18:32.000000Z",
  "updated_at": "2020-04-11T18:18:32.000000Z"
}
```

So if you send and you are administrator or user self, the response will be like this

```json
{
  "id": 1,
  "name": "Lorenzo Guimarães",
  "user_name": "LorenzooG",
  "is_admin": false,
  "email": "lorenzo@gmail.com",
  "created_at": "2020-04-11T18:18:32.000000Z",
  "updated_at": "2020-04-11T18:18:32.000000Z"
}
```

---

### `Create user`

```http request
POST api/v1/users
Accept: application/json
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "name": "Lorenzo Guimarães",
  "user_name": "LorenzooG",
  "email": "lorenzo@gmail.com",
  "password": "new_password",
  "old_password": "password_old"
}
```

*Needs administrator*

Then will response a something like this

```json
{
  "id": 1,
  "name": "Lorenzo Guimarães",
  "user_name": "LorenzooG",
  "created_at": "2020-04-11T18:18:32.000000Z",
  "updated_at": "2020-04-11T18:18:32.000000Z"
}
```

---

### `Update user`

```http request
PUT api/v1/users/1
Accept: application/json
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "name": "Lorenzo Guimarães",
  "user_name": "LorenzooG2",
  "email": "lorenzo@gmail.com",
  "password": "new_password",
  "old_password": "old_password"
}
```

*Needs administrator or own the user*

Then will response 204(no content) if success

---

### `Delete user`

```http request
DELETE api/v1/users/1
Accept: application/json
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "old_password": "old_password"
}
```

*Needs administrator or own the user*

Then will response 204(no content) if success

