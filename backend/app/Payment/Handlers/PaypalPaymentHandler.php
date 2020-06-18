<?php


namespace App\Payment\Handlers;

use App\Payment;
use App\Payment\Contracts\PaymentHandlerContract;
use App\Repositories\ProductRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PayPalCheckoutSdk\Core\PayPalEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PaypalPaymentHandler
 *
 * @package App\Payment\Handlers
 */
final class PaypalPaymentHandler implements PaymentHandlerContract
{
  const KEY = 'PAYPAL';

  private PayPalHttpClient $paypalClient;
  private ProductRepository $productRepository;

  public final function __construct(ProductRepository $productRepository, PayPalEnvironment $paypalEnvironment)
  {
    $this->paypalClient = new PayPalHttpClient($paypalEnvironment);
    $this->productRepository = $productRepository;
  }

  public final function handleCheckout(User $user, string $userName, string $originIpAddress, array $items): Response
  {
    $items = Collection::make($items)->map(function ($item) {
      return [
        'product' => $this->productRepository->findProductById($item['product']),
        'amount' => $item['amount']
      ];
    });

    $totalPrice = $items->reduce(function ($totalPrice, $item) {
      return $totalPrice + $item['product']->price * $item['amount'];
    }, 0);

    /** @var Payment $payment */
    $payment = $user->payments()->create([
      'payment_method' => self::KEY,
      'user_name' => $userName,
      'total_price' => $totalPrice,
      'origin_address' => $originIpAddress,
    ]);

    $paypalOrdersRequest = new OrdersCreateRequest();
    $paypalOrdersRequest->body = [
      'intent' => 'CAPTURE',
      'purchase_units' => $items->map(function ($item) use ($payment) {
        $product = $item['product'];

        $payment->products()->save($product, [
          'amount' => $item['amount']
        ]);

        return [
          'reference_id' => "{$payment->id}_{$product->id}",
          'amount' => [
            'value' => $product->price * $item['amount'],
            'currency_code' => config('app.payment_currency')
          ]
        ];
      }),
      'application_context' => [
        'cancel_url' => 'https://example.com/cancel',
        'return_url' => 'https://example.com/return',
        'user_action' => 'PAY_NOW'
      ]
    ];

    $response = $this->paypalClient->execute($paypalOrdersRequest);

    /** @var stdClass $responseContent */
    $responseContent = $response->result;

    $data = Collection::make($responseContent->links)->first(function ($linkObject) {
      return $linkObject->rel === 'approve';
    });

    return response()->json([
      'id' => $payment->id,
      'preference_id' => $responseContent->id,
      'link' => $data->href
    ]);
  }

  public final function handleNotification(Request $request): Response
  {
    // TODO: Implement handleNotification() method.
  }

  public function getNotificationUrl(): string
  {
    // TODO: Implement getNotificationUrl() method.
  }
}
