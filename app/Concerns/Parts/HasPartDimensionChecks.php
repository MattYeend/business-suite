<?php

namespace App\Concerns\Parts;

/**
 * Part dimension checks helper methods.
 *
 * @property float|null $length
 * @property float|null $width
 * @property float|null $height
 */
trait HasPartDimensionChecks
{
    /**
     * Check if the part has length.
     *
     * @return bool
     */
    public function hasLength(): bool
    {
        return $this->length !== null;
    }

    /**
     * Check if the part has width.
     *
     * @return bool
     */
    public function hasWidth(): bool
    {
        return $this->width !== null;
    }

    /**
     * Check if the part has height.
     *
     * @return bool
     */
    public function hasHeight(): bool
    {
        return $this->height !== null;
    }
}
