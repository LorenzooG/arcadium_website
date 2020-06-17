<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentCheckoutRequest;
use App\Http\Resources\PaymentResource;
use App\Payment;
use App\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

final class PaymentsController extends Controller
{

  /**
   * Find and show all payments
   *
   * @return ResourceCollection
   */
  public final function index()
  {
    return PaymentResource::collection(Payment::all());
  }

  /**
   * Find and show a payment
   *
   * @param Payment $payment
   * @return PaymentResource
   */
  public final function show(Payment $payment)
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
  public final function payment(PaymentCheckoutRequest $request, string $paymentHandler, PaymentService $paymentService)
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
  public final function notification(Request $request, string $paymentHandler, PaymentService $paymentService)
  {
    $paymentHandler = $paymentService->findPaymentHandlerByPaymentMethodString($paymentHandler);

    return $paymentHandler->handleNotification($request);
  }
}
