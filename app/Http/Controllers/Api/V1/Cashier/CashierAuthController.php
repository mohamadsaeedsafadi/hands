<?php

namespace App\Http\Controllers\Api\V1\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashierAuthController extends Controller
{
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (!$token = auth('cashier_api')->attempt(
        $request->only('email', 'password')
    )) {

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth('cashier_api')->factory()->getTTL() * 60,
        'role'=>"cashier"
    ]);
}
}
