<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

final class ProductsController extends Controller
{

  public final function index()
  {
    return ProductResource::collection(Product::all());
  }

  public final function show(Product $product)
  {
    return new ProductResource($product);
  }

  public final function store(Request $request)
  {
    $content = $request->validate([
      "name" => "required|string|max:255",
      "price" => "required|numeric",
      "image" => "required|image",
      "description" => "required|string|max:6000",
      "commands" => "required|string",
    ]);

    if ($content["price"] > 10000) {
      $content["price"] = 10000;
    }

    $commands = explode(",", $content["commands"]);

    $commands = Validator::make($commands, [
      "*" => "string|max:255"
    ])->validate();

    unset($content["commands"]);

    $product = Product::create($content);

    $commands = new Collection($commands);

    $commands = $commands->map(fn($command) => [
      "command" => $command
    ]);

    $product->commands()->createMany($commands);

    return (new ProductResource($product))->response($request)->setStatusCode(201);
  }

  /**
   * @param Product $product
   * @param Request $request
   * @return Response
   * @throws Exception
   */
  public final function update(Product $product, Request $request)
  {
    $content = $request->validate([
      "name" => "string|max:255",
      "image" => "image",
      "price" => "numeric",
      "description" => "string|max:6000",
      "commands" => "string",
    ]);

    if (isset($content["price"]) && $content["price"] > 10000) {
      $content["price"] = 10000;
    }

    if (isset($content["commands"])) {
      $commands = explode(",", $content["commands"]);

      $commands = Validator::make($commands, [
        "*" => "string|max:255"
      ])->validate();

      $commands = new Collection($commands);

      $commands = $commands->map(fn($command) => [
        "command" => $command
      ]);

      $commands = $commands->toArray();

      $product->commands()->delete();
      $product->commands()->createMany($commands);
    }

    unset($content["commands"]);

    $product->update($content);

    return response()->noContent();
  }

  public final function image(Product $product)
  {
    return response()->file(storage_path("app/images/{$product->image}"));
  }

  /**
   * @param Product $product
   * @return Response
   * @throws Exception
   */
  public final function delete(Product $product)
  {
    $product->delete();

    return response()->noContent();
  }
}
