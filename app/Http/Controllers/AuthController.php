<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Traits\HandleApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use HandleApiResponse;

    public function register(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            $token = JWTAuth::fromUser($user);
            return $this->successResponse(['user' => $user, 'token' => $token], 'User registered successfully',201);
        }catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(),500);

        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->errorResponse('Invalid email or password', 401);
            }
            $user = Auth::user();
            return $this->successResponse(['user' => $user, 'token' => $token], 'User logged in successfully',200);
        }catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(),500);
        }
    }

    public function refreshToken()
    {
        $token = JWTAuth::parseToken()->refresh();
        return $this->successResponse(['token' => $token], 'Token refreshed successfully');
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->successResponse(null, 'User logged out successfully ', 200);
        }catch (\Exception $exception){
            return $this->errorResponse(['message'=>$exception->getMessage()],500);
        }
    }
}

