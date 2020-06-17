<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentCheckoutRequest;
use App\Http\Resources\PaymentResource;
use App\Payment;
use App\Payment\Contracts\PaymentHandlerContract as PaymentHandler;
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

  public final function payment(PaymentCheckoutRequest $request, PaymentHandler $paymentHandler)
  {
    $user = $request->user();

    return $paymentHandler->handleCheckout($user, $request->json('items'));
  }

  public final function notification(Request $request, PaymentHandler $paymentHandler)
  {
    return $paymentHandler->handleNotification($request);
  }
}
