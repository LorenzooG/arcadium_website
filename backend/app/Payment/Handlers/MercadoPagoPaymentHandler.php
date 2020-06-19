<?php


namespace App\Payment\Handlers;


use App\Payment;
use App\Payment\Contracts\PaymentHandlerContract;
use App\Payment\Contracts\PaymentRepositoryContract;
use App\Payment\Repositories\MercadoPagoPaymentRepository;
use App\Product;
use App\Repositories\PaymentRepository;
use App\Repositories\ProductRepository;
use App\User;
use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use MercadoPago\Item as MercadoPagoItem;
use MercadoPago\Payer as MercadoPagoPayer;
use MercadoPago\Preference as MercadoPagoPreference;
use MercadoPago\SDK as MercadoPagoSDK;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class MercadoPagoPaymentHandler
 *
 * @package App\Payment\Handlers
 */
final class MercadoPagoPaymentHandler implements PaymentHandlerContract
{
  const KEY = 'MERCADO_PAGO';
  const INVALID_TOPIC_MESSAGE = 'Please input a valid topic';
  const INVALID_ITEMS_MESSAGE = 'Please a merchant order that have valid items';

  private ProductRepository $productRepository;
  private PaymentRepository $paymentRepository;

  private PaymentRepositoryContract $mercadoPagoPaymentRepository;

  private string $notificationUrl;

  /**
   * MercadoPagoPaymentHandler constructor
   *
   * @param Application $app
   */
  public final function __construct(Application $app)
  {
    $this->mercadoPagoPaymentRepository = $app->make(MercadoPagoPaymentRepository::class);
    $this->productRepository = $app->make(ProductRepository::class);
    $this->paymentRepository = $app->make(PaymentRepository::class);

    $this->setupCredentials();
  }

  public final function setupCredentials(): void
  {
    $this->notificationUrl = route('payments.notification', [
      'paymentHandler' => self::KEY
    ]);

    MercadoPagoSDK::setAccessToken(config('app.mp_access_token'));
    MercadoPagoSDK::setClientId(config('app.mp_client_id'));
    MercadoPagoSDK::setClientSecret(config('app.mp_client_secret'));
  }

  /**
   * Handle payment checkout
   *
   * @param User $user
   * @param string $userName
   * @param string $originIpAddress
   * @param array $items
   * @return Response
   * @throws Exception
   */
  public final function handleCheckout(User $user, string $userName, string $originIpAddress, array $items): Response
  {
    $totalPrice = 0;
    $mercadoPagoPreferenceItems = [];

    foreach ($items as $index => $item) {
      $item['product'] = ($product = $this->productRepository->findProductById($item['product']));

      $items[$index] = $item;

      $totalPrice += $product->price * $item['amount'];
    }

    /** @var Payment $payment */
    $payment = $user->payments()->create([
      'payment_method' => MercadoPagoPaymentHandler::KEY,
      'user_name' => $userName,
      'total_price' => $totalPrice,
      'origin_address' => $originIpAddress,
    ]);

    foreach ($items as $item) {
      /** @var Product $product */
      $product = $item['product'];
      $amount = $item['amount'];

      $payment->products()->save($product, [
        'amount' => $amount
      ]);

      $mercadoPagoPreferenceItems[] = new MercadoPagoItem([
        'id' => "{$payment->id}_{$product->id}",
        'title' => $product->title,
        'quantity' => $amount,
        'unit_price' => $product->price,
        'currency_id' => config('app.payment_currency')
      ]);
    }

    $mercadoPagoPayer = new MercadoPagoPayer([
      'name' => $user->name,
      'email' => $user->email
    ]);

    $preference = new MercadoPagoPreference([
      'items' => $mercadoPagoPreferenceItems,
      'payer' => $mercadoPagoPayer,
      'notification_url' => $this->getNotificationUrl()
    ]);

    $preferenceAttributes = $preference->getAttributes();

    return response()->json([
      'id' => $payment->id,
      'preference_id' => $preferenceAttributes['id'],
      'link' => $preferenceAttributes['init_point']
    ]);
  }

  public function handleNotification(Request $request): Response
  {
    $requestedTopic = $request->query('topic');
    $requestedId = $request->query('id');

    $mercadoPagoMerchantOrder = null;

    switch ($requestedTopic) {
      case 'payment':
        $payment = $this->mercadoPagoPaymentRepository->findItemById($requestedId);

        $mercadoPagoMerchantOrder = $payment->order->id;
        break;
      case 'merchant_order':
        $mercadoPagoMerchantOrder = $this->mercadoPagoPaymentRepository->findMerchantOrderById($requestedId);
        break;
    }

    if ($mercadoPagoMerchantOrder == null) return response()->json([
      'message' => self::INVALID_TOPIC_MESSAGE
    ], 400);

    $id = isset ($mercadoPagoMerchantOrder->items[0]) ? $mercadoPagoMerchantOrder->items[0]->id : null;

    if ($id == null) return response()->json([
      'message' => self::INVALID_ITEMS_MESSAGE
    ], 400);

    $paymentId = Str::before($id, '_');

    $payment = $this->paymentRepository->findPaymentById($paymentId);
    $payment->update([
      'total_paid' => $mercadoPagoMerchantOrder->getAttributes()['paidAmount']
    ]);

    return response()->noContent();
  }

  public function getNotificationUrl(): string
  {
    return $this->notificationUrl;
  }
}
