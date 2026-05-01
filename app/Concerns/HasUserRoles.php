<?php

namespace App\Concerns;

use Illuminate\Support\Collection;

/**
 * @mixin \App\Models\User
 * @mixin \Spatie\Permission\Traits\HasRoles
 *
 * @method $this assignRole(string|array $roles)
 * @method $this removeRole(string|array $roles)
 *
 * @property \Illuminate\Support\Collection $roles
 *
 * @property-read \Illuminate\Support\Collection $specialisedRoles
 */
trait HasUserRoles
{
    /**
     * Assign one or more specialised roles to the user.
     *
     * @param  string|array $roles Role name(s) to assign.
     *
     * @return self
     */
    public function assignSpecialisedRole(string|array $roles): self
    {
        $this->assignRole($roles);
        return $this;
    }

    /**
     * Remove one or more specialised roles from the user.
     *
     * @param  string|array $roles Role name(s) to remove.
     *
     * @return self
     */
    public function removeSpecialisedRole(string|array $roles): self
    {
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
