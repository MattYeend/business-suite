<?php

namespace App\Services\Users;

use App\Models\User;

/**
 * Formats user data for API responses.
 */
class UserFormatterService
{
    /**
     * Format a single user with all data.
     *
     * @param  User $user
     *
     * @return array
     */
    public function format(User $user): array
    {
        return array_merge(
            $this->getBaseData($user),
            $this->getTimeZoneData($user),
            $this->getTeamData($user),
            $this->getRoleData($user),
            $this->getInitialsAndDisplayData($user),
            $this->getMetaData($user),
            $this->getDateData($user),
        );
    }

    /**
     * Get base user data.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getBaseData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];
    }

    /**
     * Get the user's timezone and locale.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getTimeZoneData(User $user): array
    {
        return [
            'timezone' => $user->timezone,
            'locale' => $user->locale,
        ];
    }

    /**
     * Get the user's team data.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getTeamData(User $user): array
    {
        return [
            'team_id' => $user->team_id,
            'team_name' => $user->teamName,
        ];
    }

    /**
     * Get the user's role flags.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getRoleData(User $user): array
    {
        return [
            'is_user' => $user->is_user,
            'is_admin' => $user->is_admin,
            'is_super_admin' => $user->is_super_admin,
            'is_real' => $user->is_real,
        ];
    }

    /**
     * Get the user's initials and role display data.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getInitialsAndDisplayData(User $user): array
    {
        return [
            'initials' => $user->initials,
            'role_display' => $user->roleDisplay,
            'primary_role' => $user->primaryRole,
            'roles_list' => $user->rolesList,
            'roles' => $user->roles,
        ];
    }

    /**
     * Get the user's meta data.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getMetaData(User $user): array
    {
        return [
            'meta' => $user->meta,
        ];
    }

    /**
     * Get the user's date data.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getDateData(User $user): array
    {
        return [
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'deleted_at' => $user->deleted_at,
        ];
    }
}
