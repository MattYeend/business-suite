<?php

namespace App\Services\Parts;

use App\Http\Requests\StorePartRequest;
use App\Http\Requests\UpdatePartRequest;
use App\Models\Part;
use App\Models\User;

class PartManagementService
{
    /**
     * Inject the required services into the management service.
     *
     * @param  PartCreatorService $creator
     * @param  PartUpdaterService $updater
     * @param  PartDeleterService $destructor
     * @param  PartRestorerService $restorer
     */
    public function __construct(
        protected PartCreatorService $creator,
        protected PartUpdaterService $updater,
        protected PartDeleterService $destructor,
        protected PartRestorerService $restorer,
    ) {
    }

    /**
     * Create a new part.
     *
     * @param StorePartRequest $request
     *
     * @return Part
     */
    public function store(
        StorePartRequest $request
    ): Part {
        return $this->creator->create(
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Update an existing part.
     *
     * @param  UpdatePartRequest $request
     * @param  Part $part
     *
     * @return Part
     */
    public function update(
        UpdatePartRequest $request,
        Part $part
    ): Part {
        return $this->updater->update(
            $part,
            $request->validated(),
            $request->user()->id
        );
    }

    /**
     * Soft delete a part.
     *
     * @param  Part $part
     *
     * @return void
     */
    public function destroy(Part $part): void
    {
        $this->destructor->delete($part, auth()->id());
    }

    /**
     * Restore a soft-deleted part.
     *
     * @param  int $id
     *
     * @return Part
     */
    public function restore(int $id): Part
    {
        $part = Part::withTrashed()->findOrFail($id);
        return $this->restorer->restore($part, auth()->id());
    }

    /**
     * Force delete a part, permanently removing it from the
     * database.
     *
     * @param  int $id
     *
     * @return void
     */
    public function forceDelete(int $id): void
    {
        $part = Part::withTrashed()->findOrFail($id);
        $this->destructor->forceDelete($part, auth()->id());
    }

    /**
     * Bulk restore parts.
     *
     * @param  array $ids
     * @param  User $actor
     * @param  callable $authoriseCallback
     *
     * @return array
     */
    public function bulkRestore(
        array $ids,
        User $actor,
        callable $authoriseCallback
    ): array {
        $restored = [];

        foreach ($ids as $id) {
            $part = Part::withTrashed()->findOrFail($id);
            $authoriseCallback($part);

            if ($part->trashed()) {
                $this->restorer->restore($part, $actor->id);
                $restored[] = $id;
            }
        }

        return $restored;
    }

    /**
     * Bulk soft delete parts.
     *
     * @param  array $ids
     * @param  User $actor
     * @param  callable $authoriseCallback
     *
     * @return array
     */
    public function bulkDelete(
        array $ids,
        User $actor,
        callable $authoriseCallback
    ): array {
        $deleted = [];

        foreach ($ids as $id) {
            $part = Part::findOrFail($id);
            $authoriseCallback($part);

            $this->destructor->delete($part, $actor->id);
            $deleted[] = $id;
        }

        return $deleted;
    }
}
