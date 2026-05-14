<?php

namespace App\Services\Companies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CompanyCreatorService
{
    /**
     * Inject the required services into the creator service.
     *
     * @param CompanyDataPreparationService $dataPreparation
     * @param CompanyLogService $logService
     */
    public function __construct(
        protected CompanyDataPreparationService $dataPreparation,
        protected CompanyLogService $logService
    ) {
    }

    /**
     * Create a new company.
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
            $company = $this->createCompany($data, $createdBy);
            $this->logService->logCreation($company, $actor, $createdBy);

            return $company;
        });
    }

    /**
     * Create the company record.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return Company
     */
    protected function createCompany(
        array $data,
        int $createdBy
    ): Company {
        $companyData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return Company::create($companyData);
    }
}
