<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePipelineRequest;
use App\Http\Requests\UpdatePipelineRequest;
use App\Models\Pipeline;
use App\Services\Pipelines\PipelineLogService;
use App\Services\Pipelines\PipelineManagementService;
use App\Services\Pipelines\PipelineQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PipelineController extends Controller
{
    use AuthorizesRequests;
    /**
     * Inject the required services into the controller.
     *
     * @param  PipelineLogService $logger
     * @param  PipelineManagementService $management
     * @param  PipelineQueryService $query
     */
    public function __construct(
        protected PipelineLogService $logger,
        protected PipelineManagementService $management,
        protected PipelineQueryService $query,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * Also includes the authenticated user's permissions for the
     * Pipeline resource, so the frontend can conditionally render
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
        $this->authorize('viewAny', Pipeline::class);

        $pipelines = $this->query->getPaginated($request->all());

        return response()->json($pipelines);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is handled upstream by StorePipelineRequest.
     *
     * After storing, an audit log entry is written against the
     * authenticated user.
     *
     * @param  StorePipelineRequest $request
     *
     * @return JsonResponse
     */
    public function store(StorePipelineRequest $request): JsonResponse
    {
        $pipeline = $this->management->store($request);
        $auth = auth()->user();
        $this->logger->logCreation(
            $pipeline,
            $auth,
            $auth->id,
        );

        return response()->json($pipeline, 201);
    }

    /**
     * Display the specified resource.
     *
     * Returns a single pipeline by its model binding.
     *
     * Authorises via the 'view' policy before returning data.
     *
     * @param  Pipeline $pipeline
     *
     * @return JsonResponse
     */
    public function show(Pipeline $pipeline): JsonResponse
    {
        $this->authorize('view', $pipeline);

        $pipeline = $this->query->getById($pipeline->id);

        return response()->json($pipeline);
    }

    /**
     * Update the specified resource in storage.
     *
     * Validation is handled upstream by UpdatePipelineRequest, which
     * also implicitly authorises the operation via its authorize() method.
     *
     * After updating, an audit log entry is written against the
     * authenticated user.
     *
     * @param  UpdatePipelineRequest $request
     * @param  Pipeline $pipeline
     *
     * @return JsonResponse
     */
    public function update(
        UpdatePipelineRequest $request,
        Pipeline $pipeline
    ): JsonResponse {
        $pipeline = $this->management->update(
            $request,
            $pipeline
        );

        $auth = auth()->user();

        $this->logger->logUpdate(
            $pipeline,
            $auth,
            $auth->id,
        );

        return response()->json($pipeline);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Authorises via the 'delete' policy before proceeding.
     *
     * The audit log entry is written before the deletion so that the
     * pipeline pipeline instance is still fully accessible during logging.
     *
     * @param  Pipeline $pipeline
     * to delete.
     *
     * @return JsonResponse
     */
    public function destroy(Pipeline $pipeline): JsonResponse
    {
        $this->authorize('delete', $pipeline);
        $auth = auth()->user();

        $this->logger->logDeletion(
            $pipeline,
            $auth,
            $auth->id,
        );

        $this->management->destroy($pipeline);

        return response()->json(null, 204);
    }

    /**
     * Restore the specified pipeline from soft deletion.
     *
     * Looks up the pipeline including trashed records, then
     * checks if it exists and is trashed before authorization.
     * Returns 404 if the pipeline is not currently soft-deleted.
     *
     * @param  int|string $id
     *
     * @return JsonResponse
     *
     * @throws HttpException
     */
    public function restore($id): JsonResponse
    {
        $pipeline = Pipeline::withTrashed()->findOrFail($id);

        if (! $pipeline->trashed()) {
            abort(404);
        }

        $this->authorize('restore', $pipeline);

        $pipeline = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->logRestoration(
            $pipeline,
            $auth,
            $auth->id,
        );

        return response()->json($pipeline);
    }

    /**
     * Permanently delete the specified pipeline from storage.
     *
     * Looks up the pipeline including trashed records, then
     * authorises via the 'forceDelete' policy. This action is irreversible.
     *
     * The audit log entry is written before the permanent deletion so
     * that the pipeline instance is still fully accessible during
     * logging.
     *
     * @param  int|string $id
     *
     * @return JsonResponse
     */
    public function forceDelete($id): JsonResponse
    {
        $pipeline = Pipeline::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $pipeline);

        $auth = auth()->user();

        $this->logger->logForceDeletion(
            $pipeline,
            $auth,
            $auth->id,
        );

        $this->management->forceDelete((int) $id);

        return response()->json(null, 204);
    }

    /**
     * Soft delete multiple pipeline in bulk.
     *
     * Expects a 'ids' array in the request containing pipeline IDs
     * to delete. Each pipeline is authorised individually via the
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
            'ids.*' => 'required|integer|exists:pipelines,id',
        ]);

        $auth = auth()->user();
        $deleted = $this->management->bulkDelete(
            $request->input('ids'),
            $auth,
            fn ($pipeline) => $this->authorize('delete', $pipeline)
        );

        return response()->json([
            'message' => 'Pipeline deleted successfully',
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
        ]);
    }

    /**
     * Restore multiple pipelines from soft deletion in bulk.
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
            fn ($pipeline) => $this->authorize('restore', $pipeline)
        );

        return response()->json([
            'message' => 'Pipeline restored successfully',
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
            $pipeline = Pipeline::withTrashed()->find($id);

            if (! $pipeline) {
                throw ValidationException::withMessages([
                    "ids.{$index}" => ["The selected ids.{$index} is invalid."],
                ]);
            }
        }
    }
}
