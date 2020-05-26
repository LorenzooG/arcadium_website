<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCommandsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('product_commands', function (Blueprint $table) {
      $table->id();

      $table->unsignedBigInteger('product_id');

      $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();

      $table->string('command', 96);

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
    Schema::dropIfExists('product_commands');
  }
}
