<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AdminOnly
{
  /**
   * Handle an incoming request.
   *
   * @param Request $request
   * @param Closure $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    $user = $request->user();

    if ($user !== null && $user->isAdmin) {
      return $next($request);
    }

    throw new UnauthorizedHttpException("Id");
  }
}
