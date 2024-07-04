<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AuthRequest;
use App\Models\Technic;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    public function login(AuthRequest $request): JsonResponse
    {
        try {

            $technic = Technic::query()->where('username', $request->validated('username'))->first();

            if(!$technic || !Hash::check($request->validated('password'), $technic->password)) {
                return response()->json(['message' => 'Credenciales inválidas'], 400);
            }

            $token = $technic->createToken('auth_token')->plainTextToken;


            return response()->json(['technic' => $technic,'token' => $token]);


        } catch (Exception $e) {
            return response()->json(['message' => 'Ha ocurrido un error'], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Token ha sido revocado correctamente']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Ha ocurrido un error'], 500);
        }
    }
}
