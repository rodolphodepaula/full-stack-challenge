<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)
            ->WhereActive()
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas']
            ]);
        }

        $token = $user->createToken('authOnerpmToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'company_uuid' => $user->company->uuid
        ], 200);
    }

    public function singup(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $company = Company::whereUuid($request->company_uuid)->firstOrFail();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'company_id' => $company->id,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('authOnerpmToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'company_uuid' => $user->company->uuid
        ], 200);

    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Efetuado logout com êxito'], 200);
    }
}
