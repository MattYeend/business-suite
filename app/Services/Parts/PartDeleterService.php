<?php

namespace App\Services\Parts;

use App\Models\Part;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PartDeleterService
{
    /**
     * Inject the required services into the deleter service.
     *
     * @param  PartLogService $logService
     *
     * @return void
     */
    public function __construct(
        protected PartLogService $logService
    ) {
    }

    /**
     * Soft delete a part.
     *
     * @param  Part $part
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(
        Part $part,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($part, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $part->deleted_by = $deletedBy;
            $part->save();

            $result = $part->delete();

            $this->logService->logDeletion($part, $actor, $deletedBy);

            return $result;
        });
    }

    /**
     * Force delete a part (permanent deletion).
     *
     * @param  Part $part
     * @param  int|null $deletedBy
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function forceDelete(
        Part $part,
        ?int $deletedBy = null
    ): bool {
        return DB::transaction(function () use ($part, $deletedBy) {
            $actor = User::findOrFail($deletedBy);
            $this->logService->logForceDeletion($part, $actor, $deletedBy);

            return $part->forceDelete();
        });
    }

    /**
     * Delete multiple parts.
     *
     * @param  array $partIds
     * @param  int|null $deletedBy
     *
     * @return int Number of parts deleted
     *
     * @throws \Exception
     */
    public function deleteMultiple(
        array $partIds,
        ?int $deletedBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($partIds, $deletedBy, &$count) {
            $parts = Part::whereIn('id', $partIds)->get();

            foreach ($parts as $part) {
                if ($this->delete($part, $deletedBy)) {
                    $count++;
                }
            }
        });

        return $count;
    }
}
