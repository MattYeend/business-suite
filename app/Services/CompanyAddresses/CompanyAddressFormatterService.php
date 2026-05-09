<?php

namespace App\Services\CompanyAddresses;

use App\Models\CompanyAddress;

class CompanyAddressFormatterService
{
    /**
     * Format a single company address with all data.
     *
     * @param  CompanyAddress $address
     *
     * @return array
     */
    public function format(CompanyAddress $address): array
    {
        return [
            'id' => $address->id,
            'company_id' => $address->company_id,
            'type' => $address->type,
            'address_line_1' => $address->address_line_1,
            'address_line_2' => $address->address_line_2,
            'city' => $address->city,
            'county' => $address->county,
            'postal_code' => $address->postal_code,
            'country' => $address->country,
            'meta' => $address->meta,
            'created_at' => $address->created_at,
            'updated_at' => $address->updated_at,
            'deleted_at' => $address->deleted_at,
            'restored_at' => $address->restored_at,
            'created_by' => $address->created_by,
            'updated_by' => $address->updated_by,
            'deleted_by' => $address->deleted_by,
            'restored_by' => $address->restored_by,
        ];
    }
}
