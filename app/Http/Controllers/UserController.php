<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class UserController extends Controller
{
    public function authenticate(Request $request) {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                    'error' => 'Authentication error',
                ], 400);
            }
        } catch (JWTException $th) {
            return response()->json([
                'success' => false,
                'message' => 'create token error',
                'error' => $th->getMessage(),
            ], 500);
        } 
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }  
    
    public function register(Request $request)
        {
                $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if($validator->fails()){
                    return response()->json($validator->errors(), 400);
            }

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            $token = JWTAuth::fromUser($user); 

            return response()->json(compact('user','token'),201);
        }

        //
        public function getAuthenticatedUser() {
            try {
                if (!$user = WTAuth::paseToken()->authenticate()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User not found',
                        'error' => 'Authentication error',
                    ], 404);
                }
            } catch (TokenExpiredException $expireToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token expired',
                    'error' => $expireToken->getMessage(),
                ]);
            } catch (TokenInvalidException $ivalidToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token invalid',
                    'error' => $ivalidToken->getMessage(),
                ]);
            } catch (JWTException $jwtExp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token abscent',
                    'error' => $jwtExp->getMessage(),
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Authentication user is successfully',
                'user' => $user,
            ]);
        }
}
