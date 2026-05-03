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

        $query = $this->applySearchFilter($query, $filters['search'] ?? null);
        $query = $this->applyTeamFilter($query, $filters['team_id'] ?? null);
        $query = $this->applyRoleFilter($query, $filters['role'] ?? null);
        $query = $this->applyStatusFilter($query, $filters['status'] ?? null);
        $query = $this->trashFilterService->applyFilter($query, $filters['trashed'] ?? null);
        $query = $this->sortingService->applySorting(
            $query,
            $filters['sort_by'] ?? 'created_at',
            $filters['sort_direction'] ?? 'desc'
        );

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
    protected function applySearchFilter(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
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
    protected function applyTeamFilter(Builder $query, ?int $teamId): Builder
    {
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
    protected function applyRoleFilter(Builder $query, ?string $role): Builder
    {
        if (empty($role)) {
            return $query;
        }

        return match ($role) {
            'super_admin' => $query->where('is_super_admin', true),
            'admin' => $query->where('is_admin', true),
            'user' => $query->where('is_user', true),
            default => $query->whereHas('roles', fn ($q) => $q->where('name', $role)),
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
    protected function applyStatusFilter(Builder $query, ?string $status): Builder
    {
        if (empty($status)) {
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
    protected function paginate(Builder $query, int $perPage): array
    {
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
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
            'timezone' => $user->timezone,
            'locale' => $user->locale,
            'team_id' => $user->team_id,
            'team_name' => $user->teamName,
            'is_user' => $user->is_user,
            'is_admin' => $user->is_admin,
            'is_super_admin' => $user->is_super_admin,
            'is_real' => $user->is_real,
            'initials' => $user->initials,
            'role_display' => $user->roleDisplay,
            'primary_role' => $user->primaryRole,
            'roles_list' => $user->rolesList,
            'roles' => $user->roles,
            'meta' => $user->meta,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'deleted_at' => $user->deleted_at,
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

        if (!$user) {
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
}
