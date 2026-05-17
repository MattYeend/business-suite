<?php

namespace App\Services\BillOfMaterialItems;

use App\Models\BillOfMaterialItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class BOMItemQueryService
{
    /**
     * Inject the required services into the query service.
     *
     * @param BOMItemSortingService $sortingService
     * @param BOMItemTrashFilterService $trashFilterService
     * @param BOMItemFilterService $filterService
     * @param BOMItemFormatterService $formatterService
     */
    public function __construct(
        protected BOMItemSortingService $sortingService,
        protected BOMItemTrashFilterService $trashFilterService,
        protected BOMItemFilterService $filterService,
        protected BOMItemFormatterService $formatterService
    ) {
    }

    /**
     * Get paginated BOMs with filters.
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
     * Get a single BOMItem by ID.
     *
     * @param  int $id
     * @param  bool $withTrashed
     *
     * @return array
     */
    public function getById(int $id, bool $withTrashed = false): array
    {
        $billOfMaterialItem = $this->findCompany($id, $withTrashed);

        return array_merge(
            [
                'billOfMaterialItem' => $this->formatterService->format(
                    $billOfMaterialItem
                ),
            ],
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
        $query = BillOfMaterialItem::query();
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
            'billOfMaterialItem' => $paginator->items(),
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
                'can_create' => $user->can('create', BillOfMaterialItem::class),
                'can_view_any' => $user->can('viewAny', BillOfMaterialItem::class),
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
     * Find a BOMItem by ID with optional trashed records.
     *
     * @param  int $id
     * @param  bool $withTrashed
     *
     * @return BillOfMaterialItem
     */
    private function findCompany(
        int $id,
        bool $withTrashed = false
    ): BillOfMaterialItem {
        $query = BillOfMaterialItem::query();

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
