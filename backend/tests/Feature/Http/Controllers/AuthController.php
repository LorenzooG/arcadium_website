<?php

// namespace Tests\Feature;

// use App\User;
// use Firebase\JWT\JWT;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Support\Facades\Auth;
// use Tests\TestCase;

// class AuthController extends TestCase
// {

//   use RefreshDatabase;

//   function test_Should_authenticate_and_response_with_JWT_Token_when_the_credentials_are_correct()
//   {
//     factory(User::class)->create([
//       "email" => "lorenzo@gmail.com",
//       "password" => "password"
//     ]);

//     $login = $this->post("/api/v1/auth/login", [
//       "email" => "lorenzo@gmail.com",
//       "password" => "password"
//     ]);

//     $token = $login->json()["token"];

//     $response = $this->get("/api/v1/user", [
//       "Authorization" => "Bearer {$token}"
//     ]);

//     $this->assertEquals(1, JWT::decode($token, config("auth.jwt_secret"), ["HS256"]));

//     $login->assertOk();
//     $response->assertOk();
//   }

//   function test_Should_not_authenticate_and_not_response_with_JWT_Token_when_the_credentials_are_not_correct()
//   {
//     factory(User::class)->create([
//       "email" => "lorenzo@gmail.com",
//       "password" => "password"
//     ]);

//     $response = $this->post("/api/v1/auth/login", [
//       "email" => "lorenzo@gmail.com",
//       "password" => "password2"
//     ]);

//     $this->assertFalse($this->isAuthenticated());

//     $response->assertUnauthorized();
//   }

//   function test_Should_read_current_user_data()
//   {
//     factory(User::class)->create([
//       "email" => "lorenzo@gmail.com",
//       "name" => "Lorenzo",
//       "password" => "password"
//     ]);

//     $login = $this->post("/api/v1/auth/login", [
//       "email" => "lorenzo@gmail.com",
//       "password" => "password"
//     ]);

//     $token = $login->json()["token"];

//     $response = $this->get("/api/v1/user", [
//       "Authorization" => "Bearer $token"
//     ]);

//     $this->assertEquals(1, JWT::decode($token, config("auth.jwt_secret"), ["HS256"]));

//     $login->assertOk();
//     $response->assertOk()
//       ->assertJson([
//         "email" => "lorenzo@gmail.com",
//         "name" => "Lorenzo"
//       ]);
//   }

//   function test_Should_return_null_if_not_authenticated()
//   {
//     $response = $this->get("/api/v1/user");

//     $response->assertUnauthorized()
//       ->assertJson([
//         "message" => "Unauthorized!"
//       ]);
//   }

//   function test_Should_return_bad_request_if_request_with_an_invalid_jwt_token()
//   {
//     factory(User::class)->create();

//     $response = $this->get("/api/v1/user", [
//       "Authorization" => "Bearer 532653842567486264264611614625646436y5436743674367253467436734674367534267534267"
//     ]);

//     $response->assertStatus(400)
//       ->assertJson([
//         "message" => "Bad request!"
//       ]);
//   }

//   function test_Should_throw_error_when_execute_method_once_in_Auth_facade() {
//     $this->expectException("\Exception");

//     Auth::once();
//   }

//   function test_Should_throw_error_when_execute_method_onceUsingId_in_Auth_facade() {
//     $this->expectException("\Exception");

//     Auth::onceUsingId(1);
//   }

//   function test_Should_throw_error_when_execute_method_viaRemember_in_Auth_facade() {
//     $this->expectException("\Exception");

//     Auth::viaRemember();
//   }

//   function test_Should_throw_error_when_execute_method_logout_in_Auth_facade() {
//     $this->expectException("\Exception");

//     Auth::logout();
//   }

//   function test_Should_return_true_when_execute_method_guest_in_Auth_facade_and_is_not_logged() {
//     $this->assertTrue(Auth::guest());
//   }

//   function test_Should_throw_error_when_execute_method_id_in_Auth_facade_and_is_not_logged() {
//     $this->expectException("\Exception");

//     Auth::id();
//   }

// }