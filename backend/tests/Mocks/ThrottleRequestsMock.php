<?php


namespace Tests\Mocks;


use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ThrottleRequestsMock
{
  /**
   * Handle an incoming request.
   *
   * @param Request $request
   * @param \Closure $next
   * @param int|string $maxAttempts
   * @param float|int $decayMinutes
   * @param string $prefix
   * @return Response
   *
   */
  public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
  {
    return $next($request);
  }

}
