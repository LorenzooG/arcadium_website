<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentCheckoutRequest;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\PurchasedProductResource;
use App\Payment;
use App\Payment\Contracts\PaymentServiceContract as PaymentService;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Response;

final class PaymentsController extends Controller
{

  private PaymentRepository $paymentRepository;

  /**
   * PaymentsController constructor
   *
   * @param PaymentRepository $paymentRepository
   */
  public function __construct(PaymentRepository $paymentRepository)
  {
    $this->paymentRepository = $paymentRepository;
  }

  /**
   * Find and show all payments
   *
   * @return ResourceCollection
   */
  public function index()
  {
    $page = Paginator::resolveCurrentPage();

    return PaymentResource::collection($this->paymentRepository->findPaginatedPayments($page));
  }

  /**
   * Find and show paginated payment's products
   *
   * @param Payment $payment
   * @return mixed
   */
  public function products(Payment $payment)
  {
    $page = Paginator::resolveCurrentPage();

    return PurchasedProductResource::collection($this->paymentRepository->findPaginatedPaymentProducts($payment, $page));
  }

  /**
   * Find and show a payment
   *
   * @param Payment $payment
   * @return PaymentResource
   */
  public function show(Payment $payment)
  {
    return new PaymentResource($payment);
  }

  /**
   * Handles payment checkout request
   *
   * @param PaymentCheckoutRequest $request
   * @param string $paymentHandler
   * @param PaymentService $paymentService
   * @return Response
   */
  public function payment(PaymentCheckoutRequest $request, string $paymentHandler, PaymentService $paymentService)
  {
    $user = $request->user();

    $paymentHandler = $paymentService->findPaymentHandlerByPaymentMethodString($paymentHandler);

    return $paymentHandler->handleCheckout($user, $request->json('user_name'), $request->ip(), $request->json('items'));
  }

  /**
   * Handles payment notification request
   *
   * @param Request $request
   * @param string $paymentHandler
   * @param PaymentService $paymentService
   * @return Response
   */
  public function notification(Request $request, string $paymentHandler, PaymentService $paymentService)
  {
    $paymentHandler = $paymentService->findPaymentHandlerByPaymentMethodString($paymentHandler);

    return $paymentHandler->handleNotification($request);
  }
}
