<?php

namespace App\Services\CompanyContacts;

use App\Http\Requests\StoreCompanyContactRequest;
use App\Http\Requests\UpdateCompanyContactRequest;
use App\Models\CompanyContact;
use App\Models\User;

/**
 * Orchestrates company contact lifecycle operations by delegating to focused
 * sub-services.
 *
 * Acts as the single entry point for company contact create, update, delete,
 * and restore operations, keeping controllers decoupled from the underlying
 * service implementations.
 */
class CompanyContactManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param  CompanyContactCreatorService $creator Handles company contact
     * creation.
     * @param  CompanyContactUpdaterService $updater Handles company contact
     * updates.
     * @param  CompanyContactDeleterService $destructor Handles company
     * contact deletion.
     * @param  CompanyContactRestorerService $restorer Handles company contact
     * restoration.
     *
     * @return void
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
     * @param StoreCompanyContactRequest $request Validated request
     * containing company contact data.
     *
     * @return CompanyContact The newly created company contact.
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
     * @param  UpdateCompanyContactRequest $request Validated
     * request containing updated company contact data.
     * @param  CompanyContact $companyIndustry The company contact
     * instance to update.
     *
     * @return CompanyContact The updated company contact.
     */
    public function update(
        UpdateCompanyContactRequest $request,
        CompanyContact $companyIndustry
    ): CompanyContact {
        return $this->updater->update(
            $companyIndustry,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a company contact.
     *
     * @param  CompanyContact $companyIndustry The company contact to delete.
     *
     * @return void
     */
    public function destroy(CompanyContact $companyIndustry): void
    {
        $this->destructor->delete($companyIndustry, auth()->id());
    }

    /**
     * Restore a soft-deleted company contact.
     *
     * @param  int $id The ID of the company contact to restore.
     *
     * @return CompanyContact The restored company contact.
     */
    public function restore(int $id): CompanyContact
    {
        $companyIndustry = CompanyContact::withTrashed()->findOrFail($id);
        return $this->restorer->restore($companyIndustry, auth()->id());
    }

    /**
     * Force delete a company contact, permanently removing it from the
     * database.
     *
     * @param  int $id The ID of the company contact to force delete.
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $companyIndustry = CompanyContact::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($companyIndustry, auth()->id());
    }

    /**
     * Bulk restore company contacts.
     *
     * @param  array $ids The IDs of the company contacts to restore.
     * @param  User $actor The user performing the restoration, used for
     * logging.
     * @param  callable $authorizeCallback The callback to authorize
     * each company contact.
     *
     * @return array The IDs of the company contacts that were restored.
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $contact = CompanyContact::withTrashed()->findOrFail($id);
            $authorizeCallback($contact);

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
     * @param  array $ids The IDs of the company contacts to delete.
     * @param  User $actor The user performing the deletion, used for logging.
     * @param  callable $authorizeCallback The callback to authorize each
     * company contact.
     *
     * @return array The IDs of the company contacts that were deleted.
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authorizeCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $contact = CompanyContact::findOrFail($id);
            $authorizeCallback($contact);

            $this->destructor->delete($contact, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
