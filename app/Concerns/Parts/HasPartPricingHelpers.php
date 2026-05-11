<?php

namespace App\Concerns\Parts;

/**
 * Part helper methods.
 */
trait HasPartPricingHelpers
{
    use HasPartPricingChecks,
        HasPartPriceCalculations,
        HasPartPriceFormatting;
}
