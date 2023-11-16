<?php

namespace App\Domains\Users\Controllers;

use App\Domains\Users\DB\UsersCommand;
use App\Domains\Users\Requests\UserStoreRequest;
use App\Domains\Users\Requests\UserUpdateRequest;
use App\Domains\Users\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index() //: JsonResponse
    {
        //
    }

    public function store(UserStoreRequest $request): JsonResource
    {
        $user = UsersCommand::store($request->userStoreData);

        return new UserResource($user->loadMissing('details'));
    }

    public function update(UserUpdateRequest $request, User $user): JsonResource
    {
        $user = UsersCommand::update($request->userUpdateData, $user);

        return new UserResource($user);
    }

    public function destroy(User $user) //: JsonResponse
    {
        UsersCommand::destroy($user->loadMissing('details'));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
