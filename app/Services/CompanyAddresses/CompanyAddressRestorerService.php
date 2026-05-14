<?php

namespace App\Services\CompanyAddresses;

use App\Models\CompanyAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CompanyAddressRestorerService
{
    /**
     * Inject the required services into the resorer service.
     *
     * @param CompanyAddressLogService $logService
     */
    public function __construct(
        protected CompanyAddressLogService $logService
    ) {
    }

    /**
     * Restore a soft-deleted company address.
     *
     * @param  CompanyAddress $address
     * @param  int|null $restoredBy
     *
     * @return CompanyAddress
     *
     * @throws \Exception
     */
    public function restore(
        CompanyAddress $address,
        ?int $restoredBy = null
    ): CompanyAddress {
        return DB::transaction(function () use ($address, $restoredBy) {
            $actor = User::findOrFail($restoredBy);

            $address->restored_by = $restoredBy;
            $address->restored_at = now();
            $address->save();

            $address->restore();

            $this->logService->logRestoration($address, $actor, $restoredBy);

            return $address->fresh();
        });
    }

    /**
     * Restore multiple soft-deleted company addresses.
     *
     * @param  array $addressIds
     * @param  int|null $restoredBy
     *
     * @return int Number of addresses restored
     *
     * @throws \Exception
     */
    public function restoreMultiple(
        array $addressIds,
        ?int $restoredBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($addressIds, $restoredBy, &$count) {
            /** @var Collection<int,CompanyAddress> $addresses */
            $addresses = CompanyAddress::withTrashed()
                ->whereIn('id', $addressIds)
                ->get();

            foreach ($addresses as $address) {
                if ($address->trashed()) {
                    $this->restore($address, $restoredBy);
                    $count++;
                }
            }
        });

        return $count;
    }
}
