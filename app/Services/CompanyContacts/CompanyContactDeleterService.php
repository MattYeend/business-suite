<?php

namespace App\Services\CompanyContacts;

use App\Models\CompanyContact;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyContactDeleterService
{
    public function __construct(
        protected CompanyContactLogService $logService
    ) {
    }

    /**
     * Soft delete a company industry.
     *
     * @param  CompanyContact $industry
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(
        CompanyContact $industry,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($industry, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $industry->deleted_by = $deletedBy;
            $industry->save();

            $result = $industry->delete();

            $this->logService->logDeletion($industry, $actor, $deletedBy);

            return $result;
        });
    }

    /**
     * Force delete a company industry (permanent deletion).
     *
     * @param  CompanyContact $industry
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(
        CompanyContact $industry,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($industry, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion($industry, $actor, $deletedBy);

            return $industry->forceDelete();
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

            foreach ($contacts as $industry) {
                if ($this->delete($industry, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
