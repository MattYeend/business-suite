<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserAvatarService
{
    /**
     * Inject the required services into the avatar helper service.
     *
     * @param UserAvatarValidationService $validationService
     */
    public function __construct(
        protected UserAvatarValidationService $validationService
    ) {
    }

    /**
     * Upload and store user avatar.
     *
     * @param  UploadedFile $file
     * @param  User|null $user
     *
     * @return string
     */
    public function upload(UploadedFile $file, ?User $user = null): string
    {
        $this->validationService->validate($file);

        $path = $this->storePath($user);
        $filename = $this->generateFilename($file);
        $fullPath = "{$path}/{$filename}";

        Storage::disk('public')->putFileAs($path, $file, $filename);

        return $fullPath;
    }

    /**
     * Delete user avatar.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function delete(User $user): bool
    {
        if (! $user->avatar) {
            return false;
        }

        if (Storage::disk('public')->exists($user->avatar)) {
            return Storage::disk('public')->delete($user->avatar);
        }

        return false;
    }

    /**
     * Replace user avatar with a new one.
     *
     * @param  UploadedFile $file
     * @param  User $user
     *
     * @return string
     */
    public function replace(UploadedFile $file, User $user): string
    {
        $this->delete($user);

        return $this->upload($file, $user);
    }

    /**
     * Get the public URL for the avatar.
     *
     * @param  User $user
     *
     * @return string|null
     */
    public function getUrl(User $user): ?string
    {
        if (! $user->avatar) {
            return null;
        }

        return asset('storage/' . $user->avatar);
    }

    /**
     * Generate storage path for avatar.
     *
     * @param  User|null $user
     *
     * @return string
     */
    protected function storePath(?User $user): string
    {
        $basePath = 'avatars';

        return $user ? "{$basePath}/{$user->id}" : "{$basePath}/temp";
    }

    /**
     * Generate unique filename for avatar.
     *
     * @param  UploadedFile $file
     *
     * @return string
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $timestamp = now()->format('YmdHis');
        $extension = $file->getClientOriginalExtension();
        $hash = substr(md5($file->getClientOriginalName() . $timestamp), 0, 8);

        return "{$timestamp}_{$hash}.{$extension}";
    }
}
