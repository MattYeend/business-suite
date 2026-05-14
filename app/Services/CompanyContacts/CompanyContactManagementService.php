<?php

namespace App\Services\CompanyContacts;

use App\Http\Requests\StoreCompanyContactRequest;
use App\Http\Requests\UpdateCompanyContactRequest;
use App\Models\CompanyContact;
use App\Models\User;

class CompanyContactManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param CompanyContactCreatorService $creator
     * @param CompanyContactUpdaterService $updater
     * @param CompanyContactDeleterService $destructor
     * @param CompanyContactRestorerService $restorer
     */
    public function __construct(
        protected CompanyContactCreatorService $creator,
        protected CompanyContactUpdaterService $updater,
        protected CompanyContactDeleterService $destructor,
        protected CompanyContactRestorerService $restorer,
    ) {
    }

    /**
     * Create a new company contact.
     *
     * @param StoreCompanyContactRequest $request
     *
     * @return CompanyContact
     */
    public function store(
        StoreCompanyContactRequest $request
    ): CompanyContact {
        return $this->creator->create(
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Update an existing company contact.
     *
     * @param  UpdateCompanyContactRequest $request
     * @param  CompanyContact $companyContact
     *
     * @return CompanyContact
     */
    public function update(
        UpdateCompanyContactRequest $request,
        CompanyContact $companyContact
    ): CompanyContact {
        return $this->updater->update(
            $companyContact,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a company contact.
     *
     * @param  CompanyContact $companyContact
     *
     * @return void
     */
    public function destroy(CompanyContact $companyContact): void
    {
        $this->destructor->delete($companyContact, auth()->id());
    }

    /**
     * Restore a soft-deleted company contact.
     *
     * @param  int $id
     *
     * @return CompanyContact
     */
    public function restore(int $id): CompanyContact
    {
        $companyContact = CompanyContact::withTrashed()->findOrFail($id);
        return $this->restorer->restore($companyContact, auth()->id());
    }

    /**
     * Force delete a company contact, permanently removing it from the
     * database.
     *
     * @param  int $id
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $companyContact = CompanyContact::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($companyContact, auth()->id());
    }

    /**
     * Bulk restore company contacts.
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
            $contact = CompanyContact::withTrashed()->findOrFail($id);
            $authoriseCallback($contact);

            if ($contact->trashed()) {
                $this->restorer->restore($contact, $actor->id);
                $restored[] = $id;
            }
        }

        return $restored;
    }

    /**
     * Bulk soft delete company contacts.
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
            $contact = CompanyContact::findOrFail($id);
            $authoriseCallback($contact);

            $this->destructor->delete($contact, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
