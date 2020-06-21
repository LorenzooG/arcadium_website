<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePunishmentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('punishments', function (Blueprint $table) {
      $table->id();

      $table->string('punished_user_name', 32);
      $table->string('punished_by', 32);

      $table->string('reason', 240);
      $table->string('proof', 240);

      $table->bigInteger('punished_until');
      $table->bigInteger('punished_at');
      $table->bigInteger('punishment_duration');

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
    Schema::dropIfExists('punishments');
  }
}
