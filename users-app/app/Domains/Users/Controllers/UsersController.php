<?php

namespace App\Domains\Users\Controllers;

use App\Domains\Users\DB\UsersCommand;
use App\Domains\Users\DB\UsersQuery;
use App\Domains\Users\Requests\UserIndexRequest;
use App\Domains\Users\Requests\UserStoreRequest;
use App\Domains\Users\Requests\UserUpdateRequest;
use App\Domains\Users\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index(UserIndexRequest $request): JsonResource
    {
        $userQuery = new UsersQuery($request->userIndexData);

        return UserResource::collection($userQuery->index());
    }

    public function store(UserStoreRequest $request): JsonResource
    {
        $user = UsersCommand::store($request->userStoreData);

        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user): JsonResource
    {
        $user = UsersCommand::update($request->userUpdateData, $user);

        return new UserResource($user);
    }

    public function destroy(User $user): JsonResponse
    {
        UsersCommand::destroy($user);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
