<?php

namespace App\Services\CompanyIndustries;

use App\Http\Requests\StoreCompanyIndustryRequest;
use App\Http\Requests\UpdateCompanyIndustryRequest;
use App\Models\CompanyIndustry;

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
}
