<?php

namespace App\Services\CompanyIndustries;

use App\Models\CompanyIndustry;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyIndustryUpdaterService
{
    public function __construct(
        protected CompanyIndustryDataPreparationService $dataPreparation,
        protected CompanyIndustryLogService $logService
    ) {
    }

    /**
     * Update an existing company industry.
     *
     * @param  CompanyIndustry $industry
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return CompanyIndustry
     *
     * @throws \Exception
     */
    public function update(
        CompanyIndustry $industry,
        array $data,
        ?int $updatedBy = null
    ): CompanyIndustry {
        return DB::transaction(function () use ($industry, $data, $updatedBy) {
            $actor = User::findOrFail($updatedBy);

            $this->updateCompanyIndustryData($industry, $data, $updatedBy);
            $this->logService->logUpdate($industry, $actor);

            return $industry->fresh();
        });
    }

    /**
     * Update company industry data.
     *
     * @param  CompanyIndustry $industry
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return void
     */
    protected function updateCompanyIndustryData(
        CompanyIndustry $industry,
        array $data,
        ?int $updatedBy
    ): void {
        $fillableData = $this->dataPreparation->prepareForUpdate(
            $data,
            $updatedBy
        );
        $industry->update($fillableData);
        $industry->save();
    }
}
