<?php


namespace App\Payment\Handlers;

use App\Payment;
use App\Payment\Contracts\PaymentHandlerContract;
use App\Product;
use App\Repositories\PaymentRepository;
use App\Repositories\ProductRepository;
use App\User;
use Exception;
use Illuminate\Http\Request;
use MercadoPago\Item as MercadoPagoItem;
use MercadoPago\MerchantOrder;
use MercadoPago\Payer as MercadoPagoPayer;
use MercadoPago\Payment as MercadoPagoPayment;
use MercadoPago\Preference as MercadoPagoPreference;
use MercadoPago\SDK as MercadoPagoSDK;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MercadoPagoHandler
 *
 * MercadoPago's payment handler
 *
 * @package App\Payment\Handlers
 */
final class MercadoPagoHandler implements PaymentHandlerContract
{
  const KEY = 'MERCADO_PAGO';

  private ProductRepository $productRepository;
  private PaymentRepository $paymentRepository;

  private string $notificationUrl;

  /**
   * MercadoPagoHandler constructor
   *
   * @param PaymentRepository $paymentRepository
   * @param ProductRepository $productRepository
   */
  public final function __construct(PaymentRepository $paymentRepository, ProductRepository $productRepository)
  {
    $this->productRepository = $productRepository;
    $this->paymentRepository = $paymentRepository;

    $this->notificationUrl = route('payments.notification', [
      'paymentHandler' => self::KEY
    ]);

    $this->setupCredentials();
  }

  public function setupCredentials(): void
  {
    MercadoPagoSDK::setAccessToken(config('app.mp_access_token'));
    MercadoPagoSDK::setClientId(config('app.mp_client_id'));
    MercadoPagoSDK::setClientSecret(config('app.mp_client_secret'));
  }

  public function findMerchantOrderById($id): MerchantOrder
  {
    return MerchantOrder::find_by_id($id);
  }

  public function findItemById($id): MercadoPagoPayment
  {
    return MercadoPagoPayment::find_by_id($id);
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
  public function handleCheckout(User $user, string $userName, string $originIpAddress, array $items): Response
  {
    $totalPrice = 0;
    $mercadoPagoPreferenceItems = [];

    foreach ($items as $item) {
      $item['product'] = ($product = $this->productRepository->findProductById($item['product']));

      $totalPrice += $product->price * $item['amount'];
    }

    /** @var Payment $payment */
    $payment = $user->payments()->create([
      'payment_method' => self::KEY,
      'user_name' => $userName,
      'total_price' => $totalPrice,
      'origin_ip_address' => $originIpAddress,
    ]);

    foreach ($items as $item) {
      /** @var Product $product */
      $product = $item['product'];
      $amount = $item['amount'];

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
      'notification_url' => $this->notificationUrl
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
    // TODO: Implement handleNotification() method.
  }
}
