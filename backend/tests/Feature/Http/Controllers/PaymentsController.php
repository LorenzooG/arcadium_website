<?php

// namespace Tests\Feature;

// use App\Notifications\VipPaidNotification;
// use App\Notifications\VipPurchasedNotification;
// use App\Payment;
// use App\Product;
// use App\User;
// use App\Utils\MercadoPago;
// use Exception;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Http\UploadedFile;
// use Illuminate\Support\Facades\Notification;
// use Tests\Mocks\MercadoPagoMock;
// use Tests\TestCase;

// class PaymentsController extends TestCase
// {
//   use RefreshDatabase;

//   public function test_Should_not_show_all_payments_if_is_not_administrator()
//   {
//     $response = $this->getJson(route('payments.index'));

//     $response->assertUnauthorized()
//       ->assertJson([
//         "message" => "Unauthorized!"
//       ]);
//   }

//   /**
//    * @throws Exception
//    */
//   public function test_Should_show_all_payments_filled_array_if_is_administrator_and_db_has_a_payment()
//   {
//     factory(User::class)->create([
//       "email" => "lorenzo@gmail.com",
//       "is_admin" => true,
//       "password" => "password"
//     ]);

//     $payment = Payment::create([
//       "user_name" => "LorenzooG",
//       "origin_ip" => "127.0.0.1",
//       "user" => factory(User::class)->create([
//         "name" => "Lorenzo",
//         "email" => "lorenzo",
//         "user_name" => "LorenzooG",
//       ]),
//       "total_price" => 10.0,
//       "payment_type" => "MP",
//       "payment_response" => false,
//       "payment_raw_response" => "Not yet",
//       "delivered" => false
//     ]);

//     $payment->products()->create([
//       "product" => factory(Product::class)->create([
//         "name" => "Product",
//         "image" => UploadedFile::fake()->image("image.png"),
//         "description" => "none",
//         "price" => 10.0,
//       ]),
//       "amount" => 1
//     ]);

//     $login = $this->json("POST", "/api/v1/auth/login", [
//       "email" => "lorenzo@gmail.com",
//       "password" => "password"
//     ]);

//     $token = $login->json()["token"];

//     $response = $this->get("/api/v1/payments", [
//       "Authorization" => "Bearer $token"
//     ]);

//     $payments = $response->json();

//     $payment = (object)$payments[0];

//     $this->assertEquals(1, $payment->id);
//     $this->assertEquals("LorenzooG", $payment->user_name);

//     $user = (object)$payment->user;

//     $this->assertEquals("Lorenzo", $user->name);
//     $this->assertEquals("lorenzo", $user->email);
//     $this->assertEquals("LorenzooG", $user->user_name);

//     $this->assertEquals("MP", $payment->payment_type);
//     $this->assertEquals(false, $payment->payment_response);
//     $this->assertEquals(10.0, $payment->total_price);
//     $this->assertEquals("Not yet", $payment->payment_raw_response);
//     $this->assertEquals(false, $payment->delivered);

//     $products = $payment->products;
//     $product = (object)$products[0];

//     $this->assertEquals(1, $product->amount);

//     $product = (object)$product->product;

//     $this->assertEquals("Product", $product->name);
//     $this->assertEquals("none", $product->description);
//     $this->assertEquals(10.0, $product->price);
//     $this->assertEquals("Simple command", $product->commands[0]);
//     $this->assertEquals(url("api/v1/products/1/image"), $product->image);

//     $this->assertCount(1, $products);
//     $this->assertCount(1, $payments);

//     $response->assertOk();
//   }

//   /**
//    * @throws Exception
//    */
//   public function test_Should_not_show_a_payment_if_is_not_administrator()
//   {
//     factory(Payment::class)->create();

//     $response = $this->get("/api/v1/payments/1");

//     $response->assertUnauthorized()
//       ->assertJson([
//         "message" => "Unauthorized!"
//       ]);
//   }

//   public function test_Should_show_a_payment_set_user_default_object_if_user_do_not_exists_if_is_administrator()
//   {
//     $user = factory(User::class)->create([
//       "is_admin" => true,
//     ]);

//     $fakeUser = factory(User::class)->create();

//     $payment = Payment::create([
//       "user_name" => "LorenzooG",
//       "origin_ip" => "127.0.0.1",
//       "user" => $fakeUser,
//       "total_price" => 10.0,
//       "payment_type" => "MP",
//       "payment_response" => false,
//       "payment_raw_response" => "Not yet",
//       "delivered" => false
//     ]);

//     $payment->products()->create([
//       "product" => factory(Product::class)->create([
//         "name" => "Product",
//         "image" => UploadedFile::fake()->image("image.png"),
//         "description" => "none",
//         "price" => 10.0,
//       ]),
//       "amount" => 1
//     ]);

//     $fakeUser->delete();

//     $response = $this->actingAs($user)->get("/api/v1/payments/1");

//     $payment = (object)$response->json();

//     $this->assertEquals(1, $payment->id);
//     $this->assertEquals("LorenzooG", $payment->user_name);

//     $user = (object)$payment->user;

//     $this->assertEquals("undefined", $user->name);
//     $this->assertEquals("undefined", $user->email);
//     $this->assertEquals(false, $user->is_admin);
//     $this->assertEquals("undefined", $user->user_name);

//     $this->assertEquals("MP", $payment->payment_type);
//     $this->assertEquals(false, $payment->payment_response);
//     $this->assertEquals(10.0, $payment->total_price);
//     $this->assertEquals("Not yet", $payment->payment_raw_response);
//     $this->assertEquals(false, $payment->delivered);

//     $products = $payment->products;
//     $product = (object)$products[0];

//     $this->assertEquals(1, $product->amount);

//     $product = (object)$product->product;

//     $this->assertEquals("Product", $product->name);
//     $this->assertEquals("none", $product->description);
//     $this->assertEquals(10.0, $product->price);
//     $this->assertEquals("Simple command", $product->commands[0]);
//     $this->assertEquals(url("api/v1/products/1/image"), $product->image);

//     $this->assertCount(1, $products);

//     $response->assertOk();
//   }

//   public function test_Should_show_a_payment_if_is_administrator()
//   {
//     $user = factory(User::class)->create([
//       'is_admin' => true,
//       "email" => "lorenzo",
//       'name' => "Lorenzo",
//       'user_name' => 'LorenzooG',
//       "password" => "password"
//     ]);

//     $payment = Payment::create([
//       "user_name" => "LorenzooG",
//       "origin_ip" => "127.0.0.1",
//       "user" => $user,
//       "total_price" => 10.0,
//       "payment_type" => "MP",
//       "payment_response" => false,
//       "payment_raw_response" => "Not yet",
//       "delivered" => false,
//     ]);

//     $payment->products()->create([
//       "product" => factory(Product::class)->create([
//         "name" => "Product",
//         "image" => UploadedFile::fake()->image("image.png"),
//         "description" => "none",
//         "price" => 10.0,
//       ]),
//       "amount" => 1
//     ]);

//     $response = $this->actingAs($user)->get("/api/v1/payments/1");

//     $payment = (object)$response->json();

//     $this->assertEquals(1, $payment->id);
//     $this->assertEquals("LorenzooG", $payment->user_name);

//     $user = (object)$payment->user;

//     $this->assertEquals("Lorenzo", $user->name);
//     $this->assertEquals("lorenzo", $user->email);
//     $this->assertEquals("LorenzooG", $user->user_name);

//     $this->assertEquals("MP", $payment->payment_type);
//     $this->assertEquals(false, $payment->payment_response);
//     $this->assertEquals(10.0, $payment->total_price);
//     $this->assertEquals("Not yet", $payment->payment_raw_response);
//     $this->assertEquals(false, $payment->delivered);

//     $products = $payment->products;
//     $product = (object)$products[0];

//     $this->assertEquals(1, $product->amount);

//     $product = (object)$product->product;

//     $this->assertEquals("Product", $product->name);
//     $this->assertEquals("none", $product->description);
//     $this->assertEquals(10.0, $product->price);
//     $this->assertEquals("Simple command", $product->commands[0]);
//     $this->assertEquals(url("api/v1/products/1/image"), $product->image);

//     $this->assertCount(1, $products);

//     $response->assertOk();
//   }

//   public function test_Should_return_not_found_when_get_undefined_Payment()
//   {
//     factory(User::class)->create([
//       "email" => "lorenzo@gmail.com",
//       'is_admin' => true,
//       "password" => "password"
//     ]);

//     $login = $this->json("POST", "/api/v1/auth/login", [
//       "email" => "lorenzo@gmail.com",
//       "password" => "password"
//     ]);

//     $token = $login->json()["token"];

//     $response = $this->get("/api/v1/payments/1", [
//       "Authorization" => "Bearer $token"
//     ]);

//     $response->assertNotFound();
//   }

//   public function test_Should_return_payment_link_and_payment_id_and_send_purchased_vip_email_when_request_payment_if_is_logged_cleaning_xss_inputs()
//   {
//     Notification::fake();

//     $user = factory(User::class)->create();

//     factory(Product::class)->create();

//     $response = $this->actingAs($user)->json("POST", "/api/v1/checkout", [
//       "payment_type" => "MP",
//       "user_name" => "\"</>11234567812345678",
//       "products" => [
//         [
//           "amount" => 25,
//           "product" => 1
//         ]
//       ]
//     ]);

//     $id = $response->json()["original_id"];

//     Notification::assertSentTo($user, VipPurchasedNotification::class);

//     $this->assertDatabaseHas("payments", [
//       "id" => 1,
//       "user_name" => "&quot;&lt;/&gt;11234567812345678",
//       "user_id" => 1,
//       "origin_ip" => "127.0.0.1",
//       "payment_response" => false,
//       "payment_raw_response" => "NULL",
//       "payment_type" => "MP",
//       "delivered" => false
//     ]);

//     $this->assertDatabaseHas("purchased_products", [
//       "id" => 1,
//       "product_id" => 1,
//       "payment_id" => 1,
//       "amount" => 25
//     ]);

//     $response->assertOk()
//       ->assertJson([
//         "id" => 1,
//         "original_id" => $id,
//         "link" => "https://www.mercadopago.com.br/checkout/v1/redirect?pref_id=$id"
//       ]);
//   }

//   public function test_Should_return_unauthorized_and_do_not_send_email_when_request_payment_if_is_not_logged()
//   {
//     Notification::fake();

//     factory(Product::class)->create();

//     $response = $this->json("POST", "/api/v1/checkout", [
//       "payment_type" => "MP",
//       "user_name" => "LorenzooG",
//       "products" => [
//         [
//           "amount" => 25,
//           "product" => 1
//         ]
//       ]
//     ]);

//     Notification::assertNothingSent();

//     $this->assertDatabaseMissing("payments", [
//       "id" => 1
//     ]);

//     $this->assertDatabaseMissing("purchased_products", [
//       "id" => 1
//     ]);

//     $response->assertUnauthorized()
//       ->assertJson([
//         "message" => "Unauthorized!",
//       ]);
//   }

//   public function test_Should_do_not_checkout_when_request_payment_and_send_products_with_correct_type_in_product_product()
//   {
//     Notification::fake();

//     $user = factory(User::class)->create();

//     factory(Product::class)->create();

//     $response = $this->actingAs($user)->json("POST", "/api/v1/checkout", [
//       "user_name" => "LorenzooG",
//       "payment_type" => "MP",
//       "products" => [
//         [
//           "product" => "a",
//           "amount" => "25",
//         ]
//       ]
//     ]);

//     $this->assertDatabaseMissing("payments", [
//       "id" => 1
//     ]);

//     $this->assertDatabaseMissing("purchased_products", [
//       "id" => 1
//     ]);

//     Notification::assertNotSentTo($user, VipPurchasedNotification::class);

//     $response->assertStatus(422)
//       ->assertJson([
//         "message" => "The given data was invalid.",
//         "errors" => [
//           "products.0.product" => [
//             "The products.0.product must be a number."
//           ]
//         ]
//       ]);
//   }

//   public function test_Should_do_not_checkout_when_request_payment_and_send_products_with_correct_type_in_product_amount()
//   {
//     Notification::fake();

//     $user = factory(User::class)->create();

//     factory(Product::class)->create();

//     $response = $this->actingAs($user)->json("POST", "/api/v1/checkout", [
//       "user_name" => "LorenzooG",
//       "payment_type" => "MP",
//       "products" => [
//         [
//           "product" => 1,
//           "amount" => "ad",
//         ]
//       ]
//     ]);

//     $this->assertDatabaseMissing("payments", [
//       "id" => 1
//     ]);

//     $this->assertDatabaseMissing("purchased_products", [
//       "id" => 1
//     ]);

//     Notification::assertNotSentTo($user, VipPurchasedNotification::class);

//     $response->assertStatus(422)
//       ->assertJson([
//         "message" => "The given data was invalid.",
//         "errors" => [
//           "products.0.amount" => [
//             "The products.0.amount must be a number."
//           ]
//         ]
//       ]);
//   }

//   public function test_Should_do_not_checkout_when_checkout_and_do_not_send_payment_type_with_correct_type()
//   {
//     Notification::fake();

//     $user = factory(User::class)->create();

//     factory(Product::class)->create();

//     $response = $this->actingAs($user)->json("POST", "/api/v1/checkout", [
//       "user_name" => "LorenzooG",
//       "payment_type" => 536426,
//       "products" => [
//         [
//           "amount" => 25,
//           "product" => 1
//         ]
//       ]
//     ]);

//     $this->assertDatabaseMissing("payments", [
//       "id" => 1
//     ]);

//     $this->assertDatabaseMissing("purchased_products", [
//       "id" => 1
//     ]);

//     Notification::assertNotSentTo($user, VipPurchasedNotification::class);

//     $response->assertStatus(422)
//       ->assertJson([
//         "message" => "The given data was invalid.",
//         "errors" => [
//           "payment_type" => [
//             "The selected payment type is invalid."
//           ]
//         ]
//       ]);
//   }

//   public function test_Should_do_not_checkout_when_checkout_and_send_an_undefined_product()
//   {
//     Notification::fake();

//     $user = factory(User::class)->create();

//     $response = $this->actingAs($user)->json("POST", "/api/v1/checkout", [
//       "user_name" => "LorenzooG",
//       "payment_type" => "MP",
//       "products" => [
//         [
//           "amount" => 25,
//           "product" => 1
//         ]
//       ]
//     ]);

//     $this->assertDatabaseMissing("payments", [
//       "id" => 1
//     ]);

//     $this->assertDatabaseMissing("purchased_products", [
//       "id" => 1
//     ]);

//     Notification::assertNotSentTo($user, VipPurchasedNotification::class);

//     $response->assertStatus(404)
//       ->assertJson([
//         "message" => "App\Product not found!",
//       ]);
//   }

//   public function test_Should_return_bad_request_if_request_ipn_and_do_not_send_id()
//   {
//     factory(Payment::class)->create();

//     $token = MercadoPago::instance()->accessToken();

//     $response = $this->json("POST", "/api/v1/checkout/ipn/mp/?topic=merchant_order");

//     $this->assertDatabaseMissing("payments", [
//       "id" => 1,
//       "payment_response" => true
//     ]);

//     $response->assertStatus(400)
//       ->assertJson([
//         "message" => "Bad request!",
//       ]);
//   }

//   public function test_Should_return_bad_request_if_request_ipn_and_do_not_send_topic()
//   {
//     factory(Payment::class)->create();

//     $token = MercadoPago::instance()->accessToken();

//     $response = $this->json("POST", "/api/v1/checkout/ipn/mp/?id=35932750");

//     $this->assertDatabaseMissing("payments", [
//       "id" => 1,
//       "payment_response" => true
//     ]);

//     $response->assertStatus(400)
//       ->assertJson([
//         "message" => "Bad request!",
//       ]);
//   }

//   public function test_Should_return_bad_request_if_request_ipn_with_invalid_topic()
//   {
//     factory(Payment::class)->create();

//     $token = MercadoPago::instance()->accessToken();

//     $response = $this->json("POST", "/api/v1/checkout/ipn/mp/?topic=ass&id=35932750");

//     $this->assertDatabaseMissing("payments", [
//       "id" => 1,
//       "payment_response" => true
//     ]);

//     $response->assertStatus(400)
//       ->assertJson([
//         "message" => "Bad request!",
//       ]);
//   }

//   public function test_Should_return_bad_request_if_request_ipn_with_invalid_merchant_order_id()
//   {
//     factory(Payment::class)->create();

//     $token = MercadoPago::instance()->accessToken();

//     $response = $this->json("POST", "/api/v1/checkout/ipn/mp/?topic=merchant_order&id=35932750");

//     $this->assertDatabaseMissing("payments", [
//       "id" => 1,
//       "payment_response" => true
//     ]);

//     $response->assertStatus(400)
//       ->assertJson([
//         "message" => "Bad request!",
//       ]);
//   }

//   public function test_Should_return_bad_request_if_request_ipn_with_payment_topic_with_invalid_payment_id()
//   {
//     factory(Payment::class)->create();

//     $token = MercadoPago::instance()->accessToken();

//     $response = $this->json("POST", "/api/v1/checkout/ipn/mp/?topic=payment&id=35932750");

//     $this->assertDatabaseMissing("payments", [
//       "id" => 1,
//       "payment_response" => true
//     ]);

//     $response->assertStatus(400)
//       ->assertJson([
//         "message" => "Bad request!",
//       ]);
//   }

//   public function test_Should_change_payment_response_to_true_in_database_and_response_that_is_delivered_if_request_ipn_and_is_paid()
//   {
//     Notification::fake();

//     /**
//      * @var Payment $payment
//      */
//     $payment = factory(Payment::class)->create();
//     $user = $payment->user()->first();

//     MercadoPago::$instance = new MercadoPagoMock($payment);

//     $token = config("app.mp_access_token");

//     $response = $this->json("POST", "/api/v1/checkout/ipn/mp/?topic=merchant_order&id=32953725");

//     Notification::assertSentTo($user, VipPaidNotification::class);

//     $this->assertEquals(true, Payment::findOrFail(1)->payment_response);
//     $this->assertEquals("approved", Payment::findOrFail(1)->payment_raw_response);

//     $response->assertOk()
//       ->assertJson([
//         "message" => "Successfully delivered"
//       ]);
//   }

//   public function test_Should_change_payment_response_to_false_in_database_and_response_that_is_delivered_if_request_ipn_again_and_payed_value_is_minor_than_total()
//   {
//     Notification::fake();

//     /**
//      * @var Payment $payment
//      */
//     $payment = factory(Payment::class)->create();
//     $user = $payment->user()->first();

//     MercadoPago::$instance = new MercadoPagoMock($payment);

//     $response = $this->json("POST", "/api/v1/checkout/ipn/mp/?topic=merchant_order&id=32953725");

//     Notification::assertSentTo($user, VipPaidNotification::class);

//     $this->assertEquals(true, Payment::findOrFail(1)->payment_response);
//     $this->assertEquals("approved", Payment::findOrFail(1)->payment_raw_response);

//     $response->assertOk()
//       ->assertJson([
//         "message" => "Successfully delivered"
//       ]);

//     MercadoPago::$instance = new MercadoPagoMock($payment, [
//       "paidAmount" => 0
//     ]);

//     $response = $this->json("POST", "/api/v1/checkout/ipn/mp/?topic=merchant_order&id=32953725");

//     $this->assertDatabaseHas("payments", [
//       "id" => 1,
//       "payment_response" => false
//     ]);

//     $response->assertJson([
//       "message" => "Not delivered yet"
//     ]);
//   }

//   public function test_Should_change_payment_response_to_true_in_database_and_response_that_is_delivered_if_request_ipn_by_payment_topic_and_is_paid()
//   {
//     Notification::fake();

//     /**
//      * @var Payment $payment
//      */
//     $payment = factory(Payment::class)->create();
//     $user = $payment->user()->first();

//     MercadoPago::$instance = new MercadoPagoMock($payment);

//     $token = config("app.mp_access_token");

//     $response = $this->json("POST", "/api/v1/checkout/ipn/mp/?topic=payment&id=32953725");

//     Notification::assertSentTo($user, VipPaidNotification::class);

//     $this->assertEquals(true, Payment::findOrFail(1)->payment_response);
//     $this->assertEquals("approved", Payment::findOrFail(1)->payment_raw_response);

//     $response->assertOk()
//       ->assertJson([
//         "message" => "Successfully delivered"
//       ]);
//   }

//   public function test_Should_not_change_payment_response_to_true_in_database_and_response_that_is_not_delivered_if_request_ipn_and_is_not_paid()
//   {
//     Notification::fake();

//     /**
//      * @var Payment $payment
//      */
//     $payment = factory(Payment::class)->create();
//     $user = $payment->user()->first();

//     MercadoPago::$instance = new MercadoPagoMock($payment, [
//       "paidAmount" => 0
//     ]);

//     $token = config("app.mp_access_token");

//     $response = $this->json("POST", "/api/v1/checkout/ipn/mp/?topic=merchant_order&id=32953725");

//     Notification::assertNotSentTo($user, VipPaidNotification::class);

//     $this->assertDatabaseHas("payments", [
//       "id" => 1,
//       "payment_response" => false
//     ]);

//     $response->assertOk()
//       ->assertJson([
//         "message" => "Not delivered yet"
//       ]);
//   }

//   public function test_Should_not_change_payment_response_to_true_in_database_and_response_that_is_not_delivered_if_request_ipn_by_payment_topic_and_is_not_paid()
//   {
//     Notification::fake();

//     /**
//      * @var Payment $payment
//      */
//     $payment = factory(Payment::class)->create();
//     $user = $payment->user()->first();

//     MercadoPago::$instance = new MercadoPagoMock($payment, [
//       "paidAmount" => 0
//     ]);

//     $token = config("app.mp_access_token");

//     $response = $this->json("POST", "/api/v1/checkout/ipn/mp/?topic=payment&id=32953725");

//     $this->assertDatabaseHas("payments", [
//       "id" => 1,
//       "payment_response" => false
//     ]);

//     $response->assertOk()
//       ->assertJson([
//         "message" => "Not delivered yet"
//       ]);

//     Notification::assertNotSentTo($user, VipPaidNotification::class);
//   }

//   public function test_Should_return_404_if_request_ipn_and_items_ids_are_invalid()
//   {
//     Notification::fake();

//     /**
//      * @var Payment $payment
//      */
//     $payment = factory(Payment::class)->create();
//     $user = $payment->user()->first();

//     MercadoPago::$instance = new MercadoPagoMock($payment, [], [
//       "id" => null
//     ]);

//     $token = config("app.mp_access_token");

//     $response = $this->json("POST", "/api/v1/checkout/ipn/mp/?topic=merchant_order&id=32953725");

//     $this->assertDatabaseHas("payments", [
//       "id" => 1,
//       "payment_response" => false
//     ]);

//     Notification::assertNotSentTo($user, VipPaidNotification::class);

//     $response->assertNotFound()
//       ->assertJson([
//         "message" => "App\Payment not found!"
//       ]);
//   }

//   public function test_Should_return_404_if_request_ipn_and_items_ids_are_invalid_and_requesting_by_payment_topic()
//   {
//     Notification::fake();

//     /**
//      * @var Payment $payment
//      */
//     $payment = factory(Payment::class)->create();
//     $user = $payment->user()->first();

//     MercadoPago::$instance = new MercadoPagoMock($payment, [], [
//       "id" => null
//     ]);

//     $token = config("app.mp_access_token");

//     $response = $this->json("POST", "/api/v1/checkout/ipn/mp/?topic=payment&id=32953725");

//     $this->assertDatabaseHas("payments", [
//       "id" => 1,
//       "payment_response" => false
//     ]);

//     Notification::assertNotSentTo($user, VipPaidNotification::class);

//     $response->assertNotFound()
//       ->assertJson([
//         "message" => "App\Payment not found!"
//       ]);
//   }

// }