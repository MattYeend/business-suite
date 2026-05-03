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
    /** Service responsible for creating new user records.
     *
     * @var UserCreatorService
     */
    private UserCreatorService $creator;

    /** Service responsible for updating existing user records.
     *
     * @var UserUpdaterService
     */
    private UserUpdaterService $updater;

    /** Service responsible for soft-deleting and restoring user records.
     *
     * @var UserDestructorService
     */
    private UserDestructorService $destructor;

    /**
     * Inject the required services into the management service.
     *
     * @param  UserCreatorService $creator Handles user creation.
     * @param  UserUpdaterService $updater Handles user updates.
     * @param  UserDestructorService $destructor Handles user deletion
     * and restoration.
     *
     * @return void
     */
    public function __construct(
        UserCreatorService $creator,
        UserUpdaterService $updater,
        UserDestructorService $destructor,
    ) {
        $this->creator = $creator;
        $this->updater = $updater;
        $this->destructor = $destructor;
    }

    /**
     * Create a new user.
     *
     * @param StoreUserRequest Validated request containing user
     * data.
     *
     * @return User The newly created user.
     */
    public function store(StoreUserRequest $request): User
    {
        $user = $this->creator->create($request);

        return $user->load('role');
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
        $user = $this->updater->update($request, $user);

        return $user->load('role');
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
        $this->destructor->destroy($user);
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
        return $this->destructor->restore($id);
    }
}
