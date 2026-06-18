<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private function generateToken(): string
    {
        return hash('sha256', config('app.admin_password') . config('app.key'));
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate(['password' => 'required|string']);

        if ($request->password !== config('app.admin_password')) {
            return response()->json(['message' => 'Incorrect password'], 401);
        }

        return response()->json([
            'message' => 'Logged in successfully',
            'token'   => $this->generateToken(),
        ]);
    }

    public function logout(): JsonResponse
    {
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function checkAuth(Request $request): JsonResponse
    {
        $token = $request->header('X-Admin-Token');
        $valid = $token && hash_equals($this->generateToken(), $token);

        return response()->json(['authenticated' => $valid]);
    }
}
