<?php

namespace App\Services\Companies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CompanyRestorerService
{
    /**
     * Inject the required services into the resorer service.
     *
     * @param CompanyLogService $logService
     */
    public function __construct(
        protected CompanyLogService $logService
    ) {
    }

    /**
     * Restore a soft-deleted company.
     *
     * @param  Company $company
     * @param  int|null $restoredBy
     *
     * @return Company
     *
     * @throws \Exception
     */
    public function restore(
        Company $company,
        ?int $restoredBy = null
    ): Company {
        return DB::transaction(function () use ($company, $restoredBy) {
            $actor = User::findOrFail($restoredBy);

            $company->restored_by = $restoredBy;
            $company->restored_at = now();
            $company->save();

            // restore() returns boolean, so we don't assign it
            $company->restore();

            $this->logService->logRestoration($company, $actor, $restoredBy);

            // Return the fresh model instance
            return $company->fresh();
        });
    }

    /**
     * Restore multiple soft-deleted companies.
     *
     * @param  array $companyIds
     * @param  int|null $restoredBy
     *
     * @return int Number of companies restored
     *
     * @throws \Exception
     */
    public function restoreMultiple(
        array $companyIds,
        ?int $restoredBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($companyIds, $restoredBy, &$count) {
            /** @var Collection<int,Company> $companies */
            $companies = Company::withTrashed()
                ->whereIn('id', $companyIds)
                ->get();

            foreach ($companies as $company) {
                if ($company->trashed()) {
                    $this->restore($company, $restoredBy);
                    $count++;
                }
            }
        });

        return $count;
    }
}
