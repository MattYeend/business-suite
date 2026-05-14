<?php

namespace App\Services\Companies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyDeleterService
{
    /**
     * Inject the required services into the deleter service.
     *
     * @param CompanyLogService $logService
     */
    public function __construct(
        protected CompanyLogService $logService
    ) {
    }

    /**
     * Soft delete a company.
     *
     * @param  Company $company
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(
        Company $company,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($company, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $company->deleted_by = $deletedBy;
            $company->save();

            $result = $company->delete();

            $this->logService->logDeletion($company, $actor, $deletedBy);

            return $result;
        });
    }

    /**
     * Force delete a company (permanent deletion).
     *
     * @param  Company $company
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(
        Company $company,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($company, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion($company, $actor, $deletedBy);

            return $company->forceDelete();
        });
    }

    /**
     * Delete multiple companies.
     *
     * @param  array $companyIds
     * @param  int|null $deletedBy
     *
     * @return int Number of companies deleted
     *
     * @throws \Exception
     */
    public function deleteMultiple(
        array $companyIds,
        ?int $deletedBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($companyIds, $deletedBy, &$count) {
            $companies = Company::whereIn('id', $companyIds)->get();

            foreach ($companies as $company) {
                if ($this->delete($company, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
