## Posts API Documentation

### `Get all posts`

```http request
GET api/v1/posts
Accept: application/json
```

Then will response a something like this

```json
[
  {
    "id": 1,
    "name": "Post title",
    "description": "**post description**",
    "created_at": "2020-04-11T18:18:32.000000Z",
    "updated_at": "2020-04-11T18:18:32.000000Z"
  }
]
```

---

### `Get one post`

```http request
GET api/v1/posts/1
Accept: application/json
```

Then will response a something like this

```json
{
  "id": 1,
  "name": "Post title",
  "description": "**post description**",
  "created_at": "2020-04-11T18:18:32.000000Z",
  "updated_at": "2020-04-11T18:18:32.000000Z"
}
```

---

### `Create post`

```http request
POST api/v1/posts
Accept: application/json
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "name": "Post title",
  "description": "**post description**"
}
```

*Do not forget to upload file also*
*Needs administrator*

Then will response a something like this

```json
{
  "id": 1,
  "name": "Post title",
  "description": "**post description**",
  "created_at": "2020-04-11T18:18:32.000000Z",
  "updated_at": "2020-04-11T18:18:32.000000Z"
}
```

---

### `Update post`

```http request
PUT api/v1/products/1
Accept: application/json
Authorization: Bearer YOUR_TOKEN
Content-Type: multipart/form-data

{
  "name": "Post title2",
  "description": "**post description2**"
}
```

*Needs administrator*

Then will response 204(no content) if success

---

### `Delete post`

```http request
DELETE api/v1/posts/1
Accept: application/json
Authorization: Bearer YOUR_TOKEN
```

*Needs administrator*

Then will response 204(no content) if success

---

### `Get all user posts`

```http request
GET api/v1/user/posts
Accept: application/json
```

**Not implemented yet**

Then will response a something like this

```json
[
  {
    "id": 1,
    "name": "Post title",
    "description": "**post description**",
    "created_at": "2020-04-11T18:18:32.000000Z",
    "updated_at": "2020-04-11T18:18:32.000000Z"
  }
]
```
