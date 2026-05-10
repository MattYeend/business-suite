<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyIndustryRequest;
use App\Http\Requests\UpdateCompanyIndustryRequest;
use App\Models\CompanyIndustry;
use App\Services\CompanyIndustries\CompanyIndustryLogService;
use App\Services\CompanyIndustries\CompanyIndustryManagementService;
use App\Services\CompanyIndustries\CompanyIndustryQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CompanyIndustryController extends Controller
{
    use AuthorizesRequests;
    /**
     * Inject the required services into the controller.
     *
     * @param  CompanyIndustryLogService $logger Handles audit logging for
     * company industry events.
     * @param  CompanyIndustryManagementService $management Handles company
     * industry create/update/delete/restore.
     * @param  CompanyIndustryQueryService $query Handles company industry
     * listing and retrieval.
     */
    public function __construct(
        protected CompanyIndustryLogService $logger,
        protected CompanyIndustryManagementService $management,
        protected CompanyIndustryQueryService $query,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * Also includes the authenticated user's permissions for the
     * CompanyIndustry resource, so the frontend can conditionally render
     * create/view controls.
     *
     * Authorises via the 'viewAny' policy before returning data.
     *
     * @param  Request $request Incoming HTTP request; may carry
     * filter/pagination params.
     *
     * @return JsonResponse Paginated company industry data with pagination
     * metadata and permissions.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', CompanyIndustry::class);

        $companyIndustries = $this->query->getPaginated($request->all());

        return response()->json($companyIndustries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is handled upstream by StoreCompanyIndustryRequest.
     *
     * After storing, an audit log entry is written against the
     * authenticated user.
     *
     * @param  StoreCompanyIndustryRequest $request Validated request
     * containing company industry data.
     *
     * @return JsonResponse The newly created company industry, with HTTP
     * 201 Created.
     */
    public function store(StoreCompanyIndustryRequest $request): JsonResponse
    {
        $companyIndustry = $this->management->store($request);
        $auth = auth()->user();
        $this->logger->logCreation(
            $companyIndustry,
            $auth,
            $auth->id,
        );

        return response()->json($companyIndustry, 201);
    }

    /**
     * Display the specified resource.
     *
     * Returns a single company industry by its model binding.
     *
     * Authorises via the 'view' policy before returning data.
     *
     * @param  CompanyIndustry $companyIndustry Route-model-bound company
     * industry instance.
     *
     * @return JsonResponse The resolved company industry resource.
     */
    public function show(CompanyIndustry $companyIndustry): JsonResponse
    {
        $this->authorize('view', $companyIndustry);

        $companyIndustry = $this->query->getById($companyIndustry->id);

        return response()->json($companyIndustry);
    }

    /**
     * Update the specified resource in storage.
     *
     * Validation is handled upstream by UpdateCompanyIndustryRequest, which
     * also implicitly authorises the operation via its authorize() method.
     *
     * After updating, an audit log entry is written against the
     * authenticated user.
     *
     * @param  UpdateCompanyIndustryRequest $request Validated request
     * containing updated company industry data.
     * @param  CompanyIndustry $companyIndustry Route-model-bound company
     * industry instance to update.
     *
     * @return JsonResponse The updated company industry resource.
     */
    public function update(
        UpdateCompanyIndustryRequest $request,
        CompanyIndustry $companyIndustry
    ): JsonResponse {
        $companyIndustry = $this->management->update(
            $request,
            $companyIndustry
        );

        $auth = auth()->user();

        $this->logger->logUpdate(
            $companyIndustry,
            $auth,
            $auth->id,
        );

        return response()->json($companyIndustry);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Authorises via the 'delete' policy before proceeding.
     *
     * The audit log entry is written before the deletion so that the
     * company industry instance is still fully accessible during logging.
     *
     * @param  CompanyIndustry $companyIndustry Route-model-bound company
     * industry instance to delete.
     *
     * @return JsonResponse Empty response with HTTP 204 No Content.
     */
    public function destroy(CompanyIndustry $companyIndustry): JsonResponse
    {
        $this->authorize('delete', $companyIndustry);
        $auth = auth()->user();

        $this->logger->logDeletion(
            $companyIndustry,
            $auth,
            $auth->id,
        );

        $this->management->destroy($companyIndustry);

        return response()->json(null, 204);
    }

    /**
     * Restore the specified company industry from soft deletion.
     *
     * Looks up the company industry including trashed records, then
     * checks if it exists and is trashed before authorization.
     * Returns 404 if the company industry is not currently soft-deleted.
     *
     * @param  int|string $id The primary key of the soft-deleted
     * company industry.
     *
     * @return JsonResponse The restored company industry resource.
     *
     * @throws HttpException If the company industry is not trashed (404).
     */
    public function restore($id): JsonResponse
    {
        $companyIndustry = CompanyIndustry::withTrashed()->findOrFail($id);

        if (! $companyIndustry->trashed()) {
            abort(404);
        }

        $this->authorize('restore', $companyIndustry);

        $companyIndustry = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->logRestoration(
            $companyIndustry,
            $auth,
            $auth->id,
        );

        return response()->json($companyIndustry);
    }

    /**
     * Permanently delete the specified company industry from storage.
     *
     * Looks up the company industry including trashed records, then
     * authorises via the 'forceDelete' policy. This action is irreversible.
     *
     * The audit log entry is written before the permanent deletion so
     * that the company industry instance is still fully accessible during
     * logging.
     *
     * @param  int|string $id The primary key of the company industry to
     * permanently delete.
     *
     * @return JsonResponse Empty response with HTTP 204 No Content.
     */
    public function forceDelete($id): JsonResponse
    {
        $companyIndustry = CompanyIndustry::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $companyIndustry);

        $auth = auth()->user();

        $this->logger->logForceDeletion(
            $companyIndustry,
            $auth,
            $auth->id,
        );

        $this->management->forceDelete((int) $id);

        return response()->json(null, 204);
    }

    /**
     * Soft delete multiple company industries in bulk.
     *
     * Expects a 'ids' array in the request containing company industry IDs
     * to delete. Each company industry is authorised individually via the
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
            'ids.*' => 'required|integer|exists:company_industries,id',
        ]);

        $auth = auth()->user();
        $deleted = $this->management->bulkDelete(
            $request->input('ids'),
            $auth,
            fn ($industry) => $this->authorize('delete', $industry)
        );

        return response()->json([
            'message' => 'Company industries deleted successfully',
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
        ]);
    }

    /**
     * Restore multiple company industries from soft deletion in bulk.
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
            fn ($industry) => $this->authorize('restore', $industry)
        );

        return response()->json([
            'message' => 'Company industries restored successfully',
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
            $industry = CompanyIndustry::withTrashed()->find($id);

            if (! $industry) {
                throw ValidationException::withMessages([
                    "ids.{$index}" => ["The selected ids.{$index} is invalid."],
                ]);
            }
        }
    }
}
