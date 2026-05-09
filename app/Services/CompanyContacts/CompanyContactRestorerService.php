<?php

namespace App\Services\CompanyContacts;

use App\Models\CompanyContact;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CompanyContactRestorerService
{
    public function __construct(
        protected CompanyContactLogService $logService
    ) {
    }

    /**
     * Restore a soft-deleted company contact.
     *
     * @param  CompanyContact $contact
     * @param  int|null $restoredBy
     *
     * @return CompanyContact
     *
     * @throws \Exception
     */
    public function restore(
        CompanyContact $contact,
        ?int $restoredBy = null
    ): CompanyContact {
        return DB::transaction(function () use ($contact, $restoredBy) {
            $actor = User::findOrFail($restoredBy);

            $contact->restored_by = $restoredBy;
            $contact->restored_at = now();
            $contact->save();

            $contact->restore();

            $this->logService->logRestoration($contact, $actor, $restoredBy);

            return $contact->fresh();
        });
    }

    /**
     * Restore multiple soft-deleted company contacts.
     *
     * @param  array $contactIds
     * @param  int|null $restoredBy
     *
     * @return int Number of contacts restored
     *
     * @throws \Exception
     */
    public function restoreMultiple(
        array $contactIds,
        ?int $restoredBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($contactIds, $restoredBy, &$count) {
            /** @var Collection<int,CompanyContact> $contacts */
            $contacts = CompanyContact::withTrashed()
                ->whereIn('id', $contactIds)
                ->get();

            foreach ($contacts as $contact) {
                if ($contact->trashed()) {
                    $this->restore($contact, $restoredBy);
                    $count++;
                }
            }
        });

        return $count;
    }
}
