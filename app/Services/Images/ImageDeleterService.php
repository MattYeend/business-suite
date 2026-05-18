<?php

namespace App\Services\Images;

use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ImageDeleterService
{
    /**
     * Inject the required services into the deleter service.
     *
     * @param ImageLogService $logService
     */
    public function __construct(
        protected ImageLogService $logService
    ) {
    }

    /**
     * Soft delete a image.
     *
     * @param  Image $image
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(
        Image $image,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($image, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $image->deleted_by = $deletedBy;
            $image->save();

            $result = $image->delete();

            $this->logService->logDeletion($image, $actor, $deletedBy);

            return $result;
        });
    }

    /**
     * Force delete a image (permanent deletion).
     *
     * @param  Image $image
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(
        Image $image,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($image, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion($image, $actor, $deletedBy);

            return $image->forceDelete();
        });
    }

    /**
     * Delete multiple images.
     *
     * @param  array $imageIds
     * @param  int|null $deletedBy
     *
     * @return int Number of images deleted
     *
     * @throws \Exception
     */
    public function deleteMultiple(
        array $imageIds,
        ?int $deletedBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($imageIds, $deletedBy, &$count) {
            $images = Image::whereIn('id', $imageIds)->get();

            foreach ($images as $image) {
                if ($this->delete($image, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
