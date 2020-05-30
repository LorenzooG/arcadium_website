<?php


namespace App\Contracts;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface Repository
{

  public function findAllEntities(): Collection;

  public function findAllEntitiesAndPaginateBy(int $currentPage, int $perPage = 15): LengthAwarePaginator;

  public function findEntityById(int $id): Model;

  public function updateEntityInTheCache(): void;

  public function removeEntityFromTheCache(): void;

  public function getCacheKey(string $fromKey): string;

}
