<?php

namespace App\Services\CompanyPhones;

use App\Models\CompanyPhone;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyPhoneDeleterService
{
    public function __construct(
        protected CompanyPhoneLogService $logService
    ) {
    }

    /**
     * Soft delete a company.
     *
     * @param  CompanyPhone $companyPhone
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(
        CompanyPhone $companyPhone,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($companyPhone, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $companyPhone->deleted_by = $deletedBy;
            $companyPhone->save();

            $result = $companyPhone->delete();

            $this->logService->logDeletion($companyPhone, $actor, $deletedBy);

            return $result;
        });
    }

    /**
     * Force delete a company phone (permanent deletion).
     *
     * @param  CompanyPhone $companyPhone
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(
        CompanyPhone $companyPhone,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($companyPhone, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion($companyPhone, $actor, $deletedBy);

            return $companyPhone->forceDelete();
        });
    }

    /**
     * Delete multiple company phones.
     *
     * @param  array $companyPhoneIds
     * @param  int|null $deletedBy
     *
     * @return int Number of company phones deleted
     *
     * @throws \Exception
     */
    public function deleteMultiple(
        array $companyPhoneIds,
        ?int $deletedBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($companyPhoneIds, $deletedBy, &$count) {
            $companyPhones = CompanyPhone::whereIn('id', $companyPhoneIds)->get();

            foreach ($companyPhones as $companyPhone) {
                if ($this->delete($companyPhone, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
