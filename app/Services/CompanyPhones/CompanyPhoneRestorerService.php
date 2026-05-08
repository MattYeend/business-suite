<?php

namespace App\Services\CompanyPhones;

use App\Models\CompanyPhone;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CompanyPhoneRestorerService
{
    public function __construct(
        protected CompanyPhoneLogService $logService
    ) {
    }

    /**
     * Restore a soft-deleted company phone.
     *
     * @param  CompanyPhone $companyPhone
     * @param  int|null $restoredBy
     *
     * @return CompanyPhone
     *
     * @throws \Exception
     */
    public function restore(
        CompanyPhone $companyPhone,
        ?int $restoredBy = null
    ): CompanyPhone {
        return DB::transaction(function () use ($companyPhone, $restoredBy) {
            $actor = User::findOrFail($restoredBy);

            $companyPhone->restored_by = $restoredBy;
            $companyPhone->restored_at = now();
            $companyPhone->save();

            // restore() returns boolean, so we don't assign it
            $companyPhone->restore();

            $this->logService->logRestoration($companyPhone, $actor, $restoredBy);

            // Return the fresh model instance
            return $companyPhone->fresh();
        });
    }

    /**
     * Restore multiple soft-deleted company phones.
     *
     * @param  array $companyPhoneIds
     * @param  int|null $restoredBy
     *
     * @return int Number of company phones restored
     *
     * @throws \Exception
     */
    public function restoreMultiple(
        array $companyPhoneIds,
        ?int $restoredBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($companyPhoneIds, $restoredBy, &$count) {
            /** @var Collection<int,CompanyPhone> $companyPhones */
            $companyPhones = CompanyPhone::withTrashed()
                ->whereIn('id', $companyPhoneIds)
                ->get();

            foreach ($companyPhones as $companyPhone) {
                if ($companyPhone->trashed()) {
                    $this->restore($companyPhone, $restoredBy);
                    $count++;
                }
            }
        });

        return $count;
    }
}
