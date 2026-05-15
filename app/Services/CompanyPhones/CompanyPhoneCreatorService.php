<?php

namespace App\Services\CompanyPhones;

use App\Models\CompanyPhone;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CompanyPhoneCreatorService
{
    /**
     * Inject the required services into the creator service.
     *
     * @param CompanyPhoneDataPreparationService $dataPreparation
     * @param CompanyPhoneLogService $logService
     */
    public function __construct(
        protected CompanyPhoneDataPreparationService $dataPreparation,
        protected CompanyPhoneLogService $logService
    ) {
    }

    /**
     * Create a new company phone.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return CompanyPhone
     *
     * @throws ModelNotFoundException
     */
    public function create(array $data, int $createdBy): CompanyPhone
    {
        $actor = User::findOrFail($createdBy);

        return DB::transaction(function () use ($data, $createdBy, $actor) {
            $companyPhone = $this->createCompanyPhone($data, $createdBy);
            $this->logService->logCreation($companyPhone, $actor, $createdBy);

            return $companyPhone;
        });
    }

    /**
     * Create the company phone record.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return CompanyPhone
     */
    protected function createCompanyPhone(
        array $data,
        int $createdBy
    ): CompanyPhone {
        $phoneData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return CompanyPhone::create($phoneData);
    }
}
