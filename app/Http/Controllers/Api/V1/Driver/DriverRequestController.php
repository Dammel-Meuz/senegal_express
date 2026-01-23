<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Models\DriverRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DriverRequestController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        $request->validate([
            'license_number' => 'required|string',
            'id_card' => 'required|file|mimes:jpg,png,pdf',
            'driver_license' => 'required|file|mimes:jpg,png,pdf',
            'insurance' => 'nullable|file|mimes:jpg,png,pdf',
        ]);

        $user = $request->user();

        if ($user->role !== 'passenger') {
            return $this->error('Action non autorisée', null, 403);
        }

        if ($user->driverRequest) {
            return $this->error('Demande déjà envoyée', null, 400);
        }

        $driverRequest = DriverRequest::create([
            'user_id' => $user->id,
            'license_number' => $request->license_number,
            'id_card_path' => $request->file('id_card')->store('drivers/id_cards'),
            'driver_license_path' => $request->file('driver_license')->store('drivers/licenses'),
            'insurance_path' => $request->file('insurance')?->store('drivers/insurance'),
        ]);

        return $this->success($driverRequest, 'Demande envoyée');
    }
}
