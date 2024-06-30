<?php

namespace App\Http\Controllers;

use App\Traits\HandleApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;

class ProfileController extends Controller
{
    use HandleApiResponse;
    public function show()
    {
        try {
            $user = Auth::user();
            return $this->successResponse(['user' => $user],'User retrieved Successfully',200);
        }catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(),500);
        }
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $user = Auth::user();
            $validated = $request->validated();
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
            return $this->successResponse(['user' => $user], 'Profile updated successfully', 200);
        }catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(),500);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = Auth::user();
            $validated = $request->validated();
            if(!Hash::check($validated['old_password'],$user->password)){
                return $this->errorResponse('Invalid old password.', 422);
            }
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
            return $this->successResponse(null,'Password updated successfully',200);
        }catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(),500);
        }
    }
}
