<?php

namespace App\Services\CompanyIndustries;

use App\Models\CompanyIndustry;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CompanyIndustryCreatorService
{
    public function __construct(
        protected CompanyIndustryDataPreparationService $dataPreparation,
        protected CompanyIndustryLogService $logService
    ) {
    }

    /**
     * Create a new company industry.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return CompanyIndustry
     *
     * @throws ModelNotFoundException
     */
    public function create(array $data, int $createdBy): CompanyIndustry
    {
        $actor = User::findOrFail($createdBy);

        return DB::transaction(function () use ($data, $createdBy, $actor) {
            $industry = $this->createCompanyIndustry($data, $createdBy);
            $this->logService->logCreation($industry, $actor);

            return $industry;
        });
    }

    /**
     * Create the company industry record.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return CompanyIndustry
     */
    protected function createCompanyIndustry(
        array $data,
        int $createdBy
    ): CompanyIndustry {
        $industryData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return CompanyIndustry::create($industryData);
    }
}
