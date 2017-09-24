<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
  /**
   * @var \Tymon\JWTAuth\JWTAuth
   */
  protected $jwt;

  public function __construct(JWTAuth $jwt) {
    $this->jwt = $jwt;
  }

  public function postLogout(Request $r) {
     $this->jwt->setToken($r->input('token'));
    $this->jwt->invalidate();

    return response()->json([
      'token' => null
    ], 200);
  }

  public function postLogin(Request $request) {
    $this->validate($request, [
      'email'    => 'required|email|max:255',
      'password' => 'required',
    ]);

    try {

      if (! $token = $this->jwt->attempt($request->only('email', 'password'))) {
        return response()->json(['user_not_found'], 400);
      }

    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

      return response()->json(['token_expired'], 401);

    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

      return response()->json(['token_invalid'], 401);

    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

      return response()->json(['token_absent' => $e->getMessage()], 401);

    }

    return response()->json(compact('token'));
  }
}
