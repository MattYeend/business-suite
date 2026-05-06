<?php

namespace App\Services\Companies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CompanyCreatorService
{
    public function __construct(
        protected CompanyDataPreparationService $dataPreparation,
        protected CompanyLogService $logService
    ) {
    }

    /**
     * Create a new company company.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return Company
     *
     * @throws ModelNotFoundException
     */
    public function create(array $data, int $createdBy): Company
    {
        $actor = User::findOrFail($createdBy);

        return DB::transaction(function () use ($data, $createdBy, $actor) {
            $company = $this->createCompanyIndustry($data, $createdBy);
            $this->logService->logCreation($company, $actor, $createdBy);

            return $company;
        });
    }

    /**
     * Create the company company record.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return Company
     */
    protected function createCompanyIndustry(
        array $data,
        int $createdBy
    ): Company {
        $industryData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return Company::create($industryData);
    }
}
