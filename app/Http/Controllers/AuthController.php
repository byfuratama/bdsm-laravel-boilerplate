<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AuthController extends Controller
{
    protected function customPayload() {
        return [
            // "payload" => "a"
        ];
    }

    protected function attemptToken($credentials) {
        $auth = auth();
        if ($this->customPayload() != []) {
            $auth = $auth->claims($this->customPayload());
        }
        return auth()->attempt($credentials);
    }

    protected function respondWithToken($token)
    {
        return jsend_success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'expires_at' => Carbon::now()->addMinutes(auth()->factory()->getTTL())
        ]);
    }

    public function login()
    {
        $credentials = request(['username', 'password']);

        if (! $token = $this->attemptToken($credentials)) {
            return jsend_success(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    
    public function me()
    {
        return jsend_success(auth()->user());
    }

    
    public function logout()
    {
        auth()->logout();

        return jsend_success(['message' => 'Successfully logged out']);
    }

    
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function payload() {
        return auth()->payload();
    }
}