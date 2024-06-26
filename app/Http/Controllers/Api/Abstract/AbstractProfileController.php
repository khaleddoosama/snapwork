<?php

namespace App\Http\Controllers\Api\Abstract;

use App\Http\Controllers\Api\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class AbstractProfileController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {
        $this->middleware('jwt.verify');
    }

    public function __invoke(Request $request)
    {
        return $this->apiResponse(null, 'Method not allowed', 405);
    }

    protected function addUserData(Request $request, $relation, $message)
    {
        $data = $request->validated();
        $user = Auth::user();

        $user->$relation()->create($data);

        return $this->apiResponse(new ProfileResource($user), $message, 200);
    }

    protected function updateUserData(Request $request, $model, $resource, $message)
    {
        $data = $request->validated();
        $model->update($data);

        return $this->apiResponse(new $resource($model), $message, 200);
    }

    protected function deleteUserData($model, $message)
    {
        $model->delete();
        return $this->apiResponse(null, $message, 200);
    }
}
