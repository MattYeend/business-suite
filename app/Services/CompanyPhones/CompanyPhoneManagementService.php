<?php

namespace App\Services\CompanyPhones;

use App\Http\Requests\StoreCompanyPhoneRequest;
use App\Http\Requests\UpdateCompanyPhoneRequest;
use App\Models\CompanyPhone;
use App\Models\User;


class CompanyPhoneManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param CompanyPhoneCreatorService $creator
     * @param CompanyPhoneUpdaterService $updater
     * @param CompanyPhoneDeleterService $destructor
     * @param CompanyPhoneRestorerService $restorer
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
     * @param StoreCompanyPhoneRequest $request
     *
     * @return CompanyPhone
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
     * @param  UpdateCompanyPhoneRequest $request
     * @param  CompanyPhone $company
     *
     * @return CompanyPhone
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
     * @param  CompanyPhone $company
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
     * @param  int $id
     *
     * @return CompanyPhone
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
     * @param  int $id
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
            $phone = CompanyPhone::withTrashed()->findOrFail($id);
            $authoriseCallback($phone);

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
            $phone = CompanyPhone::findOrFail($id);
            $authoriseCallback($phone);

            $this->destructor->delete($phone, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
