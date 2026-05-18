<?php

namespace App\Services\Images;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ImageRestorerService
{
    /**
     * Inject the required services into the resorer service.
     *
     * @param ImageLogService $logService
     */
    public function __construct(
        protected ImageLogService $logService
    ) {
    }

    /**
     * Restore a soft-deleted image.
     *
     * @param  Image $image
     * @param  int|null $restoredBy
     *
     * @return Image
     *
     * @throws \Exception
     */
    public function restore(
        Image $image,
        ?int $restoredBy = null
    ): Image {
        return DB::transaction(function () use ($image, $restoredBy) {
            $actor = User::findOrFail($restoredBy);

            $image->restored_by = $restoredBy;
            $image->restored_at = now();
            $image->save();

            // restore() returns boolean, so we don't assign it
            $image->restore();

            $this->logService->logRestoration($image, $actor, $restoredBy);

            // Return the fresh model instance
            return $image->fresh();
        });
    }

    /**
     * Restore multiple soft-deleted images.
     *
     * @param  array $imageIds
     * @param  int|null $restoredBy
     *
     * @return int Number of images restored
     *
     * @throws \Exception
     */
    public function restoreMultiple(
        array $imageIds,
        ?int $restoredBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($imageIds, $restoredBy, &$count) {
            /** @var Collection<int,Image> $images */
            $images = Image::withTrashed()
                ->whereIn('id', $imageIds)
                ->get();

            foreach ($images as $image) {
                if ($image->trashed()) {
                    $this->restore($image, $restoredBy);
                    $count++;
                }
            }
        });

        return $count;
    }
}
