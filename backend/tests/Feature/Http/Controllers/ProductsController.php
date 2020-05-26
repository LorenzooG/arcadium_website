<?php

// namespace Tests\Feature;

// use App\Product;
// use App\User;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Http\UploadedFile;
// use Tests\TestCase;

// class ProductsController extends TestCase
// {
//   use RefreshDatabase;

//   /**
//    * Read
//    */
//   public function test_Should_read_0_Products_when_DB_is_empty()
//   {
//     $response = $this->get("/api/v1/products");

//     $users = $response->json();

//     $this->assertCount(0, $users);
//     $this->assertCount(0, Product::all());
//     $response->assertStatus(200);
//   }

//   public function test_Should_read_1_Products_when_DB_is_not_empty()
//   {
//     factory(Product::class)->create([
//       "name" => "Product",
//       "image" => UploadedFile::fake()->image("image.png"),
//       "description" => "**test**",
//       "price" => 10.0,
//     ]);

//     $response = $this->get("/api/v1/products");

//     $products = $response->json();

//     $product = (object)$products[0];

//     $this->assertEquals(1, $product->id);
//     $this->assertEquals("Product", $product->name);
//     $this->assertEquals(url("api/v1/products/1/image"), $product->image);
//     $this->assertEquals("**test**", $product->description);
//     $this->assertEquals(10.0, $product->price);
//     $this->assertFalse(isset($product->commands));

//     $this->assertCount(1, $products);

//     $response->assertOk();
//   }

//   public function test_Should_read_1_Products_when_DB_is_not_empty_and_show_command_when_is_administrator()
//   {
//     $user = factory(User::class)->create([
//       'is_admin' => true,
//     ]);

//     /**
//      * @var Product $product
//      */
//     factory(Product::class)->create([
//       "name" => "Product",
//       "image" => UploadedFile::fake()->image("image.png"),
//       "description" => "**test**",
//       "price" => 10.0,
//     ]);

//     $response = $this->actingAs($user)->get("/api/v1/products");

//     $products = $response->json();

//     $product = (object)$products[0];

//     $this->assertEquals(1, $product->id);
//     $this->assertEquals("Product", $product->name);
//     $this->assertEquals(url("api/v1/products/1/image"), $product->image);
//     $this->assertEquals("**test**", $product->description);
//     $this->assertEquals(10.0, $product->price);

//     $commands = $product->commands;

//     $this->assertEquals("Simple command", $commands[0]);

//     $this->assertCount(1, $commands);
//     $this->assertCount(1, $products);

//     $response->assertOk();
//   }

//   public function test_Should_read_1_Product()
//   {
//     factory(Product::class)->create([
//       "name" => "Product",
//       "image" => UploadedFile::fake()->image("image.png"),
//       "description" => "**test**",
//       "price" => 10.0,
//     ]);

//     $response = $this->get("/api/v1/products/1");

//     $response->assertOk()
//       ->assertJson([
//         "id" => 1,
//         "name" => "Product",
//         "description" => "**test**",
//         "image" => url("api/v1/products/1/image"),
//         "price" => 10.0,
//       ]);
//   }

//   public function test_Should_read_1_Product_and_show_command_and_is_administrator()
//   {
//     $user = factory(User::class)->create([
//       'is_admin' => true,
//     ]);

//     factory(Product::class)->create([
//       "name" => "Product",
//       "image" => UploadedFile::fake()->image("image.png"),
//       "description" => "**test**",
//       "price" => 10.0,
//     ]);

//     $response = $this->actingAs($user)->get("/api/v1/products/1");

//     $response->assertOk()
//       ->assertJson([
//         "id" => 1,
//         "name" => "Product",
//         "description" => "**test**",
//         "image" => url("api/v1/products/1/image"),
//         "price" => 10.0,
//         "commands" => [
//           "Simple command"
//         ]
//       ]);
//   }

//   public function test_Should_return_not_found_when_get_undefined_Product()
//   {
//     $response = $this->get("/api/v1/products/1");

//     $response->assertNotFound();
//   }

//   public function test_Should_return_product_image_if_exists()
//   {
//     factory(Product::class)->create([
//       "name" => "Product",
//       "image" => UploadedFile::fake()->image("image.png"),
//       "description" => "**test**",
//       "price" => 10.0,
//     ]);

//     $response = $this->get("/api/v1/products/1/image");

//     $response->assertOk();
//   }

//   public function test_Should_not_return_product_image_if_do_not_exists()
//   {
//     $response = $this->get("/api/v1/products/1/image");

//     $response->assertNotFound()
//       ->assertJson([
//         "message" => "App\Product not found!"
//       ]);
//   }

//   /**
//    * Create
//    */
//   public function test_Should_create_Product_if_is_administrator()
//   {
//     $user = factory(User::class)->create([
//       'is_admin' => true,
//     ]);

//     $desc = "1Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue.236867834267348267034289676468943266874224687684262648787264722878888898";
//     $command = "123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345";

//     $response = $this->actingAs($user)->json("POST", "/api/v1/products", [
//       "name" => $command,
//       "image" => UploadedFile::fake()->image("image.png"),
//       "description" => $desc,
//       "price" => 10000,
//       "commands" => $command
//     ]);

//     $this->assertDatabaseHas("products", [
//       "name" => $command,
//       "description" => $desc,
//       "price" => 10000,
//     ]);

//     $this->assertDatabaseHas("commands", [
//       "command" => $command
//     ]);

//     $response->assertCreated()
//       ->assertJson([
//         "id" => 1,
//         "name" => $command,
//         "description" => $desc,
//         "image" => url("api/v1/products/1/image"),
//         "price" => 10000,
//         "commands" => [$command]
//       ]);
//   }

//   public function test_Should_not_create_Product_if_is_not_administrator()
//   {
//     $response = $this->json("POST", "/api/v1/products", [
//       "name" => "Product",
//       "image" => UploadedFile::fake()->image("image.png"),
//       "description" => "**test**",
//       "price" => 10.0,
//       "command" => "say test"
//     ]);

//     $this->assertFalse(Product::query()->where("id", "=", 1)->exists());

//     $response->assertUnauthorized()
//       ->assertJson([
//         "message" => "Unauthorized!"
//       ]);
//   }

//   /**
//    * Delete
//    */
//   public function test_Should_delete_Product_when_is_administrator()
//   {
//     factory(User::class)->create([
//       "email" => "lorenzo@gmail.com",
//       'is_admin' => true,
//       "password" => "password"
//     ]);

//     factory(Product::class)->create();

//     $login = $this->json("POST", "/api/v1/auth/login", [
//       "email" => "lorenzo@gmail.com",
//       "password" => "password"
//     ]);

//     $token = $login->json()["token"];

//     $response = $this->delete("/api/v1/products/1", [], [
//       "Authorization" => "Bearer $token"
//     ]);

//     $response->assertNoContent();

//     $this->assertFalse(Product::query()->where("id", "=", 1)->exists());
//   }

//   public function test_Should_not_delete_Product_when_is_not_administrator()
//   {
//     factory(Product::class)->create();

//     $response = $this->delete("/api/v1/products/1");

//     $this->assertTrue(Product::query()->where("id", "=", 1)->exists());

//     $response->assertUnauthorized();
//   }

//   public function test_Should_return_not_found_when_delete_undefined_Product()
//   {
//     $response = $this->delete("/api/v1/products/1");

//     $response->assertNotFound();
//   }

//   /**
//    * Update
//    */
//   public function test_Should_update_Product_when_is_administrator()
//   {
//     $user = factory(User::class)->create([
//       'is_admin' => true,
//     ]);

//     factory(Product::class)->create();

//     $name = "111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111252532583257328";
//     $desc = "1Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue. Feugiat sed lectus vestibulum mattis ullamcorper. Turpis massa tincidunt dui ut ornare lectus sit amet. Orci phasellus egestas tellus rutrum tellus pellentesque. Justo donec enim diam vulputate ut pharetra sit. At quis risus sed vulputate odio ut enim. Eget nunc scelerisque viverra mauris in aliquam sem fringilla ut.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Natoque penatibus et magnis dis parturient montes nascetur ridiculus. Massa eget egestas purus viverra. Mauris commodo quis imperdiet massa. Amet cursus sit amet dictum sit amet justo donec enim. Faucibus turpis in eu mi bibendum neque egestas congue.236867834267348267034289676468943266874224687684262648787264722878888898";

//     $response = $this->actingAs($user)->json("PUT", "/api/v1/products/1", [
//       "name" => $name,
//       "image" => UploadedFile::fake()->image("image.png"),
//       "description" => $desc,
//       "price" => 12.0,
//       "commands" => $name
//     ]);

//     $response->assertNoContent();
//   }

//   public function test_Should_update_Product_cleaning_xss_in_the_inputs()
//   {
//     $user = factory(User::class)->create([
//       'is_admin' => true,
//     ]);

//     factory(Product::class)->create();

//     $response = $this->actingAs($user)->json("PUT", "/api/v1/products/1", [
//       "name" => "<script>'¢\"\"£¥€©®&'</script>",
//       "image" => UploadedFile::fake()->image("image.png"),
//       "description" => "<script>'¢\"\"£¥€©®&'</script>",
//       "price" => 10000,
//       "commands" => "<script>'¢\"\"£¥€©®&'</script>, <script>'¢\"\"£¥€©®&'</script>2"
//     ]);

//     $response->assertNoContent();

//     $this->assertDatabaseHas("products", [
//       "name" => "&lt;script&gt;'¢&quot;&quot;£¥€©®&amp;'&lt;/script&gt;",
//       "description" => "&lt;script&gt;'¢&quot;&quot;£¥€©®&amp;'&lt;/script&gt;",
//       "price" => 10000,
//     ]);

//     $this->assertDatabaseHas("commands", [
//       "command" => "&lt;script&gt;'¢&quot;&quot;£¥€©®&amp;'&lt;/script&gt;"
//     ]);

//     $this->assertDatabaseHas("commands", [
//       "command" => " &lt;script&gt;'¢&quot;&quot;£¥€©®&amp;'&lt;/script&gt;2"
//     ]);
//   }

//   public function test_Should_return_not_found_when_put_undefined_Product()
//   {
//     $response = $this->put("/api/v1/products/1", []);

//     $response->assertNotFound();
//   }


// }