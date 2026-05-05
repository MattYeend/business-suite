<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserDeleterService
{
    public function __construct(
        protected UserLogService $logService
    ) {
    }

    /**
     * Soft delete a user.
     *
     * @param  User $user
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(User $user, ?int $deletedBy = null): bool
    {
        return DB::transaction(function () use ($user, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $user->deleted_by = $deletedBy;
            $user->save();

            $result = $user->delete();

            $this->logService->logDeletion($user, $actor, $deletedBy);

            return $result;
        });
    }

    /**
     * Force delete a user (permanent deletion).
     *
     * @param  User $user
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(User $user, ?int $deletedBy = null): bool
    {
        return DB::transaction(function () use ($user, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion($user, $actor, $deletedBy);

            return $user->forceDelete();
        });
    }

    /**
     * Delete multiple users.
     *
     * @param  array $userIds
     * @param  int|null $deletedBy
     *
     * @return int Number of users deleted
     *
     * @throws \Exception
     */
    public function deleteMultiple(array $userIds, ?int $deletedBy = null): int
    {
        $count = 0;

        DB::transaction(function () use ($userIds, $deletedBy, &$count) {
            $users = User::whereIn('id', $userIds)->get();

            foreach ($users as $user) {
                if ($this->delete($user, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
