<?php

namespace App\Services\CompanyAddresses;

use App\Models\CompanyAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CompanyAddressCreatorService
{
    /**
     * Inject the required services into the creator service.
     *
     * @param CompanyAddressDataPreparationService $dataPreparation
     * @param CompanyAddressLogService $logService
     */
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
            $address = $this->createCompanyAddress($data, $createdBy);
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
    protected function createCompanyAddress(
        array $data,
        int $createdBy
    ): CompanyAddress {
        $addressData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return CompanyAddress::create($addressData);
    }
}
