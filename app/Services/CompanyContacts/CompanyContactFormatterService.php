<?php

namespace App\Services\CompanyContacts;

use App\Models\CompanyContact;

class CompanyContactFormatterService
{
    /**
     * Format a single company contact with all data.
     *
     * @param  CompanyContact $contact
     *
     * @return array
     */
    public function format(CompanyContact $contact): array
    {
        return [
            'id' => $contact->id,
            'first_name' => $contact->first_name,
            'last_name' => $contact->last_name,
            'company_id' => $contact->company_id,
            'email' => $contact->email,
            'phone' => $contact->phone,
            'mobile' => $contact->mobile,
            'job_title' => $contact->job_title,
            'meta' => $contact->meta,
            'created_at' => $contact->created_at,
            'updated_at' => $contact->updated_at,
            'deleted_at' => $contact->deleted_at,
            'restored_at' => $contact->restored_at,
            'created_by' => $contact->created_by,
            'updated_by' => $contact->updated_by,
            'deleted_by' => $contact->deleted_by,
            'restored_by' => $contact->restored_by,
        ];
    }
}
