<?php

namespace App\Services\Users;

/**
 * Prepares user data arrays for creation and updates.
 */
class UserDataPreparationService
{
    /**
     * Inject the required services into the data preparation service.
     *
     * @param  UserPasswordService $passwordService
     */
    public function __construct(
        protected UserPasswordService $passwordService
    ) {
    }

    /**
     * Prepare user data for creation.
     *
     * @param  array $data
     * @param  string $plainPassword
     * @param  int|null $createdBy
     *
     * @return array
     */
    public function prepareForCreation(
        array $data,
        string $plainPassword,
        ?int $createdBy
    ): array {
        return [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $this->passwordService->hash($plainPassword),
            'phone' => $data['phone'] ?? null,
            'timezone' => $data['timezone'] ?? config('app.timezone'),
            'locale' => $data['locale'] ?? config('app.locale'),
            'team_id' => $data['team_id'] ?? null,
            'is_user' => $data['is_user'] ?? true,
            'is_admin' => $data['is_admin'] ?? false,
            'is_super_admin' => $data['is_super_admin'] ?? false,
            'is_real' => $data['is_real'] ?? true,
            'meta' => $data['meta'] ?? null,
            'created_by' => $createdBy,
        ];
    }

    /**
     * Prepare fillable data for update.
     *
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return array
     */
    public function prepareForUpdate(array $data, ?int $updatedBy): array
    {
        return array_filter([
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
    }
}
