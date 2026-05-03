<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserUpdaterService
{
    public function __construct(
        protected UserAvatarService $avatarService,
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
     * @throws \Exception
     */
    public function update(User $user, array $data, ?int $updatedBy = null): User
    {
        return DB::transaction(function () use ($user, $data, $updatedBy) {
            $this->updateUserData($user, $data, $updatedBy);

            if (isset($data['avatar'])) {
                $this->handleAvatar($user, $data['avatar']);
            }

            if (isset($data['roles']) && is_array($data['roles'])) {
                $this->syncRoles($user, $data['roles']);
            }

            $this->logService->logUpdate($user, $updatedBy);

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
    protected function updateUserData(User $user, array $data, ?int $updatedBy): void
    {
        $fillableData = array_filter([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'timezone' => $data['timezone'] ?? null,
            'locale' => $data['locale'] ?? null,
            'team_id' => $data['team_id'] ?? null,
            'is_user' => $data['is_user'] ?? null,
            'is_admin' => $data['is_admin'] ?? null,
            'is_super_admin' => $data['is_super_admin'] ?? null,
            'is_real' => $data['is_real'] ?? null,
            'meta' => $data['meta'] ?? null,
            'updated_by' => $updatedBy,
        ], fn ($value) => $value !== null);

        $user->fill($fillableData);
        $user->save();
    }

    /**
     * Handle avatar upload or removal.
     *
     * @param  User $user
     * @param  mixed $avatar
     *
     * @return void
     */
    protected function handleAvatar(User $user, mixed $avatar): void
    {
        if ($avatar === null || $avatar === '') {
            // Remove avatar
            $this->avatarService->delete($user);
            $user->avatar = null;
            $user->save();
        } elseif (is_object($avatar) && method_exists($avatar, 'isValid')) {
            // Upload new avatar
            $path = $this->avatarService->replace($avatar, $user);
            $user->avatar = $path;
            $user->save();
        }
    }

    /**
     * Sync roles for the user.
     *
     * @param  User $user
     * @param  array $roles
     *
     * @return void
     */
    protected function syncRoles(User $user, array $roles): void
    {
        if ($user->team_id) {
            $user->executeInTeamContext(
                fn () => $user->syncRoles($roles),
                $user->team_id
            );
        } else {
            $user->syncRoles($roles);
        }
    }
}
