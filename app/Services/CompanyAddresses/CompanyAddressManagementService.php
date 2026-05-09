<?php

namespace App\Services\CompanyAddresses;

use App\Http\Requests\StoreCompanyAddressRequest;
use App\Http\Requests\UpdateCompanyAddressRequest;
use App\Models\CompanyAddress;
use App\Models\User;

/**
 * Orchestrates company address lifecycle operations by delegating to focused
 * sub-services.
 *
 * Acts as the single entry point for company address create, update, delete,
 * and restore operations, keeping controllers decoupled from the underlying
 * service implementations.
 */
class CompanyAddressManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param  CompanyAddressCreatorService $creator Handles company address
     * creation.
     * @param  CompanyAddressUpdaterService $updater Handles company address
     * updates.
     * @param  CompanyAddressDeleterService $destructor Handles company
     * address deletion.
     * @param  CompanyAddressRestorerService $restorer Handles company address
     * restoration.
     *
     * @return void
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
     * @param StoreCompanyAddressRequest $request Validated request
     * containing company address data.
     *
     * @return CompanyAddress The newly created company address.
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
     * @param  UpdateCompanyAddressRequest $request Validated
     * request containing updated company address data.
     * @param  CompanyAddress $companyAddress The company address
     * instance to update.
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
     * @param  CompanyAddress $companyAddress The company address to delete.
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
     * @param  int $id The ID of the company address to restore.
     *
     * @return CompanyAddress The restored company address.
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
     * @param  int $id The ID of the company address to force delete.
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
     * @param  array $ids The IDs of the company addresses to restore.
     * @param  User $actor The user performing the restoration, used for
     * logging.
     * @param  callable $authorizeCallback The callback to authorize
     * each company address.
     *
     * @return array The IDs of the company addresses that were restored.
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $address = CompanyAddress::withTrashed()->findOrFail($id);
            $authorizeCallback($address);

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
     * @param  array $ids The IDs of the company addresses to delete.
     * @param  User $actor The user performing the deletion, used for logging.
     * @param  callable $authorizeCallback The callback to authorize each
     * company address.
     *
     * @return array The IDs of the company addresses that were deleted.
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $address = CompanyAddress::findOrFail($id);
            $authorizeCallback($address);

            $this->destructor->delete($address, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
