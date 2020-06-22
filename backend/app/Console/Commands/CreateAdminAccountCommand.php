<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Throwable;

final class CreateAdminAccountCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'create:admin_account';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Creates admin account';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public final function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public final function handle()
  {
    $email = $this->ask("What is your email?");
    $name = $this->ask("What is your name?");
    $userName = $this->ask("What is your user name?");
    $password = $this->ask("What is your password?");

    try {
      User::create([
        "user_name" => $userName,
        "name" => $name,
        "email" => $email,
        "is_admin" => true,
        "password" => $password
      ]);
    } catch (Throwable $exception) {
      $this->error("This account already exists! Please check the email and the user name.");

      return true;
    }

    $this->info("Your account is successfully created!");

    return false;
  }
}
