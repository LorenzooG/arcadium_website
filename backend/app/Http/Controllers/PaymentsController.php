<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentCheckoutRequest;
use App\Http\Resources\PaymentResource;
use App\Payment;
use App\Payment\PaymentService;
use Illuminate\Http\Request;

final class PaymentsController extends Controller
{

  public final function index()
  {
    return PaymentResource::collection(Payment::all());
  }

  public final function show(Payment $payment)
  {
    return new PaymentResource($payment);
  }

  public final function payment(PaymentCheckoutRequest $request, string $paymentHandler, PaymentService $paymentService)
  {
    $user = $request->user();

    $paymentHandler = $paymentService->findPaymentHandlerByPaymentMethodString($paymentHandler);

    return $paymentHandler->handleCheckout($user, $request->json('user_name'), $request->ip(), $request->json('items'));
  }

  public final function notification(Request $request, string $paymentHandler, PaymentService $paymentService)
  {
    $paymentHandler = $paymentService->findPaymentHandlerByPaymentMethodString($paymentHandler);

    return $paymentHandler->handleNotification($request);
  }
}
