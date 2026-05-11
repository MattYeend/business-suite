<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePartRequest;
use App\Http\Requests\UpdatePartRequest;
use App\Models\Part;
use App\Services\Parts\PartLogService;
use App\Services\Parts\PartManagementService;
use App\Services\Parts\PartQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PartController extends Controller
{
    use AuthorizesRequests;
    /**
     * Inject the required services into the controller.
     *
     * @param  PartLogService $logger
     * @param  PartManagementService $management
     * @param  PartQueryService $query
     */
    public function __construct(
        protected PartLogService $logger,
        protected PartManagementService $management,
        protected PartQueryService $query,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * Also includes the authenticated user's permissions for the
     * Part resource, so the frontend can conditionally render
     * create/view controls.
     *
     * Authorises via the 'viewAny' policy before returning data.
     *
     * @param  Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Part::class);

        $parts = $this->query->getPaginated($request->all());

        return response()->json($parts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is handled upstream by StorePartRequest.
     *
     * After storing, an audit log entry is written against the
     * authenticated user.
     *
     * @param  StorePartRequest $request
     *
     * @return JsonResponse
     */
    public function store(StorePartRequest $request): JsonResponse
    {
        $part = $this->management->store($request);
        $auth = auth()->user();
        $this->logger->logCreation(
            $part,
            $auth,
            $auth->id,
        );

        return response()->json($part, 201);
    }

    /**
     * Display the specified resource.
     *
     * Returns a single part by its model binding.
     *
     * Authorises via the 'view' policy before returning data.
     *
     * @param  Part $part
     *
     * @return JsonResponse
     */
    public function show(Part $part): JsonResponse
    {
        $this->authorize('view', $part);

        $part = $this->query->getById($part->id);

        return response()->json($part);
    }

    /**
     * Update the specified resource in storage.
     *
     * Validation is handled upstream by UpdatePartRequest, which
     * also implicitly authorises the operation via its authorize() method.
     *
     * After updating, an audit log entry is written against the
     * authenticated user.
     *
     * @param  UpdatePartRequest $request
     * @param  Part $part
     *
     * @return JsonResponse
     */
    public function update(
        UpdatePartRequest $request,
        Part $part
    ): JsonResponse {
        $part = $this->management->update(
            $request,
            $part
        );

        $auth = auth()->user();

        $this->logger->logUpdate(
            $part,
            $auth,
            $auth->id,
        );

        return response()->json($part);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Authorises via the 'delete' policy before proceeding.
     *
     * The audit log entry is written before the deletion so that the
     * part instance is still fully accessible during logging.
     *
     * @param  Part $part
     *
     * @return JsonResponse
     */
    public function destroy(Part $part): JsonResponse
    {
        $this->authorize('delete', $part);
        $auth = auth()->user();

        $this->logger->logDeletion(
            $part,
            $auth,
            $auth->id,
        );

        $this->management->destroy($part);

        return response()->json(null, 204);
    }

    /**
     * Restore the specified part from soft deletion.
     *
     * Looks up the part including trashed records, then
     * checks if it exists and is trashed before authorization.
     * Returns 404 if the part is not currently soft-deleted.
     *
     * @param  int|string $id
     *
     * @return JsonResponse
     *
     * @throws HttpException
     */
    public function restore($id): JsonResponse
    {
        $part = Part::withTrashed()->findOrFail($id);

        if (! $part->trashed()) {
            abort(404);
        }

        $this->authorize('restore', $part);

        $part = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->logRestoration(
            $part,
            $auth,
            $auth->id,
        );

        return response()->json($part);
    }

    /**
     * Permanently delete the specified part from storage.
     *
     * Looks up the part including trashed records, then
     * authorises via the 'forceDelete' policy. This action is irreversible.
     *
     * The audit log entry is written before the permanent deletion so
     * that the part instance is still fully accessible during
     * logging.
     *
     * @param  int|string $id
     *
     * @return JsonResponse
     */
    public function forceDelete($id): JsonResponse
    {
        $part = Part::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $part);

        $auth = auth()->user();

        $this->logger->logForceDeletion(
            $part,
            $auth,
            $auth->id,
        );

        $this->management->forceDelete((int) $id);

        return response()->json(null, 204);
    }

    /**
     * Soft delete multiple part in bulk.
     *
     * Expects a 'ids' array in the request containing part part IDs
     * to delete. Each part part is authorised individually via the
     * 'delete' policy.
     *
     * @param  Request $request
     *
     * @return JsonResponse
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:parts,id',
        ]);

        $auth = auth()->user();
        $deleted = $this->management->bulkDelete(
            $request->input('ids'),
            $auth,
            fn ($part) => $this->authorize('delete', $part)
        );

        return response()->json([
            'message' => 'Part deleted successfully',
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
        ]);
    }

    /**
     * Restore multiple part from soft deletion in bulk.
     *
     * @param  Request $request
     *
     * @return JsonResponse
     */
    public function bulkRestore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer',
        ]);

        $this->validateBulkRestoreIds($validated['ids']);

        $auth = auth()->user();
        $restored = $this->management->bulkRestore(
            $validated['ids'],
            $auth,
            fn ($part) => $this->authorize('restore', $part)
        );

        return response()->json([
            'message' => 'Part restored successfully',
            'restored_count' => count($restored),
            'restored_ids' => $restored,
        ]);
    }

    /**
     * Validate that all IDs exist in database (including trashed).
     *
     * @param  array $ids
     *
     * @return void
     *
     * @throws ValidationException
     */
    protected function validateBulkRestoreIds(array $ids): void
    {
        foreach ($ids as $index => $id) {
            $part = Part::withTrashed()->find($id);

            if (! $part) {
                throw ValidationException::withMessages([
                    "ids.{$index}" => ["The selected ids.{$index} is invalid."],
                ]);
            }
        }
    }
}
