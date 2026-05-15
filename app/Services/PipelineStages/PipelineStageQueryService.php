<?php

namespace App\Services\PipelineStages;

use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class PipelineStageQueryService
{
    /**
     * Inject the required services into the query service.
     *
     * @param PipelineStageSortingService $sortingService
     * @param PipelineStageTrashFilterService $trashFilterService
     * @param PipelineStageFilterService $filterService
     * @param PipelineStageFormatterService $formatterService
     */
    public function __construct(
        protected PipelineStageSortingService $sortingService,
        protected PipelineStageTrashFilterService $trashFilterService,
        protected PipelineStageFilterService $filterService,
        protected PipelineStageFormatterService $formatterService
    ) {
    }

    /**
     * Get paginated pipeline stages with filters.
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
     * Get a single pipeline stage by ID.
     *
     * @param  int $id
     * @param  bool $withTrashed
     *
     * @return array
     */
    public function getById(int $id, bool $withTrashed = false): array
    {
        $stage = $this->findPipelineStage($id, $withTrashed);

        return array_merge(
            ['pipeline_stage' => $this->formatterService->format($stage)],
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
        $query = PipelineStage::query();
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
            'company_addresses' => $paginator->items(),
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
                'can_create' => $user->can('create', PipelineStage::class),
                'can_view_any' => $user->can('viewAny', PipelineStage::class),
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
     * Find a pipeline stage by ID with optional trashed records.
     *
     * @param  int $id
     * @param  bool $withTrashed
     *
     * @return PipelineStage
     */
    private function findPipelineStage(
        int $id,
        bool $withTrashed = false
    ): PipelineStage {
        $query = PipelineStage::query();

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
