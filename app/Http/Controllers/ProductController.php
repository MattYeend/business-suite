<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\Products\ProductLogService;
use App\Services\Products\ProductManagementService;
use App\Services\Products\ProductQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductController extends Controller
{
    use AuthorizesRequests;
    /**
     * Inject the required services into the controller.
     *
     * @param  ProductLogService $logger
     * @param  ProductManagementService $management
     * @param  ProductQueryService $query
     */
    public function __construct(
        protected ProductLogService $logger,
        protected ProductManagementService $management,
        protected ProductQueryService $query,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * Also includes the authenticated user's permissions for the
     * Product resource, so the frontend can conditionally render
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
        $this->authorize('viewAny', Product::class);

        $products = $this->query->getPaginated($request->all());

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is handled upstream by StoreProductRequest.
     *
     * After storing, an audit log entry is written against the
     * authenticated user.
     *
     * @param  StoreProductRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->management->store($request);
        $auth = auth()->user();
        $this->logger->logCreation(
            $product,
            $auth,
            $auth->id,
        );

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * Returns a single product by its model binding.
     *
     * Authorises via the 'view' policy before returning data.
     *
     * @param  Product $product
     *
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        $this->authorize('view', $product);

        $product = $this->query->getById($product->id);

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * Validation is handled upstream by UpdateProductRequest, which
     * also implicitly authorises the operation via its authorize() method.
     *
     * After updating, an audit log entry is written against the
     * authenticated user.
     *
     * @param  UpdateProductRequest $request
     * @param  Product $product
     *
     * @return JsonResponse
     */
    public function update(
        UpdateProductRequest $request,
        Product $product
    ): JsonResponse {
        $product = $this->management->update(
            $request,
            $product
        );

        $auth = auth()->user();

        $this->logger->logUpdate(
            $product,
            $auth,
            $auth->id,
        );

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Authorises via the 'delete' policy before proceeding.
     *
     * The audit log entry is written before the deletion so that the
     * product instance is still fully accessible during logging.
     *
     * @param  Product $product
     *
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);
        $auth = auth()->user();

        $this->logger->logDeletion(
            $product,
            $auth,
            $auth->id,
        );

        $this->management->destroy($product);

        return response()->json(null, 204);
    }

    /**
     * Restore the specified product from soft deletion.
     *
     * Looks up the product including trashed records, then
     * checks if it exists and is trashed before authorization.
     * Returns 404 if the product is not currently soft-deleted.
     *
     * @param  int|string $id
     *
     * @return JsonResponse
     *
     * @throws HttpException
     */
    public function restore($id): JsonResponse
    {
        $product = Product::withTrashed()->findOrFail($id);

        if (! $product->trashed()) {
            abort(404);
        }

        $this->authorize('restore', $product);

        $product = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->logRestoration(
            $product,
            $auth,
            $auth->id,
        );

        return response()->json($product);
    }

    /**
     * Permanently delete the specified product from storage.
     *
     * Looks up the product including trashed records, then
     * authorises via the 'forceDelete' policy. This action is irreversible.
     *
     * The audit log entry is written before the permanent deletion so
     * that the product instance is still fully accessible during
     * logging.
     *
     * @param  int|string $id
     *
     * @return JsonResponse
     */
    public function forceDelete($id): JsonResponse
    {
        $product = Product::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $product);

        $auth = auth()->user();

        $this->logger->logForceDeletion(
            $product,
            $auth,
            $auth->id,
        );

        $this->management->forceDelete((int) $id);

        return response()->json(null, 204);
    }

    /**
     * Soft delete multiple product in bulk.
     *
     * Expects a 'ids' array in the request containing product product IDs
     * to delete. Each product product is authorised individually via the
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
            'ids.*' => 'required|integer|exists:products,id',
        ]);

        $auth = auth()->user();
        $deleted = $this->management->bulkDelete(
            $request->input('ids'),
            $auth,
            fn ($product) => $this->authorize('delete', $product)
        );

        return response()->json([
            'message' => 'Product deleted successfully',
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
        ]);
    }

    /**
     * Restore multiple product from soft deletion in bulk.
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
            fn ($product) => $this->authorize('restore', $product)
        );

        return response()->json([
            'message' => 'Product restored successfully',
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
            $product = Product::withTrashed()->find($id);

            if (! $product) {
                throw ValidationException::withMessages([
                    "ids.{$index}" => ["The selected ids.{$index} is invalid."],
                ]);
            }
        }
    }
}
