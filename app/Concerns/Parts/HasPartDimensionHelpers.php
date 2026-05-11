<?php

namespace App\Concerns\Parts;

/**
 * Part dimension helper methods.
 *
 * @property float|null $weight
 * @property float|null $volume
 * @property int|null $lead_time_days
 */
trait HasPartDimensionHelpers
{
    /**
     * HasPartDimensions<HasPartDimensions>
     * HasPartLeadTimes<HasPartLeadTimes>
     * HasPartDimensionChecks<HasPartDimensionChecks>
     * HasPartMassProperties<HasPartMassProperties>
     */
    use HasPartDimensions,
        HasPartLeadTimes,
        HasPartDimensionChecks,
        HasPartMassProperties;
}
