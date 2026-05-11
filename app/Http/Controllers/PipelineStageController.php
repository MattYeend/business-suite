<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePipelineStageRequest;
use App\Http\Requests\UpdatePipelineStageRequest;
use App\Models\PipelineStage;
use App\Services\PipelineStages\PipelineStageLogService;
use App\Services\PipelineStages\PipelineStageManagementService;
use App\Services\PipelineStages\PipelineStageQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PipelineStageController extends Controller
{
    use AuthorizesRequests;
    /**
     * Inject the required services into the controller.
     *
     * @param  PipelineStageLogService $logger
     * @param  PipelineStageManagementService $management
     * @param  PipelineStageQueryService $query
     */
    public function __construct(
        protected PipelineStageLogService $logger,
        protected PipelineStageManagementService $management,
        protected PipelineStageQueryService $query,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * Also includes the authenticated user's permissions for the
     * PipelineStage resource, so the frontend can conditionally render
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
        $this->authorize('viewAny', PipelineStage::class);

        $pipelineStages = $this->query->getPaginated($request->all());

        return response()->json($pipelineStages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is handled upstream by StorePipelineStageRequest.
     *
     * After storing, an audit log entry is written against the
     * authenticated user.
     *
     * @param  StorePipelineStageRequest $request
     *
     * @return JsonResponse
     */
    public function store(StorePipelineStageRequest $request): JsonResponse
    {
        $pipelineStage = $this->management->store($request);
        $auth = auth()->user();
        $this->logger->logCreation(
            $pipelineStage,
            $auth,
            $auth->id,
        );

        return response()->json($pipelineStage, 201);
    }

    /**
     * Display the specified resource.
     *
     * Returns a single pipelineStage by its model binding.
     *
     * Authorises via the 'view' policy before returning data.
     *
     * @param  PipelineStage $pipelineStage
     *
     * @return JsonResponse
     */
    public function show(PipelineStage $pipelineStage): JsonResponse
    {
        $this->authorize('view', $pipelineStage);

        $pipelineStage = $this->query->getById($pipelineStage->id);

        return response()->json($pipelineStage);
    }

    /**
     * Update the specified resource in storage.
     *
     * Validation is handled upstream by UpdatePipelineStageRequest, which
     * also implicitly authorises the operation via its authorize() method.
     *
     * After updating, an audit log entry is written against the
     * authenticated user.
     *
     * @param  UpdatePipelineStageRequest $request
     * @param  PipelineStage $pipelineStage
     *
     * @return JsonResponse
     */
    public function update(
        UpdatePipelineStageRequest $request,
        PipelineStage $pipelineStage
    ): JsonResponse {
        $pipelineStage = $this->management->update(
            $request,
            $pipelineStage
        );

        $auth = auth()->user();

        $this->logger->logUpdate(
            $pipelineStage,
            $auth,
            $auth->id,
        );

        return response()->json($pipelineStage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Authorises via the 'delete' policy before proceeding.
     *
     * The audit log entry is written before the deletion so that the
     * pipelineStage pipelineStage instance is still fully accessible
     * during logging.
     *
     * @param  PipelineStage $pipelineStage
     *
     * @return JsonResponse
     */
    public function destroy(PipelineStage $pipelineStage): JsonResponse
    {
        $this->authorize('delete', $pipelineStage);
        $auth = auth()->user();

        $this->logger->logDeletion(
            $pipelineStage,
            $auth,
            $auth->id,
        );

        $this->management->destroy($pipelineStage);

        return response()->json(null, 204);
    }

    /**
     * Restore the specified pipelineStage from soft deletion.
     *
     * Looks up the pipelineStage including trashed records, then
     * checks if it exists and is trashed before authorization.
     * Returns 404 if the pipelineStage is not currently soft-deleted.
     *
     * @param  int|string $id
     *
     * @return JsonResponse
     *
     * @throws HttpException
     */
    public function restore($id): JsonResponse
    {
        $pipelineStage = PipelineStage::withTrashed()->findOrFail($id);

        if (! $pipelineStage->trashed()) {
            abort(404);
        }

        $this->authorize('restore', $pipelineStage);

        $pipelineStage = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->logRestoration(
            $pipelineStage,
            $auth,
            $auth->id,
        );

        return response()->json($pipelineStage);
    }

    /**
     * Permanently delete the specified pipelineStage from storage.
     *
     * Looks up the pipelineStage including trashed records, then
     * authorises via the 'forceDelete' policy. This action is irreversible.
     *
     * The audit log entry is written before the permanent deletion so
     * that the pipelineStage instance is still fully accessible during
     * logging.
     *
     * @param  int|string $id
     *
     * @return JsonResponse
     */
    public function forceDelete($id): JsonResponse
    {
        $pipelineStage = PipelineStage::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $pipelineStage);

        $auth = auth()->user();

        $this->logger->logForceDeletion(
            $pipelineStage,
            $auth,
            $auth->id,
        );

        $this->management->forceDelete((int) $id);

        return response()->json(null, 204);
    }

    /**
     * Soft delete multiple pipeline stage in bulk.
     *
     * Expects a 'ids' array in the request containing pipeline stage IDs
     * to delete. Each pipeline stage is authorised individually via the
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
            'ids.*' => 'required|integer|exists:pipeline_stages,id',
        ]);

        $auth = auth()->user();
        $deleted = $this->management->bulkDelete(
            $request->input('ids'),
            $auth,
            fn ($pipelineStage) => $this->authorize('delete', $pipelineStage)
        );

        return response()->json([
            'message' => 'PipelineStage deleted successfully',
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
        ]);
    }

    /**
     * Restore multiple Pipeline stages from soft deletion in bulk.
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
            fn ($pipelineStage) => $this->authorize('restore', $pipelineStage)
        );

        return response()->json([
            'message' => 'Pipeline stage restored successfully',
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
            $pipelineStage = PipelineStage::withTrashed()->find($id);

            if (! $pipelineStage) {
                throw ValidationException::withMessages([
                    "ids.{$index}" => ["The selected ids.{$index} is invalid."],
                ]);
            }
        }
    }
}
