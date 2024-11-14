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

        if ($otp) {
            $user = User::find($request->user_id);
            $user->otp_verified_at = now();
            $user->save();

            // Création du token permanent
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token, 'user' => $user]);
        }

        return response()->json(['message' => 'Invalid or expired OTP'], 401);
    }
}
