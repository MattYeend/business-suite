<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserAvatarService
{
    /**
     * Upload and store user avatar.
     *
     * @param  UploadedFile $file
     * @param  User|null $user
     *
     * @return string The stored file path
     */
    public function upload(UploadedFile $file, ?User $user = null): string
    {
        $this->validate($file);

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
     * @return string The new file path
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
     * Validate the uploaded file.
     *
     * @param  UploadedFile $file
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function validate(UploadedFile $file): void
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2048 * 1024; // 2MB in bytes

        if (! in_array($file->getMimeType(), $allowedMimes)) {
            throw new \InvalidArgumentException(
                'Invalid file type. Allowed types: jpg, jpeg, png, gif, webp'
            );
        }

        if ($file->getSize() > $maxSize) {
            throw new \InvalidArgumentException('File size exceeds 2MB limit');
        }
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

        if ($user) {
            return "{$basePath}/{$user->id}";
        }

        return "{$basePath}/temp";
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
