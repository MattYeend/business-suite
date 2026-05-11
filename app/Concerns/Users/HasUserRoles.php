<?php

namespace App\Concerns\Users;

use Illuminate\Support\Collection;
use Spatie\Permission\Contracts\Role;

/**
 * Trait for managing specialized user roles beyond base admin/user roles.
 *
 * @mixin \Spatie\Permission\Traits\HasRoles
 *
 * @property Collection $roles
 * @property Collection $permissions
 * @property Collection $teams
 * @property Collection $specialisedRoles
 *
 * @property-read Collection $specialisedRoles
 */
trait HasUserRoles
{
    /**
     * Assign one or more specialised roles to the user.
     *
     * @param  string|array|Role|Collection $roles
     *
     * @return self
     */
    public function assignSpecialisedRole(
        string|array|Role|Collection $roles
    ): self {
        /** @var \Spatie\Permission\Traits\HasRoles $this */
        $this->assignRole($roles);
        return $this;
    }

    /**
     * Remove one or more specialised roles from the user.
     *
     * @param  string|array|Role $roles
     *
     * @return self
     */
    public function removeSpecialisedRole(string|array|Role $roles): self
    {
        /** @var \Spatie\Permission\Traits\HasRoles $this */
        $this->removeRole($roles);
        return $this;
    }

    /**
     * Get the user's specialised roles, excluding base roles.
     *
     * @return Collection
     */
    public function getSpecialisedRolesAttribute(): Collection
    {
        $baseRoles = ['super-admin', 'admin', 'user'];

        return $this->roles->filter(
            fn ($role) => ! in_array($role->name, $baseRoles)
        );
    }

    /**
     * Check if the user has any specialised roles.
     *
     * @return bool
     */
    public function hasSpecialisedRoles(): bool
    {
        return $this->specialisedRoles->isNotEmpty();
    }
}
