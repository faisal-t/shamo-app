<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => ['required','string','max:255'],
                'username' => ['required','string','unique:users','max:255'],
                'email' => ['required','string','email','max:255','unique:users'],
                'password' => ['required','string', new Password],
                'phone' => ['nullable','string','max:255'],
            ]);

            $data['password'] = Hash::make($request->password);

            User::create($data);
            $user = User::where('email', $request->email)->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'User Registered');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function login(Request $request)
    {
       try {
        $cradential = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if (!Auth::attempt($cradential)) {
            return ResponseFormatter::error([
                'message' => 'unauthorized',
            ], 'Autenticating Failed', 500);
        }

        $user = User::where('email', $request->email)->first();

        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return ResponseFormatter::success([
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 'Authenticated');
       } catch (Exception $error) {
        return ResponseFormatter::error([
            'message' => 'Something went wrong',
            'error' => $error
        ], 'Authentication Failed', 500);
       }




    }

    public function fecth(Request $request)
    {
        return ResponseFormatter::success($request->user(),'Data profile User Berhasil diambil');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'username' => ['required','string','unique:users','max:255'],
            'email' => ['required','string','email','max:255','unique:users'],
            'phone' => ['nullable','string','max:255'],
        ]);

        $user = Auth::user();
        $user->update($data);

        return ResponseFormatter::success($user, 'Profile Updated');


    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Token Revoked');
    }
}
