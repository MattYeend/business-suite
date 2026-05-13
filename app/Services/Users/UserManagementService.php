<?php

namespace App\Services\Users;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param  UserCreatorService $creator
     * @param  UserUpdaterService $updater
     * @param  UserDeleterService $destructor
     * @param  UserRestorerService $restorer
     */
    public function __construct(
        protected UserCreatorService $creator,
        protected UserUpdaterService $updater,
        protected UserDeleterService $destructor,
        protected UserRestorerService $restorer,
    ) {
    }

    /**
     * Create a new user.
     *
     * @param StoreUserRequest $request
     *
     * @return User
     */
    public function store(StoreUserRequest $request): User
    {
        $user = $this->creator->create(
            $request->validated(),
            $request->user()->id
        );

        return $user->load('roles');
    }

    /**
     * Update an existing user.
     *
     * @param  UpdateUserRequest $request
     * @param  User $user
     *
     * @return User
     */
    public function update(UpdateUserRequest $request, User $user): User
    {
        $user = $this->updater->update(
            $user,
            $request->validated(),
            $request->user()->id
        );

        return $user->load('roles');
    }

    /**
     * Soft-delete a user.
     *
     * @param  User $user
     *
     * @return void
     */
    public function destroy(User $user): void
    {
        $this->destructor->delete($user, auth()->id());
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param  int $id
     *
     * @return User
     */
    public function restore(int $id): User
    {
        $user = User::withTrashed()->findOrFail($id);
        return $this->restorer->restore($user, auth()->id());
    }

    /**
     * Force delete a user, permanently removing it from the database.
     *
     * @param  int $id
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $user = User::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($user, auth()->id());
    }

    /**
     * Bulk restore users.
     *
     * @param  array $ids
     * @param  User $actor
     * @param  callable $authoriseCallback
     *
     * @return array
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authoriseCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $user = User::withTrashed()->findOrFail($id);
            $authoriseCallback($user);

            if ($user->trashed()) {
                $this->restorer->restore($user, $actor->id);
                $restored[] = $id;
            }
        }

        return $restored;
    }

    /**
     * Bulk delete users.
     *
     * @param  array $ids
     * @param  User $actor
     * @param  callable $authoriseCallback
     *
     * @return array
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authoriseCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $user = User::findOrFail($id);
            $authoriseCallback($user);

            $this->destructor->delete($user, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
