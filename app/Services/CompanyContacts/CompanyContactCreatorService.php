<?php

namespace App\Services\CompanyContacts;

use App\Models\CompanyContact;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CompanyContactCreatorService
{
    public function __construct(
        protected CompanyContactDataPreparationService $dataPreparation,
        protected CompanyContactLogService $logService
    ) {
    }

    /**
     * Create a new company contact.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return CompanyContact
     *
     * @throws ModelNotFoundException
     */
    public function create(array $data, int $createdBy): CompanyContact
    {
        $actor = User::findOrFail($createdBy);

        return DB::transaction(function () use ($data, $createdBy, $actor) {
            $contact = $this->createCompanyContact($data, $createdBy);
            $this->logService->logCreation($contact, $actor, $createdBy);

            return $contact;
        });
    }

    /**
     * Create the company contact record.
     *
     * @param  array $data
     * @param  int $createdBy
     *
     * @return CompanyContact
     */
    protected function createCompanyContact(
        array $data,
        int $createdBy
    ): CompanyContact {
        $contactData = $this->dataPreparation->prepareForCreation(
            $data,
            $createdBy
        );

        return CompanyContact::create($contactData);
    }
}
