<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserPasswordService
{
    /**
     * Generate a secure random password.
     *
     * @param  int $length
     *
     * @return string
     */
    public function generatePassword(int $length = 12): string
    {
        return Str::random($length);
    }

    /**
     * Update user password.
     *
     * @param  User $user
     * @param  string $password
     *
     * @return bool
     */
    public function updatePassword(User $user, string $password): bool
    {
        $user->password = Hash::make($password);

        return $user->save();
    }

    /**
     * Verify if the given password matches the user's password.
     *
     * @param  User $user
     * @param  string $password
     *
     * @return bool
     */
    public function verifyPassword(User $user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }

    /**
     * Generate and update password for a user.
     *
     * @param  User $user
     * @param  int $length
     *
     * @return string The plain text password
     */
    public function generateAndUpdate(User $user, int $length = 12): string
    {
        $password = $this->generatePassword($length);
        $this->updatePassword($user, $password);

        return $password;
    }

    /**
     * Check if password meets security requirements.
     *
     * @param  string $password
     *
     * @return bool
     */
    public function meetsRequirements(string $password): bool
    {
        $minLength = 8;
        $hasUpperCase = preg_match('/[A-Z]/', $password);
        $hasLowerCase = preg_match('/[a-z]/', $password);
        $hasNumber = preg_match('/[0-9]/', $password);

        return strlen($password) >= $minLength
            && $hasUpperCase
            && $hasLowerCase
            && $hasNumber;
    }

    /**
     * Hash a plain text password.
     *
     * @param  string $password
     *
     * @return string
     */
    public function hash(string $password): string
    {
        return Hash::make($password);
    }
}
