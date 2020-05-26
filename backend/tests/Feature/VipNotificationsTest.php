<?php

// namespace Tests\Unit;

// use App\Notifications\VipPaidNotification;
// use App\Notifications\VipPurchasedNotification;
// use App\Payment;
// use Exception;
// use Tests\TestCase;
// use Throwable;

// class VipNotificationsTest extends TestCase
// {

//   function test_Should_return_email_body_when_execute_toMail_in_VipPaidNotification()
//   {
//     /**
//      * @var Payment $payment
//      */
//     $payment = factory(Payment::class)->create();
//     $email = new VipPaidNotification($payment->products()->get());

//     /**
//      * @var User $user
//      */
//     $user = $payment->user()->first();

//     $value = true;

//     try {
//       $email->toMail($user)->render();
//     } catch (Exception $exception) {
//       $value = false;
//     }

//     $this->assertTrue($value);
//   }

//   function test_Should_return_email_body_when_execute_toMail_in_VipPurchasedNotification()
//   {
//     /**
//      * @var Payment $payment
//      */
//     $payment = factory(Payment::class)->create();
//     $email = new VipPurchasedNotification($payment->products()->get());

//     /**
//      * @var User $user
//      */
//     $user = $payment->user()->first();

//     $value = true;

//     try {
//       $email->toMail($user)->render();
//     } catch (Exception $exception) {
//       $value = false;
//     }

//     $this->assertTrue($value);
//   }

//   function test_Should_return_correct_array_when_execute_toArray_in_VipPurchasedNotification()
//   {
//     /**
//      * @var Payment $payment
//      */
//     $payment = factory(Payment::class)->create();
//     $email = new VipPurchasedNotification($payment->products()->get());

//     /**
//      * @var User $user
//      */
//     $user = $payment->user()->first();

//     $array = $email->toArray($user);

//     $this->assertEquals($payment->products()->get(), $array["products"]);
//     $this->assertEquals($payment->user()->first(), $array["user"]);
//   }

//   function test_Should_return_correct_array_when_execute_toArray_in_VipPaidNotification()
//   {
//     /**
//      * @var Payment $payment
//      */
//     $payment = factory(Payment::class)->create();
//     $email = new VipPaidNotification($payment->products()->get());

//     /**
//      * @var User $user
//      */
//     $user = $payment->user()->first();

//     $array = $email->toArray($user);

//     $this->assertEquals($payment->products()->get(), $array["products"]);
//     $this->assertEquals($payment->user()->first(), $array["user"]);
//   }

//   function test_Should_return_array_with_mail_when_execute_via_in_VipPaidNotification()
//   {
//     /**
//      * @var Payment $payment
//      */
//     $payment = factory(Payment::class)->create();
//     $email = new VipPaidNotification($payment->products()->get());

//     /**
//      * @var User $user
//      */
//     $user = $payment->user()->first();

//     $this->assertEquals(["mail"], $email->via($user));
//   }

//   function test_Should_return_array_with_mail_when_execute_via_in_VipPurchasedNotification()
//   {
//     /**
//      * @var Payment $payment
//      */
//     $payment = factory(Payment::class)->create();
//     $email = new VipPurchasedNotification($payment->products()->get());

//     /**
//      * @var User $user
//      */
//     $user = $payment->user()->first();

//     $this->assertEquals(["mail"], $email->via($user));
//   }

// }