<?php

namespace App\Concerns\Parts;

/**
 * Dimension aggregation and formatting.
 *
 * @property float|null $length
 * @property float|null $width
 * @property float|null $height
 */
trait HasPartDimensions
{
    /**
     * Check if part has dimensions.
     *
     * @return bool
     */
    public function hasDimensions(): bool
    {
        return in_array(true, [
            $this->hasLength(),
            $this->hasWidth(),
            $this->hasHeight(),
        ], true);
    }

    /**
     * Get dimensions as formatted string.
     *
     * @return string|null
     */
    public function getDimensionsAttribute(): ?string
    {
        $dimensions = [];

        $this->appendDimension($dimensions, $this->length, 'L');
        $this->appendDimension($dimensions, $this->width, 'W');
        $this->appendDimension($dimensions, $this->height, 'H');

        return ! isset($dimensions)
            ? null
            : implode(' x ', $dimensions);
    }

    /**
     * Append formatted dimension value.
     *
     * @param array<int, string> $dimensions
     * @param float|null $value
     * @param string $suffix
     *
     * @return void
     */
    protected function appendDimension(
        array &$dimensions,
        ?float $value,
        string $suffix
    ): void {
        if ($value !== null) {
            $dimensions[] = "{$value}{$suffix}";
        }
    }
}
