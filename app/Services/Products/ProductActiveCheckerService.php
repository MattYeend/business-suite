<?php

namespace App\Services\Products;

use App\Models\Product;
use App\Models\User;
use App\Services\UserRoleCheckerService;

class ProductActiveCheckerService
{
    /**
     * Inject the required services into the active checker service.
     *
     * @param UserRoleCheckerService $roleChecker
     */
    public function __construct(
        protected UserRoleCheckerService $roleChecker
    ) {
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
        return ! $product->trashed();
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
        return $product->trashed();
    }

    /**
     * Check if product is active (not soft-deleted) and can be
     * updated/deleted.
     *
     * @param  Product $product
     *
     * @return bool
     */
    public function canBeModified(Product $product): bool
    {
        return $this->isActive($product);
    }

    /**
     * Check if product is soft-deleted and can be restored/force-deleted.
     *
     * @param  Product $product
     *
     * @return bool
     */
    public function canBeRestoredOrForceDeleted(
        Product $product
    ): bool {
        return $this->isTrashed($product);
    }

    /**
     * Check if user can modify product (update/delete) or restore/force-delete
     * product based on its active status.
     *
     * @param  Product $product
     * @param  string $action
     * @param  User $user
     *
     * @return bool
     */
    public function canUserPerformAction(
        Product $product,
        string $action,
        User $user
    ): bool {
        if ($action === 'modify') {
            return $this->roleChecker->isAdmin($user) && $this->canBeModified(
                $product
            );
        }

        if ($action === 'restoreOrForceDelete') {
            return $this->roleChecker->isAdmin($user) &&
                $this->canBeRestoredOrForceDeleted($product);
        }

        throw new \InvalidArgumentException("Invalid action: {$action}");
    }
}
