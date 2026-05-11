<?php

namespace App\Services\Companies;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\User;

class CompanyManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param  CompanyCreatorService $creator
     * @param  CompanyUpdaterService $updater
     * @param  CompanyDeleterService $destructor
     * @param  CompanyRestorerService $restorer
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
     * @param StoreCompanyRequest $request
     *
     * @return Company
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
     * @param  UpdateCompanyRequest $request
     * @param  Company $company
     *
     * @return Company
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
     * @param  Company $company
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
     * @param  int $id
     *
     * @return Company
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
     * @param  int $id
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
            $company = Company::withTrashed()->findOrFail($id);
            $authoriseCallback($company);

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
            $company = Company::findOrFail($id);
            $authoriseCallback($company);

            $this->destructor->delete($company, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
