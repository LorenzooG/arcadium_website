<?php

namespace App\Utils;

/**
 * Class Permission
 *
 * Permission handler
 *
 * @package App\Utils
 */
final class Permission
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

  const LIKE_POST = 8192;

  const VIEW_ROLE = 16384;

  const VIEW_ANY_ROLE = 32768;

  const STORE_ROLE = 65536;

  const UPDATE_ROLE = 131072;

  const DELETE_ROLE = 262144;

  const ATTACH_ROLE_TO_USER = 524288;

  const DETACH_ROLE_TO_USER = 1048576;

  const VIEW_SELF_ROLES = 2097152;

  const VIEW_ROLES_PERMISSIONS = 4194304;

  const DELETE_COMMENT = 8388608;

  const DELETE_ANY_COMMENT = 16777216;

  const STORE_COMMENT = 33554432;

  const UPDATE_COMMENT = 67108864;

  const UPDATE_ANY_COMMENT = 134217728;

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
    | self::DELETE_POST
    | self::LIKE_POST
    | self::VIEW_ROLE
    | self::VIEW_ANY_ROLE
    | self::STORE_ROLE
    | self::UPDATE_ROLE
    | self::DELETE_ROLE
    | self::ATTACH_ROLE_TO_USER
    | self::DETACH_ROLE_TO_USER
    | self::VIEW_SELF_ROLES
    | self::VIEW_ROLES_PERMISSIONS
    | self::STORE_COMMENT
    | self::UPDATE_ANY_COMMENT
    | self::UPDATE_COMMENT
    | self::DELETE_ANY_COMMENT
    | self::DELETE_COMMENT;

}
