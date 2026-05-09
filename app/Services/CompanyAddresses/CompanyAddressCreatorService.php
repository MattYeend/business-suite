<?php

namespace App\Services\CompanyAddresses;

use App\Models\CompanyAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CompanyAddressCreatorService
{
    public function __construct(
        protected CompanyAddressDataPreparationService $dataPreparation,
        protected CompanyAddressLogService $logService
    ) {
    }

    /**
     * Create a new company address.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return CompanyAddress
     *
     * @throws ModelNotFoundException
     */
    public function create(array $data, int $createdBy): CompanyAddress
    {
        $actor = User::findOrFail($createdBy);

        return DB::transaction(function () use ($data, $createdBy, $actor) {
            $address = $this->createCompanyIndustry($data, $createdBy);
            $this->logService->logCreation($address, $actor, $createdBy);

            return $address;
        });
    }

    /**
     * Create the company address record.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return CompanyAddress
     */
    protected function createCompanyIndustry(
        array $data,
        int $createdBy
    ): CompanyAddress {
        $industryData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return CompanyAddress::create($industryData);
    }
}
