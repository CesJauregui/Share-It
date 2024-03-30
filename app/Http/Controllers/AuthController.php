<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response($users, Response::HTTP_OK);
    }

    public function register(Request $request): Response
    {
        // Validación de datos
        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ];
        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], Response::HTTP_BAD_REQUEST);
        }

        // Alta del usuario (save)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // respuesta
        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], Response::HTTP_OK);
    }

    public function login(Request $request)
    {
        // Validación de datos
        $rules = [
            'email' => 'required|string|email|max:100',
            'password' => 'required|string'
        ];
        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], Response::HTTP_BAD_REQUEST);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'errors' => ['Unauthorized']
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'User logged in successfully',
            'data' => $user,
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], Response::HTTP_OK);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully',
        ], Response::HTTP_OK);
    }
}
