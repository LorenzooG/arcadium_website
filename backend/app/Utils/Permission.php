<?php

namespace App\Utils;

class Permission
{

  const NONE = 1;

  const STORE_USER = 2;

  const UPDATE_USER = 4;

  const DELETE_USER = 8;

  const RESTORE_USER = 16;

  const VIEW_USER_EMAIL = 16;

  const ALL =
    self::NONE
    | self::STORE_USER
    | self::UPDATE_USER
    | self::DELETE_USER
    | self::RESTORE_USER
    | self::VIEW_USER_EMAIL;

}
