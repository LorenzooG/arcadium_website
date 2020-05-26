<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Throwable;

abstract class TestCase extends BaseTestCase
{
  use CreatesApplication, RefreshDatabase, WithFaker;

  /**
   * @throws Throwable
   */
  protected function tearDown(): void
  {
    Storage::deleteDirectory("images");
    parent::tearDown();
  }
}
