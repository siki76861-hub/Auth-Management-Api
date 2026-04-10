<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Auth",
    description: "Authentication endpoints"
)]
class AuthController extends Controller
{
    //  Helper for success response
    private function success($message, $data = [], $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    //  Helper for error response
    private function error($message, $code = 500)
    {
        return response()->json([
            'status' => false,
            'message' => $message
        ], $code);
    }

    #[OA\Post(
        path: "/api/register",
        tags: ["Auth"],
        summary: "Register user",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email", "password"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "email", type: "string", example: "john@example.com"),
                    new OA\Property(property: "password", type: "string", example: "password123")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "User registered")
        ]
    )]
    public function register(Request $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('api-token')->plainTextToken;

            return $this->success('User registered successfully', [
                'user' => $user,
                'token' => $token
            ]);

        } catch (\Exception $e) {
            return $this->error('Registration failed');
        }
    }

    #[OA\Post(
        path: "/api/login",
        tags: ["Auth"],
        summary: "Login user",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string"),
                    new OA\Property(property: "password", type: "string")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Login success"),
            new OA\Response(response: 401, description: "Invalid credentials")
        ]
    )]
    public function login(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->error('Invalid credentials', 401);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return $this->success('Login successful', [
                'token' => $token
            ]);

        } catch (\Exception $e) {
            return $this->error('Login failed');
        }
    }

    #[OA\Post(
        path: "/api/logout",
        tags: ["Auth"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 200, description: "Logged out")
        ]
    )]
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return $this->success('Logged out successfully');

        } catch (\Exception $e) {
            return $this->error('Logout failed');
        }
    }
}
