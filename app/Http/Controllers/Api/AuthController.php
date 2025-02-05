<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

// use Validator;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            // return response()->json($validator->errors(), 422);
            return $this->apiResponse(null, $validator->errors()->first(), 422);
        }
        if (!$token = auth('api')->attempt($validator->validated())) {
            // return response()->json(['error' => 'Unauthorized'], 401);
            return $this->apiResponse(null, 'Unauthorized', 401);
        }
        return $this->createNewToken($token);
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'username' => 'required|string|between:2,100|alpha_dash|unique:users',
                'email' => 'required|string|email|max:100|unique:users',
                'specialization_id' => 'required|exists:specializations,id',
                'role' => 'required|in:client,freelancer',
                'job_title' => 'nullable|string|between:2,100',
                'gender' => 'required|in:male,female',
                'dob' => 'nullable|date',
                'password' => 'required|string|confirmed|min:8',
            ]);

            if ($validator->fails()) {
                return $this->apiResponse(null, $validator->errors()->first(), 400);
            }
            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            ));

            return $this->apiResponse(new UserResource($user), 'User registered successfully', 201);
        } catch (Exception $e) {
            return $this->apiResponse(null, $e->getMessage(), 400);
        }
    }


    public function logout()
    {
        auth('api')->logout();
        return $this->apiResponse(null, 'Logged out successfully', 200);
    }

    public function refresh()
    {
        return $this->createNewToken(auth('api')->refresh());
    }

    public function userProfile()
    {
        return $this->apiResponse(new ProfileResource(auth('api')->user()), 'ok', 200);
    }


    public function updateProfile(Request $request)
    {

        try {
            $user = auth('api')->user();

            $attributes = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                // 'username' => 'required|string|alpha_dash|between:2,100|unique:users,username,' . $user->id,
                'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
                'job_title' => 'nullable|string|between:2,100',
                'gender' => 'required|in:male,female',
                'phone' => 'nullable|string|between:2,100',
                'bio' => 'nullable|string|between:2,100',
                'country' => 'nullable|string|between:2,100',
                'city' => 'nullable|string|between:2,100',
                'address' => 'nullable|string|between:2,100',
                'postalcode' => 'nullable|string|between:2,100',
                'dob' => 'nullable|date',
                'specialization_id' => 'required|exists:specializations,id',
                // 'password' => 'sometimes|required|string|confirmed|min:6',
            ]);

            $user->update($attributes->validated());

            return $this->apiResponse(new ProfileResource($user), 'Profile updated successfully', 200);
        } catch (QueryException $e) {
            return $this->apiResponse(null, 'An error occurred while updating the profile: ' . $e->getMessage(), 500);
        } catch (ModelNotFoundException $e) {
            return $this->apiResponse(null, 'User not found', 404);
        } catch (Exception $e) {
            return $this->apiResponse(null, 'An error occurred while updating the profile: ' . $e->getMessage(), 500);
        }
    }


    protected function createNewToken($token)
    {
        // return response()->json([
        //     'access_token' => $token,
        //     'token_type' => 'bearer',
        //     'expires_in' => auth('api')->factory()->getTTL() * 60,
        //     'user' => new UserResource(auth('api')->user())
        // ]);

        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60, // 60 minutes * 24 hours = 1 day
            'user' => new UserResource(auth('api')->user())
        ];
        return $this->apiResponse($data, 'ok', 200);
    }
}
