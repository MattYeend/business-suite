<?php

namespace App\Services\CompanyIndustries;

use App\Http\Requests\StoreCompanyIndustryRequest;
use App\Http\Requests\UpdateCompanyIndustryRequest;
use App\Models\CompanyIndustry;
use App\Models\User;

class CompanyIndustryManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param CompanyIndustryCreatorService $creator
     * @param CompanyIndustryUpdaterService $updater
     * @param CompanyIndustryDeleterService $destructor
     * @param CompanyIndustryRestorerService $restorer
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
     * @param StoreCompanyIndustryRequest $request
     *
     * @return CompanyIndustry
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
     * @param  UpdateCompanyIndustryRequest $request
     * @param  CompanyIndustry $companyIndustry
     *
     * @return CompanyIndustry
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
     * @param  CompanyIndustry $companyIndustry
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
     * @param  int $id
     *
     * @return CompanyIndustry
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
     * @param  int $id
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
            $industry = CompanyIndustry::withTrashed()->findOrFail($id);
            $authoriseCallback($industry);

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
            $industry = CompanyIndustry::findOrFail($id);
            $authoriseCallback($industry);

            $this->destructor->delete($industry, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
