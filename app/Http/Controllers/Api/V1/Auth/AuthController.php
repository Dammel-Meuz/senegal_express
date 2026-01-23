<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
          $validate =Validator::make($request->all(),[
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|min:6',
        ], [
        'phone.required' => 'Le numéro de téléphone est requis.',
        'phone.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
        'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
        'password.required' => 'Le mot de passe est requis.',
        'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]
        );
    if($validate->fails()){
        return $this->error('Données invalides' , $validate->errors(), 422);
    }
     
        $user = User::create([
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('mobile')->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token
        ], 'Inscription réussie', 201);
    }

    public function login(Request $request)
    {
       $validator = Validator::make($request->all(), [
        'phone' => 'required|string',
        'password' => 'required',
    ], [
        'phone.required' => 'Le numéro de téléphone est requis.',
        'phone.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
        'password.required' => 'Le mot de passe est requis.',
    ]);

    if ($validator->fails()) {
        return $this->error('Erreur de validation', $validator->errors(), 422);
    }

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Identifiants invalides', null, 401);
        }

        if (!$user->is_active) {
            return $this->error('Compte désactivé', null, 403);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token
        ], 'Connexion réussie');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'Déconnexion réussie');
    }

    public function me(Request $request)
    {
        return $this->success($request->user());
    }
}
