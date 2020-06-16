<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommandStoreRequest;
use App\Http\Requests\CommandUpdateRequest;
use App\Product;
use App\ProductCommand;

final class CommandsController extends Controller
{
  public final function store(Product $product, CommandStoreRequest $request)
  {
    return $product->commands()->create($request->only(['command']));
  }

  public final function update(ProductCommand $command, CommandUpdateRequest $request)
  {
    $command->update($request->only(['command']));

    return response()->noContent();
  }
}
