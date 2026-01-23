<?php

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    use ApiResponse;

    /**
     * Lister les véhicules du conducteur connecté
     */
    public function index(Request $request)
    {
        return $this->success(
            $request->user()->vehicles
        );
    }

    /**
     * Ajouter un véhicule
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string',
            'model' => 'required|string',
            'plate_number' => 'required|string|unique:vehicles,plate_number',
            'color' => 'required|string',
            'seats' => 'required|integer|min:1|max:9',
        ]);

        $vehicle = Vehicle::create([
            'user_id' => $request->user()->id,
            'brand' => $request->brand,
            'model' => $request->model,
            'plate_number' => $request->plate_number,
            'color' => $request->color,
            'seats' => $request->seats,
        ]);

        return $this->success($vehicle, 'Véhicule ajouté avec succès', 201);
    }
}
