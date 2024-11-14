<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\OtpRequest;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function verifyOtp(OtpRequest $request)
    {
        $otp = Otp::where('user_id', $request->user_id)
            ->where('otp_code', $request->otp_code)
            ->where('expires_at', '>=', Carbon::now())
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'Invalid or expired OTP'], 401);
        }

        // Si l’OTP est valide
        $user = User::find($request->user_id);
        $user->otp_verified_at = now();
        $user->save();

        // Génération du token permanent
        $token = $user->createToken('auth_token')->plainTextToken;

        // Supprimer l’OTP après validation
        $otp->delete();

        return response()->json([
            'access_token' => $token,
            'user' => $user
        ]);
    }
}
