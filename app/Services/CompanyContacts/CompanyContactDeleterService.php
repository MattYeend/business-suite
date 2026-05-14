<?php

namespace App\Services\CompanyContacts;

use App\Models\CompanyContact;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyContactDeleterService
{
    /**
     * Inject the required services into the deleter service.
     *
     * @param CompanyContactLogService $logService
     */
    public function __construct(
        protected CompanyContactLogService $logService
    ) {
    }

    /**
     * Soft delete a company contact.
     *
     * @param  CompanyContact $contact
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(
        CompanyContact $contact,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($contact, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $contact->deleted_by = $deletedBy;
            $contact->save();

            $result = $contact->delete();

            $this->logService->logDeletion($contact, $actor, $deletedBy);

            return $result;
        });
    }

    /**
     * Force delete a company contact (permanent deletion).
     *
     * @param  CompanyContact $contact
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(
        CompanyContact $contact,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($contact, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion($contact, $actor, $deletedBy);

            return $contact->forceDelete();
        });
    }

    /**
     * Delete multiple company contacts.
     *
     * @param  array $contactIds
     * @param  int|null $deletedBy
     *
     * @return int Number of company contacts deleted
     *
     * @throws \Exception
     */
    public function deleteMultiple(
        array $contactIds,
        ?int $deletedBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($contactIds, $deletedBy, &$count) {
            $contacts = CompanyContact::whereIn('id', $contactIds)->get();

            foreach ($contacts as $contact) {
                if ($this->delete($contact, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
