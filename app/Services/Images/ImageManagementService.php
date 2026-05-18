<?php

namespace App\Services\Images;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Models\Image;
use App\Models\User;

class ImageManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param ImageCreatorService $creator
     * @param ImageUpdaterService $updater
     * @param ImageDeleterService $destructor
     * @param ImageRestorerService $restorer
     */
    public function __construct(
        protected ImageCreatorService $creator,
        protected ImageUpdaterService $updater,
        protected ImageDeleterService $destructor,
        protected ImageRestorerService $restorer,
    ) {
    }

    /**
     * Create a new image.
     *
     * @param StoreImageRequest $request
     *
     * @return Image
     */
    public function store(
        StoreImageRequest $request
    ): Image {
        return $this->creator->create(
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Update an existing image.
     *
     * @param  UpdateImageRequest $request
     * @param  Image $image
     *
     * @return Image
     */
    public function update(
        UpdateImageRequest $request,
        Image $image
    ): Image {
        return $this->updater->update(
            $image,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a image.
     *
     * @param  Image $image
     *
     * @return void
     */
    public function destroy(Image $image): void
    {
        $this->destructor->delete($image, auth()->id());
    }

    /**
     * Restore a soft-deleted image.
     *
     * @param  int $id
     *
     * @return Image
     */
    public function restore(int $id): Image
    {
        $image = Image::withTrashed()->findOrFail($id);
        return $this->restorer->restore($image, auth()->id());
    }

    /**
     * Force delete a image, permanently removing it from the
     * database.
     *
     * @param  int $id
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $image = Image::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($image, auth()->id());
    }

    /**
     * Bulk restore images.
     *
     * @param  array $ids
     * @param  User $actor
     * @param  callable $authoriseCallback
     *
     * @return array
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authoriseCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $image = Image::withTrashed()->findOrFail($id);
            $authoriseCallback($image);

            if ($image->trashed()) {
                $this->restorer->restore($image, $actor->id);
                $restored[] = $id;
            }
        }

        return $restored;
    }

    /**
     * Bulk soft delete images.
     *
     * @param  array $ids
     * @param  User $actor
     * @param  callable $authoriseCallback
     *
     * @return array
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authoriseCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $image = Image::findOrFail($id);
            $authoriseCallback($image);

            $this->destructor->delete($image, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
