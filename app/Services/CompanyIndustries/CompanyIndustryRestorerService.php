<?php

namespace App\Services\CompanyIndustries;

use App\Models\CompanyIndustry;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CompanyIndustryRestorerService
{
    public function __construct(
        protected CompanyIndustryLogService $logService
    ) {
    }

    /**
     * Restore a soft-deleted company industry.
     *
     * @param  CompanyIndustry $industry
     * @param  int|null $restoredBy
     *
     * @return CompanyIndustry
     *
     * @throws \Exception
     */
    public function restore(
        CompanyIndustry $industry,
        ?int $restoredBy = null
    ): CompanyIndustry {
        return DB::transaction(function () use ($industry, $restoredBy) {
            $actor = User::findOrFail($restoredBy);

            $industry->restored_by = $restoredBy;
            $industry->restored_at = now();
            $industry->save();
            $result = $industry->restore();

            $this->logService->logRestoration($industry, $actor);

            return $result;
        });
    }

    /**
     * Restore multiple soft-deleted company industries.
     *
     * @param  array $industryIds
     * @param  int|null $restoredBy
     *
     * @return int Number of industries restored
     *
     * @throws \Exception
     */
    public function restoreMultiple(
        array $industryIds,
        ?int $restoredBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($industryIds, $restoredBy, &$count) {
            /** @var Collection<int,CompanyIndustry> $industries */
            $industries = CompanyIndustry::withTrashed()
                ->whereIn('id', $industryIds)
                ->get();

            foreach ($industries as $industry) {
                if ($industry->trashed()) {
                    $this->restore($industry, $restoredBy);
                    $count++;
                }
            }
        });

        return $count;
    }
}
