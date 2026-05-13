<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class ProductPolicyAuthorisationService
{
    /**
     * Inject the required services into the policy authorisation service.
     *
     * @param  ProductActiveCheckerService $activeChecker
     * @param  UserRoleCheckerService $roleChecker
     */
    public function __construct(
        protected ProductActiveCheckerService $activeChecker,
        protected UserRoleCheckerService $roleChecker
    ) {
    }

    /**
     * Check if user is a regular user, admin, or super admin.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function isUser(User $user): bool
    {
        return $this->roleChecker->isUser($user);
    }

    /**
     * Check if user is admin or super admin.
     *
     * @param  User $user
     *
     * @return bool
     */
    public function isAdmin(User $user): bool
    {
        return $this->roleChecker->isAdmin($user);
    }

    /**
     * Check if product is active (not soft-deleted).
     *
     * @param  Product $product
     *
     * @return bool
     */
    public function isActive(Product $product): bool
    {
        return $this->activeChecker->isActive($product);
    }

    /**
     * Check if product is soft-deleted.
     *
     * @param  Product $product
     *
     * @return bool
     */
    public function isTrashed(Product $product): bool
    {
        return $this->activeChecker->isTrashed($product);
    }

    /**
     * Determine whether the user can view the model.
     * Only admins can view companies.
     *
     * @param  User $user
     * @param  Product $product
     *
     * @return bool
     */
    public function canView(User $user, Product $product): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $product
        );
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  Product $product
     *
     * @return bool
     */
    public function canUpdate(User $user, Product $product): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->isActive(
            $product
        );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  Product $product
     *
     * @return bool
     */
    public function canDelete(User $user, Product $product): bool
    {
        return $this->isAdmin($user) && $this->activeChecker->canBeModified(
            $product
        );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User $user
     * @param  Product $product
     *
     * @return bool
     */
    public function canRestore(User $user, Product $product): bool
    {
        return $this->isAdmin($user) &&
            $this->activeChecker->canBeRestoredOrForceDeleted($product);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User $user
     * @param  Product $product
     *
     * @return bool
     */
    public function canForceDelete(User $user, Product $product): bool
    {
        return $this->activeChecker->canUserPerformAction(
            $product,
            'restoreOrForceDelete',
            $user
        );
    }
}
