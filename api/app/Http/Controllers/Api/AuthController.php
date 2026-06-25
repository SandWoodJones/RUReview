<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
        ]);

        $user = User::create($data);
        $token = $user->createToken('api')->plainTextToken;

        return $this->success(
            ['user' => $user, 'token' => $token],
            'Conta criada com sucesso',
            201
        );
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        $user = User::where('name', $data['name'])->first();

        if (!$user || !Hash::check($data['password'], $user->password))
            throw ValidationException::withMessages([
                'name' => ['As credenciais estão incorretas']
            ]);

        $token = $user->createToken('api')->plainTextToken;

        return $this->success(
            ['user' => $user, 'token' => $token],
            'Login efetuado com sucesso'
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logout efetuado com sucesso');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->success($request->user());
    }
}
