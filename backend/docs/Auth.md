## Auth API Documentation

### `Login user`

```http request
POST api/v1/auth/login
Accept: application/json
Content-Type: application/json

{
  "email": "lorenzo@gmail.com",
  "password": "password"
}
```

Then will return this if successfully

```json
{
  "token": "5y342795ty342t9870342yt84gh487tg4j4.hg3hy5uh0956433hj95648h5h54.hj564j564j564j564j564h853h8"
}
```
