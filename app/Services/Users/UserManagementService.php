<?php

namespace App\Services\Users;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

/**
 * Orchestrates user lifecycle operations by delegating to focused
 * sub-services.
 *
 * Acts as the single entry point for user create, update, delete, and
 * restore operations, keeping controllers decoupled from the underlying
 * service implementations.
 */
class UserManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param  UserCreatorService $creator Handles user creation.
     * @param  UserUpdaterService $updater Handles user updates.
     * @param  UserDeleterService $destructor Handles user deletion
     * and restoration.
     * @param  UserRestorerService $restorer Handles user restoration.
     *
     * @return void
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
     * @param StoreUserRequest $request Validated request containing user data.
     *
     * @return User The newly created user.
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
     * @param  UpdateUserRequest $request Validated request containing
     * updated user data.
     * @param  User $user The user instance to update.
     *
     * @return User The updated user.
     */
    public function update(UpdateUserRequest $request, User $user): User
    {
        $user = $this->updater->update(
            $request->validated(),
            $request->user()->id
        );

        return $user->load('roles');
    }

    /**
     * Soft-delete a user.
     *
     * Delegates to the destructor service to perform a soft-delete.
     *
     * @param  User $user The user to delete.
     *
     * @return void
     */
    public function destroy(User $user): void
    {
        $this->destructor->delete($user);
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param  int $id The primary key of the soft-deleted user.
     *
     * @return User The restored user.
     */
    public function restore(int $id): User
    {
        $user = User::withTrashed()->findOrFail($id);
        return $this->restorer->restore($user);
    }

    /**
     * Force delete a user, permanently removing it from the database.
     *
     * @param  int $id The primary key of the user to force delete.
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $user = User::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($user);
    }

    /**
     * Bulk restore users.
     *
     * @param  array $ids The IDs of the users to restore.
     * @param  User $actor The user performing the restoration, used for
     * logging.
     * @param  callable $authorizeCallback The callback to authorize each
     * user.
     *
     * @return array The IDs of the users that were restored.
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $user = User::withTrashed()->findOrFail($id);
            $authorizeCallback($user);

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
     * @param  array $ids The IDs of the users to delete.
     * @param  User $actor The user performing the deletion, used for logging.
     * @param  callable $authorizeCallback The callback to authorize each user.
     *
     * @return array The IDs of the users that were deleted.
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $user = User::findOrFail($id);
            $authorizeCallback($user);

            $this->destructor->delete($user, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
