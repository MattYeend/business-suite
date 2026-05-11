<?php

namespace App\Services\Companies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyUpdaterService
{
    /**
     * Inject the required services into the updater service.
     *
     * @param  CompanyDataPreparationService $dataPreparation
     * @param  CompanyLogService $logService
     *
     * @return void
     */
    public function __construct(
        protected CompanyDataPreparationService $dataPreparation,
        protected CompanyLogService $logService
    ) {
    }

    /**
     * Update an existing company.
     *
     * @param  Company $company
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return Company
     *
     * @throws \Exception
     */
    public function update(
        Company $company,
        array $data,
        ?int $updatedBy = null
    ): Company {
        return DB::transaction(function () use ($company, $data, $updatedBy) {
            $actor = User::findOrFail($updatedBy);

            $this->updateCompanyData($company, $data, $updatedBy);
            $this->logService->logUpdate($company, $actor, $updatedBy);

            return $company->fresh();
        });
    }

    /**
     * Update company data.
     *
     * @param  Company $company
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return void
     */
    protected function updateCompanyData(
        Company $company,
        array $data,
        ?int $updatedBy
    ): void {
        $fillableData = $this->dataPreparation->prepareForUpdate(
            $data,
            $updatedBy
        );
        $company->update($fillableData);
        $company->save();
    }
}
