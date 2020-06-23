<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommandStoreRequest;
use App\Http\Requests\CommandUpdateRequest;
use App\Http\Resources\ProductCommandResource;
use App\Product;
use App\ProductCommand;
use App\Repositories\ProductCommandRepository;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;

final class CommandsController extends Controller
{

  private ProductCommandRepository $productCommandRepository;

  /**
   * CommandsController constructor
   *
   * @param ProductCommandRepository $productCommandRepository
   */
  public function __construct(ProductCommandRepository $productCommandRepository)
  {
    $this->productCommandRepository = $productCommandRepository;
  }

  /**
   * Find and show all product's commands in a page
   *
   * @param Product $product
   * @return mixed
   */
  public function product(Product $product)
  {
    $page = Paginator::resolveCurrentPage();

    return ProductCommandResource::collection($this->productCommandRepository->findPaginatedProductCommandsForProduct($product, $page));
  }

  /**
   * Store product command in database
   *
   * @param Product $product
   * @param CommandStoreRequest $request
   * @return ProductCommandResource
   */
  public function store(Product $product, CommandStoreRequest $request)
  {
    $productCommand = $this->productCommandRepository->createProductCommand($product, [
      'command' => $request->get('command')
    ]);

    return new ProductCommandResource($productCommand);
  }

  /**
   * Find and update product command
   *
   * @param ProductCommand $command
   * @param CommandUpdateRequest $request
   * @return Response
   */
  public function update(ProductCommand $command, CommandUpdateRequest $request)
  {
    $command->update($request->only(['command']));

    return response()->noContent();
  }

  /**
   * Find and delete product command
   *
   * @param ProductCommand $command
   * @return Response
   * @throws Exception
   */
  public function delete(ProductCommand $command)
  {
    $command->delete();

    return response()->noContent();
  }
}
