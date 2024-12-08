<?php

namespace App\Http\Controllers;

use ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $req): JsonResponse
    {
        $validator = Validator::make($req->all(),[
            'username' => 'required|min:4|max:32',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'errors' => $validator->errors()
            ],422);
        }

        $user = new User();
        $user->username = $req->username;
        $user->email = $req->email;
        $user->password = bcrypt($req->password);
        $user->company_id = $req->company_id;
        $user->save();

        return response()->json([
            'status_code' => 200,
            'message' => 'User created successfully!'
        ],201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $req): JsonResponse
    {
        $validator = Validator::make($req->all(),[
            'username' => 'required|string',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'errors' => $validator->errors()
            ],422);
        }

        $credentials = $req->only('username','password');

        if(!$token = JWTAuth::attempt($credentials))
        {
            return ApiResponse::Unauthorized(
                'Invalid credentials'
            );
        }
        return ApiResponse::JsonResult([
            'access_token' => $token
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(): JsonResponse
    {
        $user = null;//auth()->user();

        return response()->json([
            'status_code' => 200,
            'data' => $user
        ],200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        // auth()->invalid;

        return response()->json([
            'status_code' => 202,
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function refresh(): JsonResponse
    // {
    //     // return $this->responseWithToken(auth());//->refresh());
    // }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl')
        ],200);
    }
}
