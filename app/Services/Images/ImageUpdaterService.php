<?php

namespace App\Services\Images;

use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ImageUpdaterService
{
    /**
     * Inject the required services into the updater service.
     *
     * @param ImageDataPreparationService $dataPreparation
     * @param ImageLogService $logService
     */
    public function __construct(
        protected ImageDataPreparationService $dataPreparation,
        protected ImageLogService $logService
    ) {
    }

    /**
     * Update an existing image.
     *
     * @param  Image $image
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return Image
     *
     * @throws \Exception
     */
    public function update(
        Image $image,
        array $data,
        ?int $updatedBy = null
    ): Image {
        return DB::transaction(function () use ($image, $data, $updatedBy) {
            $actor = User::findOrFail($updatedBy);

            $this->updateCompanyData($image, $data, $updatedBy);
            $this->logService->logUpdate($image, $actor, $updatedBy);

            return $image->fresh();
        });
    }

    /**
     * Update image data.
     *
     * @param  Image $image
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return void
     */
    protected function updateCompanyData(
        Image $image,
        array $data,
        ?int $updatedBy
    ): void {
        $fillableData = $this->dataPreparation->prepareForUpdate(
            $data,
            $updatedBy
        );
        $image->update($fillableData);
        $image->save();
    }
}
