<?php

namespace App\Http\Controllers;

use App\ProductCommand;
use App\Http\Requests\CommandStoreRequest;
use App\Http\Requests\CommandUpdateRequest;
use App\Product;

class CommandsController extends Controller
{
  public function store(Product $product, CommandStoreRequest $request)
  {
    return $product->commands()->create($request->only(['command']));
  }

  public function update(ProductCommand $command, CommandUpdateRequest $request)
  {
    $command->update($request->only(['command']));

    return response()->noContent();
  }
}
