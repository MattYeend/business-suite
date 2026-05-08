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
     * @param  CompanyCreatorService $creator Handles company company
     * creation.
     * @param  CompanyUpdaterService $updater Handles company company
     * updates.
     * @param  CompanyDeleterService $destructor Handles company
     * company deletion.
     * @param  CompanyRestorerService $restorer Handles company company
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
     * Create a new company.
     *
     * @param StoreCompanyRequest $request Validated request
     * containing company data.
     *
     * @return Company The newly created company.
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
     * Update an existing company.
     *
     * @param  UpdateCompanyRequest $request Validated
     * request containing updated company data.
     * @param  Company $company The company
     * instance to update.
     *
     * @return Company The updated company.
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
     * Soft delete a company.
     *
     * @param  Company $company The company to delete.
     *
     * @return void
     */
    public function destroy(Company $company): void
    {
        $this->destructor->delete($company, auth()->id());
    }

    /**
     * Restore a soft-deleted company.
     *
     * @param  int $id The ID of the company to restore.
     *
     * @return Company The restored company.
     */
    public function restore(int $id): Company
    {
        $company = Company::withTrashed()->findOrFail($id);
        return $this->restorer->restore($company, auth()->id());
    }

    /**
     * Force delete a company, permanently removing it from the
     * database.
     *
     * @param  int $id The ID of the company to force delete.
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $company = Company::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($company, auth()->id());
    }

    /**
     * Bulk restore companies.
     *
     * @param  array $ids The IDs of the companies to restore.
     * @param  User $actor The user performing the restoration, used for
     * logging.
     * @param  callable $authorizeCallback The callback to authorize
     * each company company.
     *
     * @return array The IDs of the companies that were restored.
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $company = Company::withTrashed()->findOrFail($id);
            $authorizeCallback($company);

            if ($company->trashed()) {
                $this->restorer->restore($company, $actor->id);
                $restored[] = $id;
            }
        }

        return $restored;
    }

    /**
     * Bulk soft delete companies.
     *
     * @param  array $ids The IDs of the companies to delete.
     * @param  User $actor The user performing the deletion, used for logging.
     * @param  callable $authorizeCallback The callback to authorize each
     * company.
     *
     * @return array The IDs of the companies that were deleted.
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $company = Company::findOrFail($id);
            $authorizeCallback($company);

            $this->destructor->delete($company, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
