<?php

// namespace Tests\Feature;

// use App\User;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Tests\TestCase;

// class CreateAdminAccountCommandTest extends TestCase
// {

//   use RefreshDatabase;

//   public function test_Should_create_admin_account()
//   {
//     $this->artisan("create:admin_account")
//       ->expectsQuestion("What is your email?", "lorenzo@gmail.com")
//       ->expectsQuestion("What is your name?", "Lorenzo Guimarães")
//       ->expectsQuestion("What is your user name?", "LorenzooG")
//       ->expectsQuestion("What is your password?", "password")
//       ->expectsOutput("Your account is successfully created!")
//       ->assertExitCode(0);

//     $this->assertTrue(
//       User::query()
//         ->where("email", "=", "lorenzo@gmail.com")
//         ->where("name", "=", "Lorenzo Guimarães")
//         ->where("user_name", "=", "LorenzooG")
//         ->where("is_admin", "=", true)
//         ->exists()
//     );
//   }

//   public function test_Should_not_create_admin_account_when_email_already_in_use()
//   {
//     factory(User::class)->create([
//       "email" => "lorenzo@gmail.com"
//     ]);

//     $this->artisan("create:admin_account")
//       ->expectsQuestion("What is your email?", "lorenzo@gmail.com")
//       ->expectsQuestion("What is your name?", "Lorenzo Guimarães")
//       ->expectsQuestion("What is your user name?", "LorenzooG")
//       ->expectsQuestion("What is your password?", "password")
//       ->expectsOutput("This account already exists! Please check the email and the user name.")
//       ->assertExitCode(1);

//     $this->assertFalse(
//       User::query()
//         ->where("email", "=", "lorenzo@gmail.com")
//         ->where("name", "=", "Lorenzo Guimarães")
//         ->where("user_name", "=", "LorenzooG")
//         ->where("is_admin", "=", true)
//         ->exists()
//     );
//   }

//   public function test_Should_not_create_admin_account_when_user_name_already_in_use()
//   {
//     factory(User::class)->create([
//       "user_name" => "LorenzooG"
//     ]);

//     $this->artisan("create:admin_account")
//       ->expectsQuestion("What is your email?", "lorenzo@gmail.com")
//       ->expectsQuestion("What is your name?", "Lorenzo Guimarães")
//       ->expectsQuestion("What is your user name?", "LorenzooG")
//       ->expectsQuestion("What is your password?", "password")
//       ->expectsOutput("This account already exists! Please check the email and the user name.")
//       ->assertExitCode(1);


//     $this->assertFalse(
//       User::query()
//         ->where("email", "=", "lorenzo@gmail.com")
//         ->where("name", "=", "Lorenzo Guimarães")
//         ->where("user_name", "=", "LorenzooG")
//         ->where("is_admin", "=", true)
//         ->exists()
//     );
//   }
// }