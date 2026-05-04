<?php

namespace App\Services\CompanyIndustries;

use App\Models\CompanyIndustry;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class CompanyIndustryQueryService
{
    public function __construct(
        protected CompanyIndustrySortingService $sortingService,
        protected CompanyIndustryTrashFilterService $trashFilterService,
        protected CompanyIndustryFilterService $filterService,
        protected CompanyIndustryFormatterService $formatterService
    ) {
    }

    /**
     * Get paginated company industries with filters.
     *
     * @param  array $filters
     *
     * @return array
     */
    public function getPaginated(array $filters = []): array
    {
        $query = $this->buildQuery($filters);
        $paginated = $this->paginate($query, $filters['per_page'] ?? 15);

        return array_merge(
            $paginated,
            $this->getPermissions(),
            $this->baseData(),
        );
    }

    /**
     * Get a single company industry by ID.
     *
     * @param  int $id
     * @param  bool $withTrashed
     *
     * @return array
     */
    public function getById(int $id, bool $withTrashed = false): array
    {
        $industry = $this->findCompanyIndustry($id, $withTrashed);

        return array_merge(
            ['company_industry' => $this->formatterService->format($industry)],
            $this->getPermissions(),
            $this->baseData(),
        );
    }

    /**
     * Build the base query with filters.
     *
     * @param  array $filters
     *
     * @return Builder
     */
    protected function buildQuery(array $filters): Builder
    {
        $query = CompanyIndustry::query();
        $query = $this->filterService->applyAll($query, $filters);

        return $this->applySorting($query, $filters);
    }

    /**
     * Paginate the query and return as plain array.
     *
     * @param  Builder $query
     * @param  int $perPage
     *
     * @return array
     */
    protected function paginate(Builder $query, int $perPage): array
    {
        $paginator = $query->paginate($perPage);

        return [
            'company_industries' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    /**
     * Get user permissions for the authenticated user.
     *
     * @return array
     */
    protected function getPermissions(): array
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $user) {
            return ['permissions_meta' => []];
        }

        return [
            'permissions_meta' => [
                'can_create' => $user->can('create', CompanyIndustry::class),
                'can_view_any' => $user->can('viewAny', CompanyIndustry::class),
            ],
        ];
    }

    /**
     * Get base data for the view.
     *
     * @return array
     */
    protected function baseData(): array
    {
        return [
            'sort_fields' => $this->sortingService->getAvailableSortFields(),
            'trash_filters' => $this->trashFilterService->getFilterOptions(),
        ];
    }

    /**
     * Find a company industry by ID with optional trashed records.
     *
     * @param  int $id
     * @param  bool $withTrashed
     *
     * @return CompanyIndustry
     */
    private function findCompanyIndustry(
        int $id,
        bool $withTrashed = false
    ): CompanyIndustry {
        $query = CompanyIndustry::query();

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->findOrFail($id);
    }

    /**
     * Apply sorting to the query.
     *
     * @param  Builder $query
     * @param  array $filters
     *
     * @return Builder
     */
    private function applySorting(Builder $query, array $filters): Builder
    {
        $query = $this->trashFilterService->applyFilter(
            $query,
            $filters['trashed'] ?? null
        );

        return $this->sortingService->applySorting(
            $query,
            $filters['sort_by'] ?? 'created_at',
            $filters['sort_direction'] ?? 'desc'
        );
    }
}
