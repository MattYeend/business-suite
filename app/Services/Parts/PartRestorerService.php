<?php

namespace App\Services\Parts;

use App\Models\Part;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PartRestorerService
{
    /**
     * Inject the required services into the resorer service.
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
     * Restore a soft-deleted part.
     *
     * @param  Part $part
     * @param  int|null $restoredBy
     *
     * @return Part
     *
     * @throws \Exception
     */
    public function restore(
        Part $part,
        ?int $restoredBy = null
    ): Part {
        return DB::transaction(function () use ($part, $restoredBy) {
            $actor = User::findOrFail($restoredBy);

            $part->restored_by = $restoredBy;
            $part->restored_at = now();
            $part->save();

            // restore() returns boolean, so we don't assign it
            $part->restore();

            $this->logService->logRestoration($part, $actor, $restoredBy);

            // Return the fresh model instance
            return $part->fresh();
        });
    }

    /**
     * Restore multiple soft-deleted parts.
     *
     * @param  array $partIds
     * @param  int|null $restoredBy
     *
     * @return int Number of parts restored
     *
     * @throws \Exception
     */
    public function restoreMultiple(
        array $partIds,
        ?int $restoredBy = null
    ): int {
        $count = 0;

        DB::transaction(function () use ($partIds, $restoredBy, &$count) {
            /** @var Collection<int,Part> $parts */
            $parts = Part::withTrashed()
                ->whereIn('id', $partIds)
                ->get();

            foreach ($parts as $part) {
                if ($part->trashed()) {
                    $this->restore($part, $restoredBy);
                    $count++;
                }
            }
        });

        return $count;
    }
}
