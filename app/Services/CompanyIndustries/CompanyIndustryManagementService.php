<?php

namespace App\Services\CompanyIndustries;

use App\Http\Requests\StoreCompanyIndustryRequest;
use App\Http\Requests\UpdateCompanyIndustryRequest;
use App\Models\CompanyIndustry;
use App\Models\User;

/**
 * Orchestrates company industry lifecycle operations by delegating to focused
 * sub-services.
 *
 * Acts as the single entry point for company industry create, update, delete,
 * and restore operations, keeping controllers decoupled from the underlying
 * service implementations.
 */
class CompanyIndustryManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param  CompanyIndustryCreatorService $creator Handles company industry
     * creation.
     * @param  CompanyIndustryUpdaterService $updater Handles company industry
     * updates.
     * @param  CompanyIndustryDeleterService $destructor Handles company
     * industry deletion.
     * @param  CompanyIndustryRestorerService $restorer Handles company industry
     * restoration.
     *
     * @return void
     */
    public function __construct(
        protected CompanyIndustryCreatorService $creator,
        protected CompanyIndustryUpdaterService $updater,
        protected CompanyIndustryDeleterService $destructor,
        protected CompanyIndustryRestorerService $restorer,
    ) {
    }

    /**
     * Create a new company industry.
     *
     * @param StoreCompanyIndustryRequest $request Validated request
     * containing company industry data.
     *
     * @return CompanyIndustry The newly created company industry.
     */
    public function store(
        StoreCompanyIndustryRequest $request
    ): CompanyIndustry {
        return $this->creator->create(
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Update an existing company industry.
     *
     * @param  UpdateCompanyIndustryRequest $request Validated
     * request containing updated company industry data.
     * @param  CompanyIndustry $companyIndustry The company industry
     * instance to update.
     *
     * @return CompanyIndustry The updated company industry.
     */
    public function update(
        UpdateCompanyIndustryRequest $request,
        CompanyIndustry $companyIndustry
    ): CompanyIndustry {
        return $this->updater->update(
            $companyIndustry,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a company industry.
     *
     * @param  CompanyIndustry $companyIndustry The company industry to delete.
     *
     * @return void
     */
    public function destroy(CompanyIndustry $companyIndustry): void
    {
        $this->destructor->delete($companyIndustry, auth()->id());
    }

    /**
     * Restore a soft-deleted company industry.
     *
     * @param  int $id The ID of the company industry to restore.
     *
     * @return CompanyIndustry The restored company industry.
     */
    public function restore(int $id): CompanyIndustry
    {
        $companyIndustry = CompanyIndustry::withTrashed()->findOrFail($id);
        return $this->restorer->restore($companyIndustry, auth()->id());
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
        $companyIndustry = CompanyIndustry::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($companyIndustry, auth()->id());
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
            $industry = CompanyIndustry::withTrashed()->findOrFail($id);
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
            $industry = CompanyIndustry::findOrFail($id);
            $authorizeCallback($industry);

            $this->destructor->delete($industry, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
