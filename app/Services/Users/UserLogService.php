<?php

namespace App\Services\Users;

use App\Models\Log;
use App\Models\User;

class UserLogService
{
    /**
     * Log user creation.
     *
     * @param  User $user The user that was created.
     * @param  int|null $actorId The ID of the user who performed the action,
     * or null for system-initiated creation.
     *
     * @return array
     */
    public function logCreation(
        User $user,
        ?int $actorId = null
    ): array {
        $actor = $actorId ? User::find($actorId) : null;

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
     * @param  User $user The user that was updated.
     * @param  int|null $actorId The ID of the user who performed the action,
     * or null for system-initiated updates.
     *
     * @return array The structured data written to the log entry.
     */
    public function logUpdate(
        User $user,
        ?int $actorId = null
    ): array {
        $actor = $actorId ? User::find($actorId) : null;

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
     * @param  User $user The user that was deleted.
     * @param  int|null $actorId The ID of the user who performed the action,
     * or null for system-initiated deletion.
     *
     * @return array The structured data written to the log entry.
     */
    public function logDeletion(
        User $user,
        ?int $actorId = null
    ): array {
        $actor = $actorId ? User::find($actorId) : null;

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
     * @param  User $user The user that was force deleted.
     * @param  int|null $actorId The ID of the user who performed the action,
     * or null for system-initiated deletion.
     *
     * @return array The structured data written to the log entry.
     */
    public function logForceDeletion(
        User $user,
        ?int $actorId = null
    ): array {
        $actor = $actorId ? User::find($actorId) : null;

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
     * @param  User $user The user that was verified.
     * @param  int|null $actorId The ID of the user who performed the action,
     * or null for system-initiated verification.
     *
     * @return array The structured data written to the log entry.
     */
    public function userVerified(
        User $user,
        ?int $actorId = null
    ): array {
        $actor = $actorId ? User::find($actorId) : null;

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
     * @param  User $user The user that was restored.
     * @param  int|null $actorId The ID of the user who performed the action,
     * or null for system-initiated restoration.
     *
     * @return array The structured data written to the log entry.
     */
    public function logRestoration(
        User $user,
        ?int $actorId = null
    ): array {
        $actor = $actorId ? User::find($actorId) : null;

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
     * @param  User $user The user whose password was changed.
     * @param  int|null $actorId The ID of the user who performed the action,
     * or null for system-initiated change.
     *
     * @return array The structured data written to the log entry.
     */
    public function logPasswordChange(
        User $user,
        ?int $actorId = null
    ): array {
        $actor = $actorId ? User::find($actorId) : null;

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
     * @param  User $user The user whose roles were assigned.
     * @param  array $roles The roles that were assigned.
     * @param  int|null $actorId The ID of the user who performed the action,
     * or null for system-initiated assignment.
     *
     * @return array The structured data written to the log entry.
     */
    public function logRoleAssignment(
        User $user,
        array $roles,
        ?int $actorId = null
    ): array {
        $actor = $actorId ? User::find($actorId) : null;

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
