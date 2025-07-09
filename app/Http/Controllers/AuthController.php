<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle user registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function register(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|string|min:3|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            // Create the user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Return the user's data, excluding the password
            // makeHidden() ensures the password is not included in the JSON response
            return response()->json($user->makeHidden('password'), 201); // 201 Created
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            // Handle other potential errors during user creation
            return response()->json([
                'message' => 'User registration failed',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }
    /**
     * Handle user login and token generation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Attempt to authenticate the user
            if (!Auth::attempt($request->only('email', 'password'))) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials do not match our records.'],
                ]);
            }

            // Get the authenticated user
            $user = Auth::user();

            // Generate a new API token
            $token = $user->createToken('api_token')->plainTextToken;

            // Return user data and token
            return response()->json([
                'user' => $user->makeHidden('password'),
                'token' => $token,
                'message' => 'Logged in successfully.'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Log the user out (revoke the current token).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Ensure the user is authenticated via Sanctum's token
        if ($request->user()) {
            // Revoke the current access token
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out successfully.'], 200);
        }

        return response()->json(['message' => 'Not authenticated.'], 401);
    }
}
