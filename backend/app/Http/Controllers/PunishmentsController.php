<?php

namespace App\Http\Controllers;

use App\Http\Requests\PunishmentStoreRequest;
use App\Http\Requests\PunishmentUpdateRequest;
use App\Http\Resources\PunishmentResource;
use App\Punishment;
use App\Repositories\PunishmentRepository;
use Exception;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;

final class PunishmentsController extends Controller
{

  private PunishmentRepository $punishmentRepository;

  /**
   * PunishmentsController constructor
   *
   * @param PunishmentRepository $punishmentRepository
   */
  public function __construct(PunishmentRepository $punishmentRepository)
  {
    $this->punishmentRepository = $punishmentRepository;
  }

  /**
   * Find and show all punishments
   *
   * @return ResourceCollection
   */
  public function index()
  {
    $page = Paginator::resolveCurrentPage();

    return PunishmentResource::collection($this->punishmentRepository->findPaginatedPunishments($page));
  }

  /**
   * Store a punishment in the database
   *
   * @param PunishmentStoreRequest $request
   * @return PunishmentResource
   */
  public function store(PunishmentStoreRequest $request)
  {
    $data = $request->only([
      'punished_user_name',
      'punished_at',
      'punished_until',
      'proof',
      'reason',
      'punished_by',
    ]);

    $punishment = $this->punishmentRepository->createPunishment($data);

    return new PunishmentResource($punishment);
  }

  /**
   * Find and update a punishment in the database
   *
   * @param PunishmentUpdateRequest $request
   * @param Punishment $punishment
   * @return Response
   */
  public function update(PunishmentUpdateRequest $request, Punishment $punishment)
  {
    $data = $request->only([
      'punished_user_name',
      'punished_at',
      'punished_until',
      'proof',
      'reason',
      'punished_by',
    ]);

    $punishment->update($data);

    return response()->noContent();
  }

  /**
   * Find and show a punishment
   *
   * @param Punishment $punishment
   * @return PunishmentResource
   */
  public function show(Punishment $punishment)
  {
    return new PunishmentResource($punishment);
  }

  /**
   * Find and delete a punishment
   *
   * @param Punishment $punishment
   * @return Response
   * @throws Exception
   */
  public function delete(Punishment $punishment)
  {
    $punishment->delete();

    return response()->noContent();
  }

}
