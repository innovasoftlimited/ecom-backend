<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\AuthenticationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function __construct()
    {}

    /**
     * user register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'phone'    => 'required|string|regex:/^[0-9\-\(\)\/\+\s]*$/|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'type'      => 'user',
            'is_active' => 1,
            'password'  => bcrypt($request->password),
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->success([
            'user'  => $user,
            'token' => $token,
        ], 'User registered successfully', Response::HTTP_CREATED);
    }

    /**
     *
     * Handle user login.
     *
     * @param  AuthenticationRequest  $request
     * @return JsonResponse
     */
    public function login(AuthenticationRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (!$user->is_active) {
                return $this->error('User account is not active.', [
                    'code' => Response::HTTP_UNAUTHORIZED,
                ], Response::HTTP_UNAUTHORIZED);
            }

            return $this->success([
                "id"        => $user->id,
                "name"      => $user->name,
                "email"     => $user->email,
                "phone"     => $user->phone,
                "address"   => $user->address,
                "city"      => $user->city,
                "country"   => $user->country,
                "post_code" => $user->post_code,
                "type"      => $user->type,
                'token'     => $user->createToken(name: 'auth-token')->plainTextToken,
            ], 'User Logged In Successfully');

        }

        return $this->error('Invalid credentials.', [
            'code' => Response::HTTP_UNAUTHORIZED,
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function user()
    {
        return response()->json(['user' => Auth::user()]);
    }

}
