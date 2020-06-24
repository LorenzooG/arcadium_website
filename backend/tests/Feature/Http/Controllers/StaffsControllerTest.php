<?php


namespace Tests\Feature\Http\Controllers;

use App\Role;
use App\User;
use Tests\TestCase;

final class StaffsControllerTest extends TestCase
{
  public function testShouldShowAllStaffs()
  {
    $role = factory(Role::class)->create([
      'is_staff' => true
    ]);

    factory(User::class, 5)->create()->each(function (User $user) use ($role) {
      $user->roles()->save($role);
    });

    $response = $this->getJson(route('staffs.index'));

    $response->assertOk()
      ->assertJson(Role::query()->where('is_staff', true)->get()->map(function (Role $role) {
        return [
          'role' => [
            'id' => $role->id,
            'title' => $role->title,
            'color' => $role->color,
            'created_at' => $role->created_at->toISOString(),
            'updated_at' => $role->updated_at->toISOString(),
          ],
          'users' => $role->users->map(function (User $user) {
            return [
              'id' => $user->id,
              'user_name' => $user->user_name,
              'name' => $user->name,
              'posts' => route('users.posts.index', [
                'user' => $user->id
              ]),
              'roles' => route('users.roles.index', [
                'user' => $user->id
              ]),
              'avatar' => route('users.avatar', [
                'user' => $user->id
              ]),
              'deleted_at' => $user->deleted_at
                ? $user->deleted_at->toISOString()
                : null,
              'created_at' => $user->created_at->toISOString(),
              'updated_at' => $user->updated_at->toISOString()
            ];
          })->toArray()
        ];
      })->toArray());
  }
}
