<?php


namespace App\Contracts;


use Illuminate\Database\Eloquent\Model;

interface RelationshipRepository extends Repository
{
  public function createEntityWithAttributes($owner, array $attributes = []): Model;
}
