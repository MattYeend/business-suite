<?php

namespace App\Services\Users;

use App\Models\Log;
use App\Models\User;

class UserLogService
{
    /**
     * Log user creation.
     *
     * @param  User $user
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logCreation(
        User $user,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseUserData($user) + [
            'created_at' => now(),
            'created_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_CREATE_USER,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a user update event.
     *
     * @param  User $user
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logUpdate(
        User $user,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseUserData($user) + [
            'updated_at' => now(),
            'updated_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_UPDATE_USER,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a user deletion event.
     *
     * @param  User $user
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logDeletion(
        User $user,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseUserData($user) + [
            'deleted_at' => now(),
            'deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_DELETE_USER,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log user force deletion (permanent).
     *
     * @param  User $user
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logForceDeletion(
        User $user,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseUserData($user) + [
            'force_deleted_at' => now(),
            'force_deleted_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_FORCE_DELETE_USER,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a user email verification event.
     *
     * @param  User $user
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function userVerified(
        User $user,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseUserData($user) + [
            'verified_at' => now(),
            'verified_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_VERIFY_USER,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a user restoration event.
     *
     * @param  User $user
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logRestoration(
        User $user,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseUserData($user) + [
            'restored_at' => now(),
            'restored_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_USER_RESTORED,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log password change.
     *
     * @param  User $user
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logPasswordChange(
        User $user,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseUserData($user) + [
            'password_changed_at' => now(),
            'password_changed_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_PASSWORD_CHANGED,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log role assignment.
     *
     * @param  User $user
     * @param  array $roles
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logRoleAssignment(
        User $user,
        array $roles,
        User $actor,
        int $actorId
    ): array {
        $data = $this->baseUserData($user) + [
            'roles' => implode(', ', $roles),
            'assigned_at' => now(),
            'assigned_by' => $actor?->name,
        ];

        Log::log(
            Log::ACTION_ROLE_ASSIGNED,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a user import event.
     *
     * @param  array $importData
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logImport(
        array $importData,
        User $actor,
        int $actorId
    ): array {
        $data = [
            'imported_at' => now(),
            'imported_by' => $actor?->name,
            'imported_count' => count($importData),
            'imported_data_sample' => array_slice($importData, 0, 5),
        ];

        Log::log(
            Log::ACTION_IMPORT_USER,
            $data,
            $actorId,
        );
        return $data;
    }

    /**
     * Log a user export event.
     *
     * @param  array $exportData
     * @param  User $actor
     * @param  int $actorId
     *
     * @return array
     */
    public function logExport(
        array $exportData,
        User $actor,
        int $actorId
    ): array {
        $data = [
            'exported_at' => now(),
            'exported_by' => $actor?->name,
            'exported_count' => count($exportData),
            'exported_data_sample' => array_slice($exportData, 0, 5),
        ];

        Log::log(
            Log::ACTION_EXPORT_USER,
            $data,
            $actorId,
        );

        return $data;
    }

    /**
     * Log a user update performed by a scheduled task (cron).
     *
     * @param  User $user
     *
     * @return array
     */
    public function logUpdateByCron(User $user): array
    {
        $data = $this->baseUserData($user) + [
            'updated_at' => now(),
            'updated_by' => 'System (Cron)',
        ];

        Log::log(
            Log::ACTION_USER_UPDATED_BY_CRON,
            $data,
            null,
        );

        return $data;
    }

    /**
     * Get base user data for logging.
     *
     * @param  User $user
     *
     * @return array
     */
    protected function baseUserData(User $user): array
    {
        if (! $user) {
            return [
                'id' => null,
                'name' => null,
                'email' => null,
                'role' => null,
                'phone' => null,
                'avatar' => null,
            ];
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->primaryRole,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
        ];
    }
}
