<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    use ApiResponse;

    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function send(Request $request)
    {
        $user = $request->user();

        if ($user->phone_verified_at) {
            return $this->error('Numéro déjà vérifié', null, 400);
        }

        $otp = $this->otpService->generate($user);

        // TEMPORAIRE : log du code (en dev)
        logger('OTP CODE', [
            'phone' => $user->phone,
            'code' => $otp->code
        ]);

        // PLUS TARD : intégration SMS provider
        if (app()->environment('local')) {
            return $this->success([
                'otp' => $otp->code
            ], 'Code OTP (mode test)');
        }else{
            return $this->success(null, 'Code OTP envoyé');
        }

        // return $this->success($otp->code, 'Code OTP envoyé');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $success = $this->otpService->verify(
            $request->user(),
            $request->code
        );

        if (!$success) {
            return $this->error('Code invalide ou expiré', null, 400);
        }

        return $this->success(null, 'Numéro vérifié avec succès');
    }
}
