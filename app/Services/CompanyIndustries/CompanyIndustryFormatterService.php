<?php

namespace App\Services\CompanyIndustries;

use App\Models\CompanyIndustry;

class CompanyIndustryFormatterService
{
    /**
     * Format a single company industry with all data.
     *
     * @param  CompanyIndustry $industry
     *
     * @return array
     */
    public function format(CompanyIndustry $industry): array
    {
        return [
            'id' => $industry->id,
            'name' => $industry->name,
            'slug' => $industry->slug,
            'meta' => $industry->meta,
            'created_at' => $industry->created_at,
            'updated_at' => $industry->updated_at,
            'deleted_at' => $industry->deleted_at,
            'restored_at' => $industry->restored_at,
            'created_by' => $industry->created_by,
            'updated_by' => $industry->updated_by,
            'deleted_by' => $industry->deleted_by,
            'restored_by' => $industry->restored_by,
        ];
    }
}
