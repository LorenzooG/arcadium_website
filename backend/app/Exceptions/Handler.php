<?php

namespace App\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

final class Handler extends ExceptionHandler
{
  /**
   * A list of the exception types that are not reported.
   *
   * @var array
   */
  protected $dontReport = [
  ];

  /**
   * A list of the inputs that are never flashed for validation exceptions.
   *
   * @var array
   */
  protected $dontFlash = [
    'password',
    'password_confirmation',
  ];

  /**
   * Report or log an exception.
   *
   * @param Throwable $exception
   * @return void
   *
   * @throws Exception
   */
  public final function report(Throwable $exception)
  {
    parent::report($exception);
  }

  /**
   * Render an exception into an HTTP response.
   *
   * @param Request $request
   * @param Throwable $exception
   * @return Response
   *
   * @throws Throwable
   */
  public final function render($request, Throwable $exception)
  {
    if ($request->acceptsJson()) {
      if ($exception instanceof ModelNotFoundException) {
        return response()->json([
          'message' => 'Model not found!',
          'model' => $exception->getModel(),
        ], 404);
      } else if ($exception instanceof AccessDeniedHttpException) {
        return response()->json([
          'message' => "You do not have permission to make this!",
        ], 403);
      } else if ($exception instanceof NotFoundHttpException) {
        return response()->json([
          'message' => 'Page not found!'
        ], 404);
      }
    }

    if ($exception instanceof ClientException) {
      return response()->json([
        'message' => $exception->getMessage(),
        'code' => $exception->getCode()
      ], 500);
    }

    return parent::render($request, $exception);
  }
}
