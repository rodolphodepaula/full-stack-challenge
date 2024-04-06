<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserJson;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\User\UsersCollection;

class UserController extends Controller
{
    public function __construct(private UserService $srvUser){}

    public function index(UserRequest $request): UsersCollection
    {
        $filters = $request->validated();
        $usersQuery  = User::query();
        $usersQuery  = $this->srvUser->getBySearch($usersQuery, $filters);
        $users = $usersQuery->whereCompanyIsActive()->orderBy('users.name', 'ASC');
        $perPage = $request->input('per_page', 10);

        return new UsersCollection($users->paginate($perPage));
    }

    public function store(UserStoreRequest $request): UserJson
    {
        $user = $this->srvUser->save($request->validated());

        return new UserJson($user);
    }

    public function show(string $uuid): UserJson
    {
        $user = User::whereUuid($uuid)->with(['company'])->firstOrFail();

        return new UserJson($user);
    }

    public function update(UserUpdateRequest $request, string $uuid): UserJson
    {
        $user = User::whereUuid($uuid)->firstOrFail();
        $user = $this->srvUser->update($user, $request->validated());

        return new UserJson($user);

    }

    public function destroy(string $uuid): UserJson
    {
        $user = User::whereUuid($uuid)->firstOrFail();
        $this->srvUser->delete($user);

        return new UserJson($user);
    }
}
