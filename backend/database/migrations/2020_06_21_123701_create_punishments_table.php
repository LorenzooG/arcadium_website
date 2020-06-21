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

      $table->float('punished_until');
      $table->float('punished_at');
      $table->float('punishment_duration');

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
