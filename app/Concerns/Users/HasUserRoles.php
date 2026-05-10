<?php

namespace App\Concerns\Users;

use Illuminate\Support\Collection;
use Spatie\Permission\Contracts\Role;

/**
 * Trait for managing specialized user roles beyond base admin/user roles.
 *
 * This trait requires the model to use Spatie\Permission\Traits\HasRoles.
 * The assignRole() and removeRole() methods are provided by that trait.
 *
 * @mixin \Spatie\Permission\Traits\HasRoles
 *
 * @property \Illuminate\Support\Collection $roles
 * @property \Illuminate\Support\Collection $permissions
 * @property \Illuminate\Support\Collection $teams
 * @property \Illuminate\Support\Collection $specialisedRoles
 *
 * @property-read \Illuminate\Support\Collection $specialisedRoles
 */
trait HasUserRoles
{
    /**
     * Assign one or more specialised roles to the user.
     *
     * This method wraps the HasRoles::assignRole() method from Spatie.
     *
     * @param  string|array|Role|\Illuminate\Support\Collection $roles
     * Role name(s) to assign.
     *
     * @return self
     */
    public function assignSpecialisedRole(
        string|array|Role|Collection $roles
    ): self {
        // Call parent trait method - provided by
        // Spatie\Permission\Traits\HasRoles
        /** @var \Spatie\Permission\Traits\HasRoles $this */
        $this->assignRole($roles);
        return $this;
    }

    /**
     * Remove one or more specialised roles from the user.
     *
     * This method wraps the HasRoles::removeRole() method from Spatie.
     *
     * @param  string|array|Role $roles Role name(s) to remove.
     *
     * @return self
     */
    public function removeSpecialisedRole(string|array|Role $roles): self
    {
        // Call parent trait method -
        // provided by Spatie\Permission\Traits\HasRoles
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
