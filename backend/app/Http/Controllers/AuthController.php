<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class AuthController extends Controller
{

  public final function login(Request $request)
  {
    return response()->json([
      "token" => Auth::attempt($request->only(["email", "password"]))
    ]);
  }

}
