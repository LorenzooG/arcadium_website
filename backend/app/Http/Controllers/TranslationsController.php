<?php


namespace App\Http\Controllers;


use Illuminate\Contracts\Foundation\Application;

/**
 * Class TranslationsController
 *
 * @package App\Http\Controllers
 * @noinspection PhpUnused
 */
final class TranslationsController
{
  /**
   * TranslationsController constructor
   *
   * @param Application $app
   */
  public function __construct(Application $app)
  {
    $this->app = $app;
  }

  /**
   *
   * Show translations for user
   * @return string
   */
  public function __invoke()
  {
    return $this->getTranslationsForLocale($this->app->getLocale());
  }

  /** @noinspection PhpIncludeInspection */
  private function getTranslationsForLocale($locale)
  {
    return require_once $this->app->resourcePath("lang/{$locale}/messages.php");
  }
}
