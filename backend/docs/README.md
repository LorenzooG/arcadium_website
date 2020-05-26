# API Documentation

1. [Auth documentation](Auth.md)
1. [Payments documentation](Payments.md)
1. [Posts documentation](Posts.md)
1. [Users documentation](Users.md)
1. [Products documentation](Products.md)

### `When unauthorized then will return`

```json
{
  "message": "Unauthorized!"
}
```

### `When bad request then will return`

```json
{
  "message": "Bad request!"
}
```

### `When not found entity then will return`

```json
{
  "message": "App\\{Entity name} not found!"
}
```
