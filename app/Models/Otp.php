<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'otp_code', 'expires_at'];

    public $timestamps = true;

    public static function cleanExpiredOtps()
    {
        self::where('expires_at', '<', Carbon::now())->delete();
    }
}
