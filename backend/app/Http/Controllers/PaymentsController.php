<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Notifications\VipPaidNotification;
use App\Notifications\VipPurchasedNotification;
use App\Payment;
use App\Product;
use App\User;
use App\Utils\MercadoPago;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use MercadoPago\Item as MercadoPagoItem;
use MercadoPago\Payer as MercadoPagoPayer;
use MercadoPago\Preference as MercadoPagoPreference;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class PaymentsController extends Controller
{

  public function index()
  {
    return PaymentResource::collection(Payment::all());
  }

  public function show(Payment $payment)
  {
    return new PaymentResource($payment);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function ipn(Request $request)
  {
    $topic = $request->query("topic");
    $id = $request->query("id");

    if ($topic === null || $id === null) {
      throw new BadRequestHttpException();
    }

    $order = null;

    try {
      switch ($topic) {
        case "payment":
          $payment = MercadoPago::instance()->findPaymentById($id);

          $order = MercadoPago::instance()->findOrderById($payment->order->id);
          break;
        case "merchant_order":
          $order = MercadoPago::instance()->findOrderById($id);
          break;
        default:
          throw new Exception();
      }
    } catch (Throwable $exception) {
      throw new BadRequestHttpException();
    }

    $id = null;

    foreach ($order->items as $item) {
      $id = intval(Str::after($item->id, "_"));
    }

    $payment = Payment::findOrFail($id);


    if ($order->paidAmount >= $order->totalAmount) {
      $payment->payment_response = true;
      $payment->payment_raw_response = "approved";
      $payment->save();

      $email = new VipPaidNotification($payment->products()->get());
      /**
       * @var User $user
       */
      $user = $payment->user()->first();

      $user->notify($email);

      return response()->json([
        "message" => "Successfully delivered"
      ]);
    }

    $payment->payment_response = false;
    $payment->payment_raw_response = "NULL";
    $payment->save();

    return response()->json([
      "message" => "Not delivered yet"
    ]);
  }

  /**
   * @param Request $request
   * @return array
   * @throws Exception
   */
  public function checkout(Request $request)
  {

    /**
     * @var User $user
     */
    $user = $request->user();

    if ($user === null) {
      throw new UnauthorizedHttpException("");
    }

    $content = $request->validate([
      "payment_type" => [
        "required",
        Rule::in(["MP"])
      ],
      "user_name" => "required|string|max:32",
      "products" => "required|array",
      "products.*.amount" => "required|numeric",
      "products.*.product" => "required|numeric"
    ]);

    $totalPrice = 0;

    foreach ($content["products"] as $key => $product) {
      $amount = $product["amount"];

      if ($product["amount"] === 0) {
        $amount = 1;
      }

      if ($product["amount"] > 40) {
        $amount = 40;
      }

      $realProduct = Product::findOrFail($product["product"]);

      $totalPrice += $realProduct->price * $amount;

      $content["products"][$key]["product"] = $realProduct;
      $content["products"][$key]["amount"] = $amount;
    }

    $payment = $user->payments()->create([
      "delivered" => false,
      "payment_type" => $content["payment_type"],
      "user_name" => $content["user_name"],
      "total_price" => $totalPrice,
      "origin_ip" => $request->ip(),
      "payment_response" => false,
      "payment_raw_response" => "NULL"
    ]);

    $preference = new MercadoPagoPreference();

    $payer = new MercadoPagoPayer();
    $payer->name = $user->name;
    $payer->email = $user->email;

    $products = new Collection($content["products"]);

    $products = $products->map(fn($product) => [
      "product_id" => $product["product"]["id"],
      "amount" => $product["amount"]
    ]);

    $payment->products()->createMany($products);

    $mpProducts = [];

    $products = $products->map(fn($product) => (object)$product);

    foreach ($products as $product) {
      $item = new MercadoPagoItem();

      $title = config("app.payment_title");

      $title = Str::replaceArray("{amount}", [$product->amount], $title);

      $item->id = "{$product->product->id}_{$payment->id}";
      $item->title = $title;
      $item->quantity = $product->amount;
      $item->unit_price = $product->product->price;
      $item->currency_id = config("app.payment_currency");

      $mpProducts[] = $item;
    }

    $preference->items = $mpProducts;
    $preference->payer = $payer;
    $preference->notification_url = route("ipn");

    $preference->save();

    $email = new VipPurchasedNotification($payment->products()->get());

    $user->notify($email);

    return [
      "id" => $payment->id,
      "original_id" => $preference->id,
      "link" => $preference->init_point
    ];
  }
}
