<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserQueryService
{
    public function __construct(
        protected UserSortingService $sortingService,
        protected UserTrashFilterService $trashFilterService
    ) {
    }

    /**
     * Get paginated users with filters.
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
     * Get a single user by ID.
     *
     * @param  int $id
     * @param  bool $withTrashed
     *
     * @return array
     */
    public function getById(int $id, bool $withTrashed = false): array
    {
        $query = User::query();

        if ($withTrashed) {
            $query->withTrashed();
        }

        $user = $query->with(['roles.permissions'])->findOrFail($id);

        return array_merge(
            ['user' => $this->formatUser($user)],
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
        $query = User::query();

        $query = $this->applyFilters($query, $filters);
        $query = $this->applySorting($query, $filters);

        return $query->with(['roles']);
    }

    /**
     * Apply search filter.
     *
     * @param  Builder $query
     * @param  string|null $search
     *
     * @return Builder
     */
    protected function applySearchFilter(
        Builder $query,
        ?string $search
    ): Builder {
        if (! isset($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    /**
     * Apply team filter.
     *
     * @param  Builder $query
     * @param  int|null $teamId
     *
     * @return Builder
     */
    protected function applyTeamFilter(
        Builder $query,
        ?int $teamId
    ): Builder {
        if ($teamId === null) {
            return $query;
        }

        return $query->where('team_id', $teamId);
    }

    /**
     * Apply role filter.
     *
     * @param  Builder $query
     * @param  string|null $role
     *
     * @return Builder
     */
    protected function applyRoleFilter(
        Builder $query,
        ?string $role
    ): Builder {
        if (! isset($role)) {
            return $query;
        }

        return match ($role) {
            'super_admin' => $query->where('is_super_admin', true),
            'admin' => $query->where('is_admin', true),
            'user' => $query->where('is_user', true),
            default => $query->whereHas(
                'roles',
                fn ($q) => $q->where('name', $role)
            ),
        };
    }

    /**
     * Apply status filter.
     *
     * @param  Builder $query
     * @param  string|null $status
     *
     * @return Builder
     */
    protected function applyStatusFilter(
        Builder $query,
        ?string $status
    ): Builder {
        if (! isset($status)) {
            return $query;
        }

        return match ($status) {
            'real' => $query->where('is_real', true),
            'test' => $query->where('is_real', false),
            default => $query,
        };
    }

    /**
     * Paginate the query and return as plain array.
     *
     * @param  Builder $query
     * @param  int $perPage
     *
     * @return array
     */
    protected function paginate(
        Builder $query,
        int $perPage
    ): array {
        $paginator = $query->paginate($perPage);

        return [
            'users' => $paginator->items(),
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
     * Format a single user.
     *
     * @param  User $user
     *
     * @return array
     */
    protected function formatUser(User $user): array
    {
        return array_merge(
            $this->getBaseData($user),
            $this->getTimeZoneData($user),
            $this->getTeamData($user),
            $this->getRoleData($user),
            $this->getInitialsAndDisplayData($user),
            $this->getMetaData($user),
            $this->getDateData($user),
        );
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
                'can_create' => $user->can('create', User::class),
                'can_view_any' => $user->can('viewAny', User::class),
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
     * Apply all filters to the query.
     *
     * @param  Builder $query
     * @param  array $filters
     *
     * @return Builder
     */
    private function applyFilters(Builder $query, array $filters): Builder
    {
        return $this->applyStatusFilter(
            $this->applyRoleFilter(
                $this->applyTeamFilter(
                    $this->applySearchFilter(
                        $query,
                        $filters['search'] ?? null
                    ),
                    $filters['team_id'] ?? null
                ),
                $filters['role'] ?? null
            ),
            $filters['status'] ?? null
        );
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

    /**
     * Get the user's role display name.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getBaseData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
        ];
    }

    /**
     * Get the user's timezone and locale.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getTimeZoneData(User $user): array
    {
        return [
            'timezone' => $user->timezone,
            'locale' => $user->locale,
        ];
    }

    /**
     * Get the user's team data.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getTeamData(User $user): array
    {
        return [
            'team_id' => $user->team_id,
            'team_name' => $user->teamName,
        ];
    }

    /**
     * Get the user's role flags.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getRoleData(User $user): array
    {
        return [
            'is_user' => $user->is_user,
            'is_admin' => $user->is_admin,
            'is_super_admin' => $user->is_super_admin,
            'is_real' => $user->is_real,
        ];
    }

    /**
     * Get the user's initials and role display data.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getInitialsAndDisplayData(User $user): array
    {
        return [
            'initials' => $user->initials,
            'role_display' => $user->roleDisplay,
            'primary_role' => $user->primaryRole,
            'roles_list' => $user->rolesList,
            'roles' => $user->roles,
        ];
    }

    /**
     * Get the user's meta data.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getMetaData(User $user): array
    {
        return [
            'meta' => $user->meta,
        ];
    }

    /**
     * Get the user's date data.
     *
     * @param  User $user
     *
     * @return array
     */
    private function getDateData(User $user): array
    {
        return [
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'deleted_at' => $user->deleted_at,
        ];
    }
}
