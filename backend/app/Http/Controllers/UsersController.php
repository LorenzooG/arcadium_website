<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class UsersController extends Controller
{

  private UserRepository $userRepository;
  private FilesystemAdapter $storage;

  /**
   * UsersController constructor
   *
   * @param UserRepository $userRepository
   * @param FilesystemManager $storage
   */
  public function __construct(UserRepository $userRepository, FilesystemManager $storage)
  {
    $this->userRepository = $userRepository;
    $this->storage = $storage->disk($storage->getDefaultDriver());
  }

  /**
   * Find and show all users in a page
   *
   * @return ResourceCollection
   */
  public function index()
  {
    $page = Paginator::resolveCurrentPage();

    return UserResource::collection($this->userRepository->findPaginatedUsers($page));
  }

  /**
   * Find and show an user by it's id
   *
   * @param User $user
   * @return UserResource
   */
  public function show(User $user)
  {
    return new UserResource($user);
  }

  /**
   * Find and show user's avatar
   *
   * @param User $user
   * @return BinaryFileResponse
   */
  public function image(User $user)
  {
    $imageLocation = User::AVATARS_STORAGE_KEY . '/' . $user->id;

    if (!$this->storage->exists($imageLocation)) {
      $mcHeadsUrl = config('app.mc_heads_url');
      $mcHeadsUrl = str_replace('{userName}', urlencode($user->user_name), $mcHeadsUrl);

      $imageUrl = User::AVATARS_STORAGE_KEY . '/' . $user->id;

      $client = (new Client)->get($mcHeadsUrl);

      $this->storage->put($imageUrl, $client->getBody()->getContents());
    }

    return response()->file($this->storage->url($imageLocation));
  }

  /**
   * Store user in database
   *
   * @param UserStoreRequest $request
   * @return UserResource
   */
  public function store(UserStoreRequest $request)
  {
    $data = $request->only([
      'email',
      'password',
      'name',
      'user_name'
    ]);

    $user = $this->userRepository->createUser($data);

    return new UserResource($user);
  }

  /**
   * Find and update user
   *
   * @param User $user
   * @param UserUpdateRequest $request
   * @return Response
   */
  public function update(User $user, UserUpdateRequest $request)
  {
    $user->update($request->only([
      'email',
      'password',
      'name',
      'user_name',
    ]));

    return response()->noContent();
  }

  /**
   * Find and delete user
   *
   * @param User $user
   * @return Response
   * @throws Exception
   */
  public function delete(User $user)
  {
    $user->delete();

    return response()->noContent();
  }

  /**
   * Find and restore deleted user
   *
   * @param User $user
   * @return Response
   * @throws Exception
   */
  public function restore(User $user)
  {
    $user->restore();

    return response()->noContent();
  }

  /**
   * Find and show all trashed users
   *
   * @return ResourceCollection
   */
  public function trashed()
  {
    $page = Paginator::resolveCurrentPage();

    return UserResource::collection($this->userRepository->findPaginatedTrashedUsers($page));
  }

}
