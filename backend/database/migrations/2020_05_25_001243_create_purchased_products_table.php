<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasedProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('purchased_products', function (Blueprint $table) {
      $table->unsignedBigInteger('payment_id');
      $table->unsignedBigInteger('product_id');

      $table->foreign('payment_id')->references('id')->on('payments');
      $table->foreign('product_id')->references('id')->on('products');

      $table->integer('amount')->default(1);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('purchased_products');
  }
}
