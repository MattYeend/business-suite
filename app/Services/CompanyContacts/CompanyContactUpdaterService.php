<?php

namespace App\Services\CompanyContacts;

use App\Models\CompanyContact;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyContactUpdaterService
{
    public function __construct(
        protected CompanyContactDataPreparationService $dataPreparation,
        protected CompanyContactLogService $logService
    ) {
    }

    /**
     * Update an existing company contact.
     *
     * @param  CompanyContact $contact
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return CompanyContact
     *
     * @throws \Exception
     */
    public function update(
        CompanyContact $contact,
        array $data,
        ?int $updatedBy = null
    ): CompanyContact {
        return DB::transaction(function () use ($contact, $data, $updatedBy) {
            $actor = User::findOrFail($updatedBy);

            $this->updateCompanyContactData($contact, $data, $updatedBy);
            $this->logService->logUpdate($contact, $actor, $updatedBy);

            return $contact->fresh();
        });
    }

    /**
     * Update company contact data.
     *
     * @param  CompanyContact $contact
     * @param  array $data
     * @param  int|null $updatedBy
     *
     * @return void
     */
    protected function updateCompanyContactData(
        CompanyContact $contact,
        array $data,
        ?int $updatedBy
    ): void {
        $fillableData = $this->dataPreparation->prepareForUpdate(
            $data,
            $updatedBy
        );
        $contact->update($fillableData);
        $contact->save();
    }
}
