<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailUpdatesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('email_updates', function (Blueprint $table) {
      $table->id();

      $table->unsignedBigInteger('user_id');

      $table->foreign('user_id')->references('id')->on('users');

      $table->boolean('already_used')->default(false);

      $table->string('origin_address', 16);

      $table->string('token', 64);

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('email_updates');
  }
}
