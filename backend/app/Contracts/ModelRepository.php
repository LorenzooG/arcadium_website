<?php


namespace App\Contracts;


use Illuminate\Database\Eloquent\Model;

interface ModelRepository extends Repository
{
  public function createEntityWithAttributes(array $attributes = []): Model;
}
