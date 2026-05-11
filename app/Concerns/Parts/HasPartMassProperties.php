<?php

namespace App\Concerns\Parts;

/**
 * Part mass and physical property helpers.
 *
 * @property float|null $weight
 * @property float|null $volume
 */
trait HasPartMassProperties
{
    /**
     * Check if part has weight.
     *
     * @return bool
     */
    public function hasWeight(): bool
    {
        return $this->weight !== null;
    }

    /**
     * Check if part has volume.
     *
     * @return bool
     */
    public function hasVolume(): bool
    {
        return $this->volume !== null;
    }

    /**
     * Check if part has mass properties.
     *
     * @return bool
     */
    public function hasMassProperties(): bool
    {
        return $this->hasWeight()
            || $this->hasVolume();
    }
}
