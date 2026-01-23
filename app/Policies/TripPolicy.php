<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TripPolicy
{
    public function create(User $user): bool
    {
        return $user->isDriver() && $user->phone_verified_at !== null;
    }

    public function update(User $user, Trip $trip): bool
    {
        return $user->isDriver() && $trip->driver_id === $user->id;
    }

    public function delete(User $user, Trip $trip): bool
    {
        return $user->isAdmin();
    }
}
