<?php

namespace App\Services\CompanyIndustries;

use App\Models\CompanyIndustry;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyIndustryDeleterService
{
    public function __construct(
        protected CompanyIndustryLogService $logService
    ) {
    }

    /**
     * Soft delete a company industry.
     *
     * @param  CompanyIndustry $industry
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(
        CompanyIndustry $industry,
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
     * @param  CompanyIndustry $industry
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(
        CompanyIndustry $industry,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($industry, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion($industry, $actor, $deletedBy);

            return $industry->forceDelete();
        });
    }

    /**
     * Delete multiple company industries.
     *
     * @param  array $industryIds
     * @param  int|null $deletedBy
     *
     * @return int Number of company industries deleted
     *
     * @throws \Exception
     */
    public function deleteMultiple(
        array $industryIds,
        ?int $deletedBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($industryIds, $deletedBy, &$count) {
            $industries = CompanyIndustry::whereIn('id', $industryIds)->get();

            foreach ($industries as $industry) {
                if ($this->delete($industry, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
