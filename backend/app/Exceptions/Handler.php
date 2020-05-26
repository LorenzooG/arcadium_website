<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
  /**
   * A list of the exception types that are not reported.
   *
   * @var array
   */
  protected $dontReport = [
    //
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
  public function report(Throwable $exception)
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
  public function render($request, Throwable $exception)
  {
    if($exception instanceof ModelNotFoundException) {
      return response()->json([
        "message" => "{$exception->getModel()} not found!"
      ], 404);
    } else if($exception instanceof BadRequestHttpException) {
      return response()->json([
        "message" => empty($exception->getMessage()) ? "Bad request!" : $exception->getMessage()
      ], 400);
    } else if($exception instanceof UnauthorizedHttpException) {
      return response()->json([
        "message" => "Unauthorized!"
      ], 401);
    } else if($exception instanceof NotFoundHttpException && $request->acceptsJson()) {
      return response()->json([
        "message" => "Page not found!"
      ],404);
    }
    return parent::render($request, $exception);
  }
}
