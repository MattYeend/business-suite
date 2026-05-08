<?php

namespace App\Services\CompanyPhones;

use App\Http\Requests\StoreCompanyPhoneRequest;
use App\Http\Requests\UpdateCompanyPhoneRequest;
use App\Models\CompanyPhone;
use App\Models\User;

/**
 * Orchestrates company lifecycle operations by delegating to focused
 * sub-services.
 *
 * Acts as the single entry point for company phone create, update, delete,
 * and restore operations, keeping controllers decoupled from the underlying
 * service implementations.
 */
class CompanyPhoneManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param  CompanyPhoneCreatorService $creator Handles company phone
     * creation.
     * @param  CompanyPhoneUpdaterService $updater Handles company phone
     * updates.
     * @param  CompanyPhoneDeleterService $destructor Handles company
     * phone deletion.
     * @param  CompanyPhoneRestorerService $restorer Handles company phone
     * restoration.
     *
     * @return void
     */
    public function __construct(
        protected CompanyPhoneCreatorService $creator,
        protected CompanyPhoneUpdaterService $updater,
        protected CompanyPhoneDeleterService $destructor,
        protected CompanyPhoneRestorerService $restorer,
    ) {
    }

    /**
     * Create a new company phone.
     *
     * @param StoreCompanyPhoneRequest $request Validated request
     * containing company phone data.
     *
     * @return CompanyPhone The newly created company phone.
     */
    public function store(
        StoreCompanyPhoneRequest $request
    ): CompanyPhone {
        return $this->creator->create(
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Update an existing company phone.
     *
     * @param  UpdateCompanyPhoneRequest $request Validated
     * request containing updated company phone data.
     * @param  CompanyPhone $company The company phone
     * instance to update.
     *
     * @return CompanyPhone The updated company phone.
     */
    public function update(
        UpdateCompanyPhoneRequest $request,
        CompanyPhone $company
    ): CompanyPhone {
        return $this->updater->update(
            $company,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a company phone.
     *
     * @param  CompanyPhone $company The company phone to delete.
     *
     * @return void
     */
    public function destroy(CompanyPhone $company): void
    {
        $this->destructor->delete($company, auth()->id());
    }

    /**
     * Restore a soft-deleted company phone.
     *
     * @param  int $id The ID of the company phone to restore.
     *
     * @return CompanyPhone The restored company phone.
     */
    public function restore(int $id): CompanyPhone
    {
        $company = CompanyPhone::withTrashed()->findOrFail($id);
        return $this->restorer->restore($company, auth()->id());
    }

    /**
     * Force delete a company phone, permanently removing it from the
     * database.
     *
     * @param  int $id The ID of the company phone to force delete.
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $company = CompanyPhone::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($company, auth()->id());
    }

    /**
     * Bulk restore company phones.
     *
     * @param  array $ids The IDs of the company phones to restore.
     * @param  User $actor The user performing the restoration, used for
     * logging.
     * @param  callable $authorizeCallback The callback to authorize
     * each company phone.
     *
     * @return array The IDs of the company phones that were restored.
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $phone = CompanyPhone::withTrashed()->findOrFail($id);
            $authorizeCallback($phone);

            if ($phone->trashed()) {
                $this->restorer->restore($phone, $actor->id);
                $restored[] = $id;
            }
        }

        return $restored;
    }

    /**
     * Bulk soft delete company phones.
     *
     * @param  array $ids The IDs of the company phones to delete.
     * @param  User $actor The user performing the deletion, used for logging.
     * @param  callable $authorizeCallback The callback to authorize each
     * company phone.
     *
     * @return array The IDs of the company phones that were deleted.
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $phone = CompanyPhone::findOrFail($id);
            $authorizeCallback($phone);

            $this->destructor->delete($phone, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
