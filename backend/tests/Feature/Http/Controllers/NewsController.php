<?php

// namespace Tests\Feature;

// use App\Post;
// use App\User;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Tests\TestCase;

// class NewsController extends TestCase
// {
//   use RefreshDatabase;

//   /**
//    * Read
//    */
//   public function test_Should_read_0_Posts_when_DB_is_empty()
//   {
//     $response = $this->json("GET", "/api/v1/posts");

//     $users = $response->json();

//     $this->assertCount(0, $users);
//     $this->assertCount(0, Post::all());
//     $response->assertStatus(200);
//   }

//   public function test_Should_read_1_Posts_when_DB_is_not_empty()
//   {
//     factory(Post::class)->create([
//       "name" => "Post",
//       "description" => "**markdown**"
//     ]);

//     $response = $this->json("GET", "/api/v1/posts");

//     $posts = $response->json();

//     $post = (object)$posts[0];

//     $this->assertEquals("Post", $post->name);
//     $this->assertEquals("**markdown**", $post->description);

//     $this->assertEquals(1, count($posts));
//     $this->assertEquals(1, count(Post::all()));

//     $response->assertOk();
//   }

//   public function test_Should_read_1_Post()
//   {
//     factory(Post::class)->create([
//       "name" => "Post",
//       "description" => "**markdown**"
//     ]);

//     $response = $this->json("GET", "/api/v1/posts/1");

//     $response->assertOk()
//       ->assertJson([
//         "id" => 1,
//         "name" => "Post",
//         "description" => "**markdown**"
//       ]);
//   }

//   public function test_Should_return_not_found_when_get_undefined_Post()
//   {
//     $response = $this->json("GET", "/api/v1/posts/1");

//     $response->assertNotFound();
//   }

//   /**
//    * Create
//    */
//   public function test_Should_not_create_Post_if_is_not_administrator()
//   {
//     $user = factory(User::class)->create([
//       "is_admin" => false
//     ]);

//     $response = $this->actingAs($user)->json("POST", "/api/v1/posts", [
//       "name" => "Post",
//       "description" => "**markdown**"
//     ]);

//     $this->assertDatabaseMissing("posts", [
//       "id" => 1
//     ]);

//     $response->assertUnauthorized()
//       ->assertJson([
//         "message" => "Unauthorized!"
//       ]);
//   }

//   public function test_Should_create_Post_cleaning_xss_in_the_inputs()
//   {
//     $user = factory(User::class)->create([
//       'is_admin' => true,
//     ]);

//     $response = $this->actingAs($user)->json("POST", "/api/v1/posts", [
//       "name" => "<script>'¢\"\"£¥€©®&'</script>",
//       "description" => "<script>'¢\"\"£¥€©®&'</script>",
//     ]);

//     $productDB = Post::findOrFail(1);

//     $this->assertEquals("&lt;script&gt;'¢&quot;&quot;£¥€©®&amp;'&lt;/script&gt;", $productDB->name);
//     $this->assertEquals("&lt;script&gt;'¢&quot;&quot;£¥€©®&amp;'&lt;/script&gt;", $productDB->description);

//     $response->assertCreated()
//       ->assertJson([
//         "name" => "&lt;script&gt;'¢&quot;&quot;£¥€©®&amp;'&lt;/script&gt;",
//         "description" => "&lt;script&gt;'¢&quot;&quot;£¥€©®&amp;'&lt;/script&gt;",
//       ]);
//   }

//   /**
//    * Delete
//    */
//   public function test_Should_delete_Post_when_is_administrator()
//   {
//     factory(User::class)->create([
//       "email" => "lorenzo@gmail.com",
//       'is_admin' => true,
//       "password" => "password"
//     ]);

//     factory(Post::class)->create();

//     $login = $this->json("POST", "/api/v1/auth/login", [
//       "email" => "lorenzo@gmail.com",
//       "password" => "password"
//     ]);

//     $token = $login->json()["token"];

//     $response = $this->delete("/api/v1/posts/1", [], [
//       "Authorization" => "Bearer $token"
//     ]);

//     $response->assertNoContent();

//     $this->assertFalse(Post::query()->where("id", "=", 1)->exists());
//   }

//   public function test_Should_not_delete_Post_when_is_not_administrator()
//   {
//     factory(Post::class)->create();

//     $response = $this->delete("/api/v1/posts/1");

//     $this->assertTrue(Post::query()->where("id", "=", 1)->exists());

//     $response->assertUnauthorized();
//   }

//   public function test_Should_return_not_found_when_delete_undefined_Post()
//   {
//     $response = $this->delete("/api/v1/posts/1");

//     $response->assertNotFound();
//   }

//   /**
//    * Update
//    */
//   public function test_Should_update_Post_cleaning_xss_in_the_inputs()
//   {
//     $user = factory(User::class)->create([
//       'is_admin' => true,
//     ]);

//     factory(Post::class)->create();

//     $response = $this->actingAs($user)->json("PUT", "/api/v1/posts/1", [
//       "name" => "<script>'¢\"\"£¥€©®&'</script>",
//       "description" => "<script>'¢\"\"£¥€©®&'</script>",
//     ]);

//     $productDB = Post::findOrFail(1);

//     $this->assertEquals("&lt;script&gt;'¢&quot;&quot;£¥€©®&amp;'&lt;/script&gt;", $productDB->name);
//     $this->assertEquals("&lt;script&gt;'¢&quot;&quot;£¥€©®&amp;'&lt;/script&gt;", $productDB->description);

//     $response->assertNoContent();
//   }

//   public function test_Should_not_update_Post_when_is_not_administrator()
//   {
//     factory(Post::class)->create([
//       "name" => "Post",
//       "description" => "**markdown**"
//     ]);

//     $response = $this->json("PUT", "/api/v1/posts/1", [
//       "name" => "Post2",
//       "description" => "**markdown2**"
//     ]);

//     $user = Post::findOrFail(1);

//     $this->assertEquals("Post", $user->name);
//     $this->assertEquals("**markdown**", $user->description);

//     $response->assertUnauthorized();
//   }

//   public function test_Should_return_not_found_when_put_undefined_Post()
//   {
//     $response = $this->json("PUT", "/api/v1/posts/1", []);

//     $response->assertNotFound();
//   }
// }