<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyAddressRequest;
use App\Http\Requests\UpdateCompanyAddressRequest;
use App\Models\CompanyAddress;
use App\Services\CompanyAddresses\CompanyAddressLogService;
use App\Services\CompanyAddresses\CompanyAddressManagementService;
use App\Services\CompanyAddresses\CompanyAddressQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CompanyAddressController extends Controller
{
    use AuthorizesRequests;
    /**
     * Inject the required services into the controller.
     *
     * @param  CompanyAddressLogService $logger Handles audit logging for
     * company address events.
     * @param  CompanyAddressManagementService $management Handles
     * company address create/update/delete/restore.
     * @param  CompanyAddressQueryService $query Handles company address
     * listing and retrieval.
     */
    public function __construct(
        protected CompanyAddressLogService $logger,
        protected CompanyAddressManagementService $management,
        protected CompanyAddressQueryService $query,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * Also includes the authenticated user's permissions for the
     * CompanyAddress resource, so the frontend can conditionally render
     * create/view controls.
     *
     * Authorises via the 'viewAny' policy before returning data.
     *
     * @param  Request $request Incoming HTTP request; may carry
     * filter/pagination params.
     *
     * @return JsonResponse Paginated company address data with pagination
     * metadata and permissions.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', CompanyAddress::class);

        $companyAddresss = $this->query->getPaginated($request->all());

        return response()->json($companyAddresss);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is handled upstream by StoreCompanyAddressRequest.
     *
     * After storing, an audit log entry is written against the
     * authenticated user.
     *
     * @param  StoreCompanyAddressRequest $request Validated request
     * containing company address data.
     *
     * @return JsonResponse The newly created company address, with HTTP
     * 201 Created.
     */
    public function store(StoreCompanyAddressRequest $request): JsonResponse
    {
        $companyAddress = $this->management->store($request);
        $auth = auth()->user();
        $this->logger->logCreation(
            $companyAddress,
            $auth,
            $auth->id,
        );

        return response()->json($companyAddress, 201);
    }

    /**
     * Display the specified resource.
     *
     * Returns a single company address by its model binding.
     *
     * Authorises via the 'view' policy before returning data.
     *
     * @param  CompanyAddress $companyAddress Route-model-bound company
     * address instance.
     *
     * @return JsonResponse The resolved company address resource.
     */
    public function show(CompanyAddress $companyAddress): JsonResponse
    {
        $this->authorize('view', $companyAddress);

        $companyAddress = $this->query->getById($companyAddress->id);

        return response()->json($companyAddress);
    }

    /**
     * Update the specified resource in storage.
     *
     * Validation is handled upstream by UpdateCompanyAddressRequest, which
     * also implicitly authorises the operation via its authorize() method.
     *
     * After updating, an audit log entry is written against the
     * authenticated user.
     *
     * @param  UpdateCompanyAddressRequest $request Validated request
     * containing updated company address data.
     * @param  CompanyAddress $companyAddress Route-model-bound company
     * address instance to update.
     *
     * @return JsonResponse The updated company address resource.
     */
    public function update(
        UpdateCompanyAddressRequest $request,
        CompanyAddress $companyAddress
    ): JsonResponse {
        $companyAddress = $this->management->update(
            $request,
            $companyAddress
        );

        $auth = auth()->user();

        $this->logger->logUpdate(
            $companyAddress,
            $auth,
            $auth->id,
        );

        return response()->json($companyAddress);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Authorises via the 'delete' policy before proceeding.
     *
     * The audit log entry is written before the deletion so that the
     * company address instance is still fully accessible during logging.
     *
     * @param  CompanyAddress $companyAddress Route-model-bound company
     * address instance to delete.
     *
     * @return JsonResponse Empty response with HTTP 204 No Content.
     */
    public function destroy(CompanyAddress $companyAddress): JsonResponse
    {
        $this->authorize('delete', $companyAddress);
        $auth = auth()->user();

        $this->logger->logDeletion(
            $companyAddress,
            $auth,
            $auth->id,
        );

        $this->management->destroy($companyAddress);

        return response()->json(null, 204);
    }

    /**
     * Restore the specified company address from soft deletion.
     *
     * Looks up the company address including trashed records, then
     * checks if it exists and is trashed before authorization.
     * Returns 404 if the company address is not currently soft-deleted.
     *
     * @param  int|string $id The primary key of the soft-deleted
     * company address.
     *
     * @return JsonResponse The restored company address resource.
     *
     * @throws HttpException If the company address is not trashed (404).
     */
    public function restore($id): JsonResponse
    {
        $CompanyAddress = CompanyAddress::withTrashed()->findOrFail($id);

        if (! $CompanyAddress->trashed()) {
            abort(404);
        }

        $this->authorize('restore', $CompanyAddress);

        $CompanyAddress = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->logRestoration(
            $CompanyAddress,
            $auth,
            $auth->id,
        );

        return response()->json($CompanyAddress);
    }

    /**
     * Permanently delete the specified company address from storage.
     *
     * Looks up the company address including trashed records, then
     * authorises via the 'forceDelete' policy. This action is irreversible.
     *
     * The audit log entry is written before the permanent deletion so
     * that the company address instance is still fully accessible during
     * logging.
     *
     * @param  int|string $id The primary key of the company address to
     * permanently delete.
     *
     * @return JsonResponse Empty response with HTTP 204 No Content.
     */
    public function forceDelete($id): JsonResponse
    {
        $CompanyAddress = CompanyAddress::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $CompanyAddress);

        $auth = auth()->user();

        $this->logger->logForceDeletion(
            $CompanyAddress,
            $auth,
            $auth->id,
        );

        $this->management->forceDelete((int) $id);

        return response()->json(null, 204);
    }

    /**
     * Soft delete multiple company addresss in bulk.
     *
     * Expects a 'ids' array in the request containing company address IDs
     * to delete. Each company address is authorised individually via the
     * 'delete' policy.
     *
     * @param  Request $request Incoming HTTP request with 'ids' array.
     *
     * @return JsonResponse Summary of the bulk operation.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:company_addresses,id',
        ]);

        $auth = auth()->user();
        $deleted = $this->management->bulkDelete(
            $request->input('ids'),
            $auth,
            fn ($address) => $this->authorize('delete', $address)
        );

        return response()->json([
            'message' => 'Company address deleted successfully',
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
        ]);
    }

    /**
     * Restore multiple company addresss from soft deletion in bulk.
     *
     * @param  Request $request Incoming HTTP request with 'ids' array.
     *
     * @return JsonResponse Summary of the bulk operation.
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
            fn ($address) => $this->authorize('restore', $address)
        );

        return response()->json([
            'message' => 'Company address restored successfully',
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
            $address = CompanyAddress::withTrashed()->find($id);

            if (! $address) {
                throw ValidationException::withMessages([
                    "ids.{$index}" => ["The selected ids.{$index} is invalid."],
                ]);
            }
        }
    }
}
