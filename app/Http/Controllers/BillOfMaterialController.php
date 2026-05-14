<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillOfMaterialRequest;
use App\Http\Requests\UpdateBillOfMaterialRequest;
use App\Models\BillOfMaterial;
use App\Services\BillOfMaterials\BOMLogService;
use App\Services\BillOfMaterials\BOMManagementService;
use App\Services\BillOfMaterials\BOMQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BillOfMaterialController extends Controller
{
    use AuthorizesRequests;
    /**
     * Inject the required services into the controller.
     *
     * @param  BOMLogService $logger
     * @param  BOMManagementService $management
     * @param  BOMQueryService $query
     */
    public function __construct(
        protected BOMLogService $logger,
        protected BOMManagementService $management,
        protected BOMQueryService $query,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * Also includes the authenticated user's permissions for the
     * BOM resource, so the frontend can conditionally render
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
        $this->authorize('viewAny', BillOfMaterial::class);

        $billOfMaterial = $this->query->getPaginated($request->all());

        return response()->json($billOfMaterial);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is handled upstream by StoreBillOfMaterialRequest.
     *
     * After storing, an audit log entry is written against the
     * authenticated user.
     *
     * @param  StoreBillOfMaterialRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreBillOfMaterialRequest $request): JsonResponse
    {
        $billOfMaterial = $this->management->store($request);
        $auth = auth()->user();
        $this->logger->logCreation(
            $billOfMaterial,
            $auth,
            $auth->id,
        );

        return response()->json($billOfMaterial, 201);
    }

    /**
     * Display the specified resource.
     *
     * Returns a single BOM by its model binding.
     *
     * Authorises via the 'view' policy before returning data.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return JsonResponse
     */
    public function show(BillOfMaterial $billOfMaterial): JsonResponse
    {
        $this->authorize('view', $billOfMaterial);

        $billOfMaterial = $this->query->getById($billOfMaterial->id);

        return response()->json($billOfMaterial);
    }

    /**
     * Update the specified resource in storage.
     *
     * Validation is handled upstream by UpdateBillOfMaterialRequest, which
     * also implicitly authorises the operation via its authorize() method.
     *
     * After updating, an audit log entry is written against the
     * authenticated user.
     *
     * @param  UpdateBillOfMaterialRequest $request
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return JsonResponse
     */
    public function update(
        UpdateBillOfMaterialRequest $request,
        BillOfMaterial $billOfMaterial
    ): JsonResponse {
        $billOfMaterial = $this->management->update(
            $request,
            $billOfMaterial
        );

        $auth = auth()->user();

        $this->logger->logUpdate(
            $billOfMaterial,
            $auth,
            $auth->id,
        );

        return response()->json($billOfMaterial);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Authorises via the 'delete' policy before proceeding.
     *
     * The audit log entry is written before the deletion so that the
     * BOM instance is still fully accessible during logging.
     *
     * @param  BillOfMaterial $billOfMaterial
     *
     * @return JsonResponse
     */
    public function destroy(BillOfMaterial $billOfMaterial): JsonResponse
    {
        $this->authorize('delete', $billOfMaterial);
        $auth = auth()->user();

        $this->logger->logDeletion(
            $billOfMaterial,
            $auth,
            $auth->id,
        );

        $this->management->destroy($billOfMaterial);

        return response()->json(null, 204);
    }

    /**
     * Restore the specified BOM from soft deletion.
     *
     * Looks up the BOM including trashed records, then
     * checks if it exists and is trashed before authorisation.
     * Returns 404 if the BOM is not currently soft-deleted.
     *
     * @param  int|string $id
     *
     * @return JsonResponse
     *
     * @throws HttpException
     */
    public function restore($id): JsonResponse
    {
        $billOfMaterial = BillOfMaterial::withTrashed()->findOrFail($id);

        if (! $billOfMaterial->trashed()) {
            abort(404);
        }

        $this->authorize('restore', $billOfMaterial);

        $billOfMaterial = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->logRestoration(
            $billOfMaterial,
            $auth,
            $auth->id,
        );

        return response()->json($billOfMaterial);
    }

    /**
     * Permanently delete the specified BOM from storage.
     *
     * Looks up the BOM including trashed records, then
     * authorises via the 'forceDelete' policy. This action is irreversible.
     *
     * The audit log entry is written before the permanent deletion so
     * that the BOM instance is still fully accessible during
     * logging.
     *
     * @param  int|string $id
     *
     * @return JsonResponse
     */
    public function forceDelete($id): JsonResponse
    {
        $billOfMaterial = BillOfMaterial::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $billOfMaterial);

        $auth = auth()->user();

        $this->logger->logForceDeletion(
            $billOfMaterial,
            $auth,
            $auth->id,
        );

        $this->management->forceDelete((int) $id);

        return response()->json(null, 204);
    }

    /**
     * Soft delete multiple BOM in bulk.
     *
     * Expects a 'ids' array in the request containing BOM IDs
     * to delete. Each BOM is authorised individually via the
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
            'ids.*' => 'required|integer|exists:bill_of_materials,id',
        ]);

        $auth = auth()->user();
        $deleted = $this->management->bulkDelete(
            $request->input('ids'),
            $auth,
            fn ($billOfMaterial) => $this->authorize('delete', $billOfMaterial)
        );

        return response()->json([
            'message' => 'BOM deleted successfully',
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
        ]);
    }

    /**
     * Restore multiple company addresss from soft deletion in bulk.
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
            fn ($billOfMaterial) => $this->authorize('restore', $billOfMaterial)
        );

        return response()->json([
            'message' => 'BOM restored successfully',
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
            $billOfMaterial = BillOfMaterial::withTrashed()->find($id);

            if (! $billOfMaterial) {
                throw ValidationException::withMessages([
                    "ids.{$index}" => ["The selected ids.{$index} is invalid."],
                ]);
            }
        }
    }
}
