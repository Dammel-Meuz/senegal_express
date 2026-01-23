<?php

namespace App\Services;

use App\Models\PhoneOtp;
use App\Models\User;
use Carbon\Carbon;

class OtpService
{
    public function generate(User $user): PhoneOtp
    {
        // invalider anciens OTP
        PhoneOtp::where('user_id', $user->id)->delete();

        $code = random_int(100000, 999999);

        return PhoneOtp::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);
    }

    public function verify(User $user, string $code): bool
    {
        $otp = PhoneOtp::where('user_id', $user->id)->first();

        if (!$otp) {
            return false;
        }

        if ($otp->expires_at->isPast()) {
            $otp->delete();
            return false;
        }

        if ($otp->attempts >= 3) {
            $otp->delete();
            return false;
        }

        if ($otp->code !== $code) {
            $otp->increment('attempts');
            return false;
        }

        // Succès
        $otp->delete();
        $user->update([
            'phone_verified_at' => now()
        ]);

        return true;
    }
}
