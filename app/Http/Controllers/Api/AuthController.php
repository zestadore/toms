<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $attr = $request->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'mobile' => 'required|numeric|unique:users,mobile',
            'role' => 'in:user,partner,admin',
            'password' => 'required|string|min:6|confirmed'
        ]);
        $user = User::create([
            'first_name' => $attr['first_name'],
            'last_name' => $request->last_name,
            'password' => Hash::make($attr['password']),
            'email' => $attr['email'],
            'mobile' => $attr['mobile'],
            'role' => $request->role??'user'
        ]);
        if($user){
            return $this->generateToken($user);
        }else{
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        return $this->generateToken($user);
    }

    public function logout()
    {
        $res=Auth::user()->tokens()->delete();
        if($res){
            return response()->json([
                'message' => 'successfully logged out'
            ], 200);
        }else{
            return response()->json([
                'message' => 'Failed to logout, kindly try again!'
            ], 500);
        }
    }

    private function generateToken($user)
    {
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'user_name'=>$user->first_name . ' ' . $user->last_name,
            'email'=>$user->email,
            'access_token' => 'Bearer ' . $token,
            'role' => $user->role,
        ],200);
    }
}
