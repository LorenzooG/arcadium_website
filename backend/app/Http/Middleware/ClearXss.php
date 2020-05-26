<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ClearXss
{
  public function clearXss(array $array)
  {
    foreach ($array as $key => $value) {
      if (is_string($value)) {
        $array[$key] = htmlspecialchars($value);
      } else if (is_array($value)) {
        $array[$key] = $this->clearXss($value);
      }
    }

    return $array;
  }

  /**
   * Handle an incoming request.
   *
   * @param Request $request
   * @param Closure $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $request->replace($this->clearXss($request->all()));

    return $next($request);
  }
}
