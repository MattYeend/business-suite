<?php

namespace App\Services\Companies;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\User;

/**
 * Orchestrates company lifecycle operations by delegating to focused
 * sub-services.
 *
 * Acts as the single entry point for company create, update, delete,
 * and restore operations, keeping controllers decoupled from the underlying
 * service implementations.
 */
class CompanyManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param  CompanyCreatorService $creator Handles company industry
     * creation.
     * @param  CompanyUpdaterService $updater Handles company industry
     * updates.
     * @param  CompanyDeleterService $destructor Handles company
     * industry deletion.
     * @param  CompanyRestorerService $restorer Handles company industry
     * restoration.
     *
     * @return void
     */
    public function __construct(
        protected CompanyCreatorService $creator,
        protected CompanyUpdaterService $updater,
        protected CompanyDeleterService $destructor,
        protected CompanyRestorerService $restorer,
    ) {
    }

    /**
     * Create a new company industry.
     *
     * @param StoreCompanyRequest $request Validated request
     * containing company industry data.
     *
     * @return Company The newly created company industry.
     */
    public function store(
        StoreCompanyRequest $request
    ): Company {
        return $this->creator->create(
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Update an existing company industry.
     *
     * @param  UpdateCompanyRequest $request Validated
     * request containing updated company industry data.
     * @param  Company $company The company industry
     * instance to update.
     *
     * @return Company The updated company industry.
     */
    public function update(
        UpdateCompanyRequest $request,
        Company $company
    ): Company {
        return $this->updater->update(
            $company,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a company industry.
     *
     * @param  Company $company The company industry to delete.
     *
     * @return void
     */
    public function destroy(Company $company): void
    {
        $this->destructor->delete($company, auth()->id());
    }

    /**
     * Restore a soft-deleted company industry.
     *
     * @param  int $id The ID of the company industry to restore.
     *
     * @return Company The restored company industry.
     */
    public function restore(int $id): Company
    {
        $company = Company::withTrashed()->findOrFail($id);
        return $this->restorer->restore($company, auth()->id());
    }

    /**
     * Force delete a company industry, permanently removing it from the
     * database.
     *
     * @param  int $id The ID of the company industry to force delete.
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $company = Company::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($company, auth()->id());
    }

    /**
     * Bulk restore company industries.
     *
     * @param  array $ids The IDs of the company industries to restore.
     * @param  User $actor The user performing the restoration, used for
     * logging.
     * @param  callable $authorizeCallback The callback to authorize
     * each company industry.
     *
     * @return array The IDs of the company industries that were restored.
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $industry = Company::withTrashed()->findOrFail($id);
            $authorizeCallback($industry);

            if ($industry->trashed()) {
                $this->restorer->restore($industry, $actor->id);
                $restored[] = $id;
            }
        }

        return $restored;
    }

    /**
     * Bulk soft delete company industries.
     *
     * @param  array $ids The IDs of the company industries to delete.
     * @param  User $actor The user performing the deletion, used for logging.
     * @param  callable $authorizeCallback The callback to authorize each
     * company industry.
     *
     * @return array The IDs of the company industries that were deleted.
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $industry = Company::findOrFail($id);
            $authorizeCallback($industry);

            $this->destructor->delete($industry, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
