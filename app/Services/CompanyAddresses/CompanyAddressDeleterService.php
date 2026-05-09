<?php

namespace App\Services\CompanyAddresses;

use App\Models\CompanyAddress;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyAddressDeleterService
{
    public function __construct(
        protected CompanyAddressLogService $logService
    ) {
    }

    /**
     * Soft delete a company address.
     *
     * @param  CompanyAddress $address
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(
        CompanyAddress $address,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($address, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $address->deleted_by = $deletedBy;
            $address->save();

            $result = $address->delete();

            $this->logService->logDeletion($address, $actor, $deletedBy);

            return $result;
        });
    }

    /**
     * Force delete a company address (permanent deletion).
     *
     * @param  CompanyAddress $address
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(
        CompanyAddress $address,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($address, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion($address, $actor, $deletedBy);

            return $address->forceDelete();
        });
    }

    /**
     * Delete multiple company addresses.
     *
     * @param  array $addressIds
     * @param  int|null $deletedBy
     *
     * @return int Number of company addresses deleted
     *
     * @throws \Exception
     */
    public function deleteMultiple(
        array $addressIds,
        ?int $deletedBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($addressIds, $deletedBy, &$count) {
            $addresses = CompanyAddress::whereIn('id', $addressIds)->get();

            foreach ($addresses as $address) {
                if ($this->delete($address, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
