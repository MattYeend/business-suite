<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasDataOwnership
{
    /**
     * Scope a query to only include records owned by the user.
     */
    public function scopeOwnedBy(Builder $query, $userId): Builder
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('created_by', $userId)
              ->orWhere('assigned_to', $userId)
              ->orWhereHas('assignedUsers', function ($q) use ($userId) {
                  $q->where('user_id', $userId);
              });
        });
    }

    /**
     * Scope a query based on user's data access level.
     */
    public function scopeAccessibleBy(Builder $query, $user): Builder
    {
        // Super admin and those with "view all data" see everything
        if ($user->hasRole('super-admin') || $user->can('view all data')) {
            return $query;
        }

        // Users with "manage own data only" see only their records
        if ($user->can('manage own data only')) {
            return $query->ownedBy($user->id);
        }

        // Default: no access
        return $query->whereRaw('1 = 0');
    }
}
