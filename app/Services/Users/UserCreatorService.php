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
        protected UserAvatarService $avatarService,
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
     * @throws \Exception
     */
    public function create(array $data, ?int $createdBy = null): User
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $user = $this->createUser($data, $createdBy);

            if (isset($data['avatar'])) {
                $this->handleAvatar($user, $data['avatar']);
            }

            if (isset($data['roles']) && is_array($data['roles'])) {
                $this->assignRoles($user, $data['roles']);
            }

            $this->sendWelcomeEmail($user, $data['plain_password']);

            $this->logService->logCreation($user, $createdBy);

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

        $userData = [
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

        $user = User::create($userData);

        // Store plain password for email
        $user->plain_password = $plainPassword;

        return $user;
    }

    /**
     * Handle avatar upload.
     *
     * @param  User $user
     * @param  mixed $avatar
     *
     * @return void
     */
    protected function handleAvatar(User $user, mixed $avatar): void
    {
        if (is_object($avatar) && method_exists($avatar, 'isValid')) {
            $path = $this->avatarService->upload($avatar, $user);
            $user->avatar = $path;
            $user->save();
        }
    }

    /**
     * Assign roles to the user.
     *
     * @param  User $user
     * @param  array $roles
     *
     * @return void
     */
    protected function assignRoles(User $user, array $roles): void
    {
        if ($user->team_id) {
            $user->assignRoleInTeam($roles, $user->team_id);
        } else {
            $user->assignRole($roles);
        }
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
