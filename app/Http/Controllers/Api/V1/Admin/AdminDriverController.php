<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverRequest;
use App\Traits\ApiResponse;

class AdminDriverController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->success(
            DriverRequest::with('user')->where('status', 'pending')->get()
        );
    }

    public function approve($id)
    {
        $request = DriverRequest::findOrFail($id);

        $request->update(['status' => 'approved']);
        $request->user->update(['role' => 'driver']);

        return $this->success(null, 'Conducteur approuvé');
    }

    public function reject($id)
    {
        $request = DriverRequest::findOrFail($id);
        $request->update(['status' => 'rejected']);

        return $this->success(null, 'Demande rejetée');
    }
}
