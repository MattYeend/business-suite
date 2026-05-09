<?php

namespace App\Services\CompanyAddresses;

use App\Models\CompanyAddress;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyAddressUpdaterService
{
    public function __construct(
        protected CompanyAddressDataPreparationService $dataPreparation,
        protected CompanyAddressLogService $logService
    ) {
    }

    /**
     * Update an existing company address.
     *
     * @param  CompanyAddress $address
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return CompanyAddress
     *
     * @throws \Exception
     */
    public function update(
        CompanyAddress $address,
        array $data,
        ?int $updatedBy = null
    ): CompanyAddress {
        return DB::transaction(function () use ($address, $data, $updatedBy) {
            $actor = User::findOrFail($updatedBy);

            $this->updateCompanyAddressData($address, $data, $updatedBy);
            $this->logService->logUpdate($address, $actor, $updatedBy);

            return $address->fresh();
        });
    }

    /**
     * Update company address data.
     *
     * @param  CompanyAddress $address
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return void
     */
    protected function updateCompanyAddressData(
        CompanyAddress $address,
        array $data,
        ?int $updatedBy
    ): void {
        $fillableData = $this->dataPreparation->prepareForUpdate(
            $data,
            $updatedBy
        );
        $address->update($fillableData);
        $address->save();
    }
}
