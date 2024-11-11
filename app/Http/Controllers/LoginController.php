<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // Log l'email reçu pour le debug
        Log::info("Email reçu : " . $request->email);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        // Vérifiez le mot de passe
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Login successful.',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid password.',
            ], 401);
        }
    }
}
