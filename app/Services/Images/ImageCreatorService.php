<?php

namespace App\Services\Images;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ImageCreatorService
{
    /**
     * Inject the required services into the creator service.
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
     * Create a new image.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return Image
     *
     * @throws ModelNotFoundException
     */
    public function create(array $data, int $createdBy): Image
    {
        $actor = User::findOrFail($createdBy);

        return DB::transaction(function () use ($data, $createdBy, $actor) {
            $image = $this->createCompany($data, $createdBy);
            $this->logService->logCreation($image, $actor, $createdBy);

            return $image;
        });
    }

    /**
     * Create the image record.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return Image
     */
    protected function createCompany(
        array $data,
        int $createdBy
    ): Image {
        $companyData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return Image::create($companyData);
    }
}
