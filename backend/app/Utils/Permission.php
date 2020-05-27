<?php

namespace App\Utils;

class Permission
{

  const NONE = 1;

  const STORE_USER = 2;

  const UPDATE_USER = 4;

  const UPDATE_ANY_USER = 8;

  const DELETE_USER = 16;

  const DELETE_ANY_USER = 32;

  const RESTORE_ANY_USER = 64;

  const VIEW_USER_EMAIL = 128;

  const STORE_POST = 256;

  const UPDATE_POST = 512;

  const UPDATE_ANY_POST = 1024;

  const DELETE_POST = 2048;

  const DELETE_ANY_POST = 4096;

  const ALL =
    self::NONE
    | self::STORE_USER
    | self::UPDATE_ANY_USER
    | self::UPDATE_USER
    | self::DELETE_ANY_USER
    | self::DELETE_USER
    | self::RESTORE_ANY_USER
    | self::VIEW_USER_EMAIL
    | self::STORE_POST
    | self::UPDATE_ANY_POST
    | self::UPDATE_POST
    | self::DELETE_ANY_POST
    | self::DELETE_POST;

}
