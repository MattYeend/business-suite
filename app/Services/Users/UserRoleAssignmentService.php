<?php

namespace App\Services\Users;

use App\Models\User;

/**
 * Handles role assignment logic for users.
 */
class UserRoleAssignmentService
{
    /**
     * Assign roles to a user.
     *
     * @param  User $user
     * @param  array|null $roles
     *
     * @return void
     */
    public function assign(User $user, ?array $roles): void
    {
        if ($roles === null) {
            return;
        }

        $this->executeRoleAssignment($user, $roles);
    }

    /**
     * Sync roles for a user.
     *
     * @param  User $user
     * @param  array $roles
     *
     * @return void
     */
    public function sync(User $user, array $roles): void
    {
        $this->executeRoleSync($user, $roles);
    }

    /**
     * Execute role assignment with team context if needed.
     *
     * @param  User $user
     * @param  array $roles
     *
     * @return void
     */
    private function executeRoleAssignment(User $user, array $roles): void
    {
        if ($user->team_id) {
            $user->assignRoleInTeam($roles, $user->team_id);
            return;
        }

        $user->assignRole($roles);
    }

    /**
     * Execute role sync with team context if needed.
     *
     * @param  User $user
     * @param  array $roles
     *
     * @return void
     */
    private function executeRoleSync(User $user, array $roles): void
    {
        if ($user->team_id) {
            $user->executeInTeamContext(
                fn () => $user->syncRoles($roles),
                $user->team_id
            );
            return;
        }

        $user->syncRoles($roles);
    }
}
