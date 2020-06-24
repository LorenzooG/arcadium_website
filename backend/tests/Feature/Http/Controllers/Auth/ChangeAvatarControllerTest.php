<?php


namespace Tests\Feature\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class ChangeAvatarControllerTest extends TestCase
{
  public function testShouldChangeCurrentUserAvatar()
  {
    Storage::fake();

    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    $newAvatar = UploadedFile::fake()->image('image.png');

    $response = $this->actingAs($user)->postJson(route('user.update.avatar'), [
      'image' => $newAvatar
    ]);

    Storage::assertExists(User::AVATARS_STORAGE_KEY . '/' . $user->id);

    $response->assertNoContent();
  }

  public function testShouldChangeTargetUserAvatar()
  {
    Storage::fake();

    /** @var User $user */
    $user = factory(User::class)->state('admin')->create();

    $newAvatar = UploadedFile::fake()->image('image.png');

    $response = $this->actingAs($user)->postJson(route('users.update.avatar', [
      'user' => $user->id
    ]), [
      'image' => $newAvatar
    ]);

    Storage::assertExists(User::AVATARS_STORAGE_KEY . '/' . $user->id);

    $response->assertNoContent();
  }
}
