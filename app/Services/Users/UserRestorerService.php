<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRestorerService
{
    public function __construct(
        protected UserLogService $logService
    ) {
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param  User $user
     * @param  int|null $restoredBy
     *
     * @return bool
     * @throws \Exception
     */
    public function restore(User $user, ?int $restoredBy = null): bool
    {
        return DB::transaction(function () use ($user, $restoredBy) {
            $user->restored_by = $restoredBy;
            $user->restored_at = now();
            $user->save();

            $result = $user->restore();

            $this->logService->logRestoration($user, $restoredBy);

            return $result;
        });
    }

    /**
     * Restore multiple soft-deleted users.
     *
     * @param  array $userIds
     * @param  int|null $restoredBy
     *
     * @return int Number of users restored
     * @throws \Exception
     */
    public function restoreMultiple(array $userIds, ?int $restoredBy = null): int
    {
        $count = 0;

        DB::transaction(function () use ($userIds, $restoredBy, &$count) {
            $users = User::onlyTrashed()->whereIn('id', $userIds)->get();

            foreach ($users as $user) {
                if ($this->restore($user, $restoredBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
