<?php

namespace App\Services\CompanyPhones;

use App\Models\CompanyPhone;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyPhoneUpdaterService
{
    public function __construct(
        protected CompanyPhoneDataPreparationService $dataPreparation,
        protected CompanyPhoneLogService $logService
    ) {
    }

    /**
     * Update an existing company phone.
     *
     * @param  CompanyPhone $companyPhone
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return CompanyPhone
     *
     * @throws \Exception
     */
    public function update(
        CompanyPhone $companyPhone,
        array $data,
        ?int $updatedBy = null
    ): CompanyPhone {
        return DB::transaction(function () use ($companyPhone, $data, $updatedBy) {
            $actor = User::findOrFail($updatedBy);

            $this->updateCompanyData($companyPhone, $data, $updatedBy);
            $this->logService->logUpdate($companyPhone, $actor, $updatedBy);

            return $companyPhone->fresh();
        });
    }

    /**
     * Update company phone data.
     *
     * @param  CompanyPhone $companyPhone
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return void
     */
    protected function updateCompanyData(
        CompanyPhone $companyPhone,
        array $data,
        ?int $updatedBy
    ): void {
        $fillableData = $this->dataPreparation->prepareForUpdate(
            $data,
            $updatedBy
        );
        $companyPhone->update($fillableData);
        $companyPhone->save();
    }
}
