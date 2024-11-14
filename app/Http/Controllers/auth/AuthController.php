<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\AuthRequest;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

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
        $otpCode = Str::random(6); // Générer un code OTP de 6 caractères
        $expiresAt = Carbon::now()->addMinutes(5); // Expiration dans 10 minutes

        // Sauvegarder l'OTP dans la table `otps`
        Otp::create([
            'otp_code' => $otpCode,
            'user_id' => $user->id,
            'expires_at' => $expiresAt,
        ]);

        // Envoyer la notification OTP par e-mail
        $user->notify(new SendOtpNotification($otpCode));

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'An OTP has been sent to your email.',
        ]);
    }
}
