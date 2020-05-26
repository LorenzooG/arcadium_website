<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('payments', function (Blueprint $table) {
      $table->id();

      $table->unsignedBigInteger('user_id');

      $table->foreign('user_id')->references('id')->on('users');

      $table->boolean('is_delivered')->default(false);
      $table->string('user_name');
      $table->integer('payment_method')->default(0);
      $table->string('origin_address', 16);
      $table->double('total_price');
      $table->double('total_paid')->default(0);

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
    Schema::dropIfExists('payments');
  }
}
