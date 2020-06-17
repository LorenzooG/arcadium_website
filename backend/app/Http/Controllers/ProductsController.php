<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Product;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class ProductsController extends Controller
{
  private ProductRepository $productRepository;

  /**
   * ProductsController constructor
   *
   * @param ProductRepository $productRepository
   */
  public final function __construct(ProductRepository $productRepository)
  {
    $this->productRepository = $productRepository;
  }

  /**
   * Find and show all products in a page
   *
   * @return AnonymousResourceCollection
   */
  public final function index()
  {
    $page = Paginator::resolveCurrentPage();

    return ProductResource::collection($this->productRepository->findPaginatedProducts($page));
  }

  /**
   * Find and show a product
   *
   * @param Product $product
   * @return ProductResource
   */
  public final function show(Product $product)
  {
    return new ProductResource($product);
  }

  /**
   * Store product in database
   *
   * @param ProductStoreRequest $request
   * @return ProductResource
   */
  public final function store(ProductStoreRequest $request)
  {
    $data = $request->only([
      'title',
      'price',
      'description'
    ]);

    $data['image'] = $request->file('image');

    $product = $this->productRepository->createProduct($data);

    return new ProductResource($product);
  }

  /**
   * Find and update product
   *
   * @param Product $product
   * @param ProductUpdateRequest $request
   * @return Response
   */
  public final function update(Product $product, ProductUpdateRequest $request)
  {
    $product->update($request->only([
      'title',
      'price',
      'description'
    ]));

    return response()->noContent();
  }

  /**
   * Find and show product's image
   *
   * @param Product $product
   * @return BinaryFileResponse
   */
  public final function image(Product $product)
  {
    return Storage::download('products.images/' . $product->image);
  }

  /**
   * Find and update product's image
   *
   * @param Product $product
   * @param Request $request
   * @return Response
   */
  public final function updateImage(Product $product, Request $request)
  {
    $product->update([
      'image' => $request->file('image')
    ]);

    return response()->noContent();
  }

  /**
   * Find and delete product
   *
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
