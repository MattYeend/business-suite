<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserUpdaterService
{
    public function __construct(
        protected UserAvatarHandlerService $avatarHandler,
        protected UserRoleAssignmentService $roleAssignment,
        protected UserDataPreparationService $dataPreparation,
        protected UserLogService $logService
    ) {
    }

    /**
     * Update an existing user.
     *
     * @param  User $user
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return User
     *
     * @throws \Exception
     */
    public function update(
        User $user,
        array $data,
        ?int $updatedBy = null
    ): User {
        return DB::transaction(function () use ($user, $data, $updatedBy) {
            $actor = User::findOrFail($updatedBy);

            $this->updateUserData($user, $data, $updatedBy);
            $this->handleAvatarUpdate($user, $data);
            $this->handleRolesUpdate($user, $data);
            $this->logService->logUpdate($user, $actor);

            return $user->fresh();
        });
    }

    /**
     * Update user data.
     *
     * @param  User $user
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return void
     */
    protected function updateUserData(
        User $user,
        array $data,
        ?int $updatedBy
    ): void {
        $fillableData = $this->dataPreparation->prepareForUpdate(
            $data,
            $updatedBy
        );
        $user->fill($fillableData);
        $user->save();
    }

    /**
     * Handle avatar update if provided.
     *
     * @param  User $user
     * @param  array $data
     *
     * @return void
     */
    protected function handleAvatarUpdate(User $user, array $data): void
    {
        if (! array_key_exists('avatar', $data)) {
            return;
        }

        $this->avatarHandler->handleUpdate($user, $data['avatar']);
    }

    /**
     * Handle roles update if provided.
     *
     * @param  User $user
     * @param  array $data
     *
     * @return void
     */
    protected function handleRolesUpdate(User $user, array $data): void
    {
        if (! isset($data['roles']) || ! is_array($data['roles'])) {
            return;
        }

        $this->roleAssignment->sync($user, $data['roles']);
    }
}
