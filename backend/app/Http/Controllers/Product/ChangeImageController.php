<?php


namespace App\Http\Controllers\Product;


use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUpdateRequest;
use App\Product;
use Illuminate\Http\Response;

/**
 * Class ChangeImageController
 *
 * @package App\Http\Controllers\Product
 * @noinspection PhpUnused
 */
final class ChangeImageController extends Controller
{
  /**
   * Handle update target product's image
   *
   * @param ImageUpdateRequest $request
   * @param Product $product
   * @return Response
   */
  public function __invoke(ImageUpdateRequest $request, Product $product)
  {
    $product->saveImage($request->file('image'));

    return response()->noContent();
  }
}
