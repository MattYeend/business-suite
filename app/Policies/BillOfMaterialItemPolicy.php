<?php

namespace App\Policies;

use App\Models\BillOfMaterialItem;
use App\Models\User;

class BillOfMaterialItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BillOfMaterialItem $billOfMaterialItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BillOfMaterialItem $billOfMaterialItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BillOfMaterialItem $billOfMaterialItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BillOfMaterialItem $billOfMaterialItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BillOfMaterialItem $billOfMaterialItem): bool
    {
        return false;
    }
}
