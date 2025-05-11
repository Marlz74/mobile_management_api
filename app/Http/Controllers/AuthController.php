<?php
    namespace App\Http\Controllers;

    use App\Helpers\ApiResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Tymon\JWTAuth\Facades\JWTAuth;
    use Tymon\JWTAuth\Exceptions\JWTException;

    class AuthController extends Controller
    {
        public function login(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error('Validation failed', 422, $validator->errors());
            }

            $credentials = $request->only('email', 'password');

            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return ApiResponse::error('Unauthorized', 401);
                }

                $user = JWTAuth::user();

                return ApiResponse::success([
                    'user' => ['email' => $user->email],
                    'authorization' => [
                        'token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => config('jwt.ttl')   
                    ],
                ], 'Login successful');
            } catch (JWTException $e) {
                return ApiResponse::error('Could not create token', 500);
            }
        }
    }