<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\AuthRequest;
use App\Mail\sendOtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Générer un OTP
        $otpCode = rand(100000, 999999); // Générer un code OTP de 6 caractères
        $expiresAt = Carbon::now()->addMinutes(5); // Expiration dans 5 minutes

        // Sauvegarder l'OTP dans la table `otps`
        Otp::create([
            'otp_code' => $otpCode,
            'user_id' => $user->id,
            'expires_at' => $expiresAt,
        ]);

        // Envoyer l'OTP par e-mail en utilisant le mailable
        Mail::to($user->email)->send(new sendOtpMail($otpCode));

        // Réponse JSON pour indiquer le succès de l'envoi
        return response()->json([
            'message' => 'OTP sent successfully. Please check your email.',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        // Vérifiez si l'utilisateur est authentifié
        if (Auth::check()) {
            // Déconnectez l'utilisateur
            Auth::logout();

            // Révoquez les tokens de l'utilisateur si vous utilisez une API avec des jetons (optionnel)
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Successfully logged out.'
            ], 200);
        }

        return response()->json([
            'message' => 'No user is authenticated.'
        ], 401);
    }
}
