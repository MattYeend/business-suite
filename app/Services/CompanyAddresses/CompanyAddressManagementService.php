<?php

namespace App\Services\CompanyAddresses;

use App\Http\Requests\StoreCompanyAddressRequest;
use App\Http\Requests\UpdateCompanyAddressRequest;
use App\Models\CompanyAddress;
use App\Models\User;

class CompanyAddressManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param CompanyAddressCreatorService $creator
     * @param CompanyAddressUpdaterService $updater
     * @param CompanyAddressDeleterService $destructor
     * @param CompanyAddressRestorerService $restorer
     */
    public function __construct(
        protected CompanyAddressCreatorService $creator,
        protected CompanyAddressUpdaterService $updater,
        protected CompanyAddressDeleterService $destructor,
        protected CompanyAddressRestorerService $restorer,
    ) {
    }

    /**
     * Create a new company address.
     *
     * @param StoreCompanyAddressRequest $request
     *
     * @return CompanyAddress
     */
    public function store(
        StoreCompanyAddressRequest $request
    ): CompanyAddress {
        return $this->creator->create(
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Update an existing company address.
     *
     * @param  UpdateCompanyAddressRequest $request
     * @param  CompanyAddress $companyAddress
     *
     * @return CompanyAddress The updated company address.
     */
    public function update(
        UpdateCompanyAddressRequest $request,
        CompanyAddress $companyAddress
    ): CompanyAddress {
        return $this->updater->update(
            $companyAddress,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a company address.
     *
     * @param  CompanyAddress $companyAddress
     *
     * @return void
     */
    public function destroy(CompanyAddress $companyAddress): void
    {
        $this->destructor->delete($companyAddress, auth()->id());
    }

    /**
     * Restore a soft-deleted company address.
     *
     * @param  int $id
     *
     * @return CompanyAddress
     */
    public function restore(int $id): CompanyAddress
    {
        $companyAddress = CompanyAddress::withTrashed()->findOrFail($id);
        return $this->restorer->restore($companyAddress, auth()->id());
    }

    /**
     * Force delete a company address, permanently removing it from the
     * database.
     *
     * @param  int $id
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $companyAddress = CompanyAddress::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($companyAddress, auth()->id());
    }

    /**
     * Bulk restore company addresses.
     *
     * @param  array $ids
     * @param  User $actor
     * @param  callable $authoriseCallback
     *
     * @return array
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authoriseCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $address = CompanyAddress::withTrashed()->findOrFail($id);
            $authoriseCallback($address);

            if ($address->trashed()) {
                $this->restorer->restore($address, $actor->id);
                $restored[] = $id;
            }
        }

        return $restored;
    }

    /**
     * Bulk soft delete company addresses.
     *
     * @param  array $ids
     * @param  User $actor
     * @param  callable $authoriseCallback
     *
     * @return array
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authoriseCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $address = CompanyAddress::findOrFail($id);
            $authoriseCallback($address);

            $this->destructor->delete($address, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
