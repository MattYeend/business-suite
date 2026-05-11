<?php

namespace App\Services\Parts;

use App\Models\Part;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PartUpdaterService
{
    /**
     * Inject the required services into the updater service.
     *
     * @param  PartDataPreparationService $dataPreparation
     * @param  PartLogService $logService
     *
     * @return void
     */
    public function __construct(
        protected PartDataPreparationService $dataPreparation,
        protected PartLogService $logService
    ) {
    }

    /**
     * Update an existing part.
     *
     * @param  Part $part
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return Part
     *
     * @throws \Exception
     */
    public function update(
        Part $part,
        array $data,
        ?int $updatedBy = null
    ): Part {
        return DB::transaction(function () use ($part, $data, $updatedBy) {
            $actor = User::findOrFail($updatedBy);

            $this->updateCompanyData($part, $data, $updatedBy);
            $this->logService->logUpdate($part, $actor, $updatedBy);

            return $part->fresh();
        });
    }

    /**
     * Update part data.
     *
     * @param  Part $part
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return void
     */
    protected function updateCompanyData(
        Part $part,
        array $data,
        ?int $updatedBy
    ): void {
        $fillableData = $this->dataPreparation->prepareForUpdate(
            $data,
            $updatedBy
        );
        $part->update($fillableData);
        $part->save();
    }
}
