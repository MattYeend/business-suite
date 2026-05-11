<?php

namespace App\Concerns\Parts;

use App\Models\Part;

/**
 * Part state helper methods.
 *
 * @property string $type
 * @property string $status
 * @property bool $is_active
 * @property bool $is_purchasable
 * @property bool $is_sellable
 * @property bool $is_manufactured
 * @property bool $is_serialised
 * @property bool $is_batch_tracked
 * @property bool $is_real
 */
trait HasPartStateHelpers
{
    /**
     * Determine whether the part is of a given type.
     *
     * @return bool
     */
    public function isType(string $type): bool
    {
        return $this->type === $type;
    }

    /**
     * Check if part is a raw material.
     *
     * @return bool
     */
    public function isRawMaterial(): bool
    {
        return $this->type === Part::TYPE_RAW_MATERIAL;
    }

    /**
     * Check if part is a finished good.
     *
     * @return bool
     */
    public function isFinishedGood(): bool
    {
        return $this->type === Part::TYPE_FINISHED_GOOD;
    }

    /**
     * Check if part is a consumable.
     *
     * @return bool
     */
    public function isConsumable(): bool
    {
        return $this->type === Part::TYPE_CONSUMABLE;
    }

    /**
     * Check if part is a spare part.
     *
     * @return bool
     */
    public function isSparePart(): bool
    {
        return $this->type === Part::TYPE_SPARE_PART;
    }

    /**
     * Check if part is a sub-assembly.
     *
     * @return bool
     */
    public function isSubAssembly(): bool
    {
        return $this->type === Part::TYPE_SUB_ASSEMBLY;
    }

    /**
     * Determine whether the part has a given status.
     *
     * @return bool
     */
    public function hasStatus(string $status): bool
    {
        return $this->status === $status;
    }

    /**
     * Check if part status is active.
     *
     * @return bool
     */
    public function isStatusActive(): bool
    {
        return $this->status === Part::STATUS_ACTIVE;
    }

    /**
     * Check if part is discontinued.
     *
     * @return bool
     */
    public function isDiscontinued(): bool
    {
        return $this->status === Part::STATUS_DISCONTINUED;
    }

    /**
     * Check if part is pending.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === Part::STATUS_PENDING;
    }

    /**
     * Check if part status is out of stock.
     *
     * @return bool
     */
    public function isStatusOutOfStock(): bool
    {
        return $this->status === Part::STATUS_OUT_OF_STOCK;
    }
}
