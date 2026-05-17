<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillOfMaterialItemRequest;
use App\Http\Requests\UpdateBillOfMaterialItemRequest;
use App\Models\BillOfMaterialItem;
use App\Services\BillOfMaterialItems\BOMItemLogService;
use App\Services\BillOfMaterialItems\BOMItemManagementService;
use App\Services\BillOfMaterialItems\BOMItemQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BillOfMaterialItemController extends Controller
{
    use AuthorizesRequests;
    /**
     * Inject the required services into the controller.
     *
     * @param  BOMItemLogService $logger
     * @param  BOMItemManagementService $management
     * @param  BOMItemQueryService $query
     */
    public function __construct(
        protected BOMItemLogService $logger,
        protected BOMItemManagementService $management,
        protected BOMItemQueryService $query,
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
        $this->authorize('viewAny', BillOfMaterialItem::class);

        $billOfMateriamItem = $this->query->getPaginated($request->all());

        return response()->json($billOfMateriamItem);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is handled upstream by StoreBillOfMaterialRequest.
     *
     * After storing, an audit log entry is written against the
     * authenticated user.
     *
     * @param  StoreBillOfMaterialItemRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreBillOfMaterialItemRequest $request): JsonResponse
    {
        $billOfMateriamItem = $this->management->store($request);
        $auth = auth()->user();
        $this->logger->logCreation(
            $billOfMateriamItem,
            $auth,
            $auth->id,
        );

        return response()->json($billOfMateriamItem, 201);
    }

    /**
     * Display the specified resource.
     *
     * Returns a single BOM by its model binding.
     *
     * Authorises via the 'view' policy before returning data.
     *
     * @param  BillOfMaterialItem $billOfMateriamItem
     *
     * @return JsonResponse
     */
    public function show(BillOfMaterialItem $billOfMateriamItem): JsonResponse
    {
        $this->authorize('view', $billOfMateriamItem);

        $billOfMateriamItem = $this->query->getById($billOfMateriamItem->id);

        return response()->json($billOfMateriamItem);
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
     * @param  UpdateBillOfMaterialItemRequest $request
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return JsonResponse
     */
    public function update(
        UpdateBillOfMaterialItemRequest $request,
        BillOfMaterialItem $billOfMaterialItem
    ): JsonResponse {
        $billOfMaterialItem = $this->management->update(
            $request,
            $billOfMaterialItem
        );

        $auth = auth()->user();

        $this->logger->logUpdate(
            $billOfMaterialItem,
            $auth,
            $auth->id,
        );

        return response()->json($billOfMaterialItem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Authorises via the 'delete' policy before proceeding.
     *
     * The audit log entry is written before the deletion so that the
     * BOM instance is still fully accessible during logging.
     *
     * @param  BillOfMaterialItem $billOfMaterialItem
     *
     * @return JsonResponse
     */
    public function destroy(BillOfMaterialItem $billOfMaterialItem): JsonResponse
    {
        $this->authorize('delete', $billOfMaterialItem);
        $auth = auth()->user();

        $this->logger->logDeletion(
            $billOfMaterialItem,
            $auth,
            $auth->id,
        );

        $this->management->destroy($billOfMaterialItem);

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
        $billOfMaterialItem = BillOfMaterialItem::withTrashed()->findOrFail($id);

        if (! $billOfMaterialItem->trashed()) {
            abort(404);
        }

        $this->authorize('restore', $billOfMaterialItem);

        $billOfMaterialItem = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->logRestoration(
            $billOfMaterialItem,
            $auth,
            $auth->id,
        );

        return response()->json($billOfMaterialItem);
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
        $billOfMaterialItem = BillOfMaterialItem::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $billOfMaterialItem);

        $auth = auth()->user();

        $this->logger->logForceDeletion(
            $billOfMaterialItem,
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
            'ids.*' => 'required|integer|exists:bill_of_material_items,id',
        ]);

        $auth = auth()->user();
        $deleted = $this->management->bulkDelete(
            $request->input('ids'),
            $auth,
            fn ($billOfMaterialItem) => $this->authorize('delete', $billOfMaterialItem)
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
            fn ($billOfMaterialItem) => $this->authorize('restore', $billOfMaterialItem)
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
            $billOfMaterialItem = BillOfMaterialItem::withTrashed()->find($id);

            if (! $billOfMaterialItem) {
                throw ValidationException::withMessages([
                    "ids.{$index}" => ["The selected ids.{$index} is invalid."],
                ]);
            }
        }
    }
}
