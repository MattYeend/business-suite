<?php

namespace App\Services\Users;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserCreatorService
{
    public function __construct(
        protected UserPasswordService $passwordService,
        protected UserAvatarHandlerService $avatarHandler,
        protected UserRoleAssignmentService $roleAssignment,
        protected UserDataPreparationService $dataPreparation,
        protected UserLogService $logService
    ) {
    }

    /**
     * Create a new user.
     *
     * @param  array $data
     * @param  int|null $createdBy
     *
     * @return User
     *
     * @throws \Exception
     */
    public function create(array $data, ?int $createdBy = null): User
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $actor = User::findOrFail($createdBy);

            $user = $this->createUser($data, $createdBy);

            $this->avatarHandler->handleUpload($user, $data['avatar'] ?? null);
            $this->roleAssignment->assign($user, $data['roles'] ?? null);
            $this->sendWelcomeEmail($user, $data['plain_password']);
            $this->logService->logCreation($user, $actor);

            return $user->fresh();
        });
    }

    /**
     * Create the user record.
     *
     * @param  array $data
     * @param  int|null $createdBy
     *
     * @return User
     */
    protected function createUser(array $data, ?int $createdBy): User
    {
        $plainPassword = $this->passwordService->generatePassword();

        $userData = $this->dataPreparation->prepareForCreation(
            $data,
            $plainPassword,
            $createdBy
        );

        $user = User::create($userData);
        $user->plain_password = $plainPassword;

        return $user;
    }

    /**
     * Send welcome email to the user.
     *
     * @param  User $user
     * @param  string $password
     *
     * @return void
     */
    protected function sendWelcomeEmail(User $user, string $password): void
    {
        Mail::to($user->email)->queue(new WelcomeEmail($user, $password));
    }
}
