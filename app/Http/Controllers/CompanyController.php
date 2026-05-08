<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\Companies\CompanyLogService;
use App\Services\Companies\CompanyManagementService;
use App\Services\Companies\CompanyQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CompanyController extends Controller
{
    use AuthorizesRequests;
    /**
     * Inject the required services into the controller.
     *
     * @param  CompanyLogService $logger Handles audit logging for
     * company events.
     * @param  CompanyManagementService $management Handles
     * company create/update/delete/restore.
     * @param  CompanyQueryService $query Handles company
     * listing and retrieval.
     */
    public function __construct(
        protected CompanyLogService $logger,
        protected CompanyManagementService $management,
        protected CompanyQueryService $query,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * Also includes the authenticated user's permissions for the
     * Company resource, so the frontend can conditionally render
     * create/view controls.
     *
     * Authorises via the 'viewAny' policy before returning data.
     *
     * @param  Request $request Incoming HTTP request; may carry
     * filter/pagination params.
     *
     * @return JsonResponse Paginated company data with pagination
     * metadata and permissions.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Company::class);

        $companies = $this->query->getPaginated($request->all());

        return response()->json($companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is handled upstream by StoreCompanyRequest.
     *
     * After storing, an audit log entry is written against the
     * authenticated user.
     *
     * @param  StoreCompanyRequest $request Validated request
     * containing company data.
     *
     * @return JsonResponse The newly created company, with HTTP
     * 201 Created.
     */
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $company = $this->management->store($request);
        $auth = auth()->user();
        $this->logger->logCreation(
            $company,
            $auth,
            $auth->id,
        );

        return response()->json($company, 201);
    }

    /**
     * Display the specified resource.
     *
     * Returns a single company by its model binding.
     *
     * Authorises via the 'view' policy before returning data.
     *
     * @param  Company $company Route-model-bound company
     * instance.
     *
     * @return JsonResponse The resolved company resource.
     */
    public function show(Company $company): JsonResponse
    {
        $this->authorize('view', $company);

        $company = $this->query->getById($company->id);

        return response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * Validation is handled upstream by UpdateCompanyRequest, which
     * also implicitly authorises the operation via its authorize() method.
     *
     * After updating, an audit log entry is written against the
     * authenticated user.
     *
     * @param  UpdateCompanyRequest $request Validated request
     * containing updated company data.
     * @param  Company $company Route-model-bound company
     * instance to update.
     *
     * @return JsonResponse The updated company resource.
     */
    public function update(
        UpdateCompanyRequest $request,
        Company $company
    ): JsonResponse {
        $company = $this->management->update(
            $request,
            $company
        );

        $auth = auth()->user();

        $this->logger->logUpdate(
            $company,
            $auth,
            $auth->id,
        );

        return response()->json($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Authorises via the 'delete' policy before proceeding.
     *
     * The audit log entry is written before the deletion so that the
     * company instance is still fully accessible during logging.
     *
     * @param  Company $company Route-model-bound company
     * instance to delete.
     *
     * @return JsonResponse Empty response with HTTP 204 No Content.
     */
    public function destroy(Company $company): JsonResponse
    {
        $this->authorize('delete', $company);
        $auth = auth()->user();

        $this->logger->logDeletion(
            $company,
            $auth,
            $auth->id,
        );

        $this->management->destroy($company);

        return response()->json(null, 204);
    }

    /**
     * Restore the specified company from soft deletion.
     *
     * Looks up the company including trashed records, then
     * checks if it exists and is trashed before authorization.
     * Returns 404 if the company is not currently soft-deleted.
     *
     * @param  int|string $id The primary key of the soft-deleted
     * company.
     *
     * @return JsonResponse The restored company resource.
     *
     * @throws HttpException If the company company is not trashed (404).
     */
    public function restore($id): JsonResponse
    {
        $company = Company::withTrashed()->findOrFail($id);

        if (! $company->trashed()) {
            abort(404);
        }

        $this->authorize('restore', $company);

        $company = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->logRestoration(
            $company,
            $auth,
            $auth->id,
        );

        return response()->json($company);
    }

    /**
     * Permanently delete the specified company from storage.
     *
     * Looks up the company including trashed records, then
     * authorises via the 'forceDelete' policy. This action is irreversible.
     *
     * The audit log entry is written before the permanent deletion so
     * that the company instance is still fully accessible during
     * logging.
     *
     * @param  int|string $id The primary key of the company to
     * permanently delete.
     *
     * @return JsonResponse Empty response with HTTP 204 No Content.
     */
    public function forceDelete($id): JsonResponse
    {
        $company = Company::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $company);

        $auth = auth()->user();

        $this->logger->logForceDeletion(
            $company,
            $auth,
            $auth->id,
        );

        $this->management->forceDelete((int) $id);

        return response()->json(null, 204);
    }

    /**
     * Soft delete multiple company in bulk.
     *
     * Expects a 'ids' array in the request containing company company IDs
     * to delete. Each company company is authorised individually via the
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
            fn ($company) => $this->authorize('delete', $company)
        );

        return response()->json([
            'message' => 'Company deleted successfully',
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
        ]);
    }

    /**
     * Restore multiple company from soft deletion in bulk.
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
            fn ($company) => $this->authorize('restore', $company)
        );

        return response()->json([
            'message' => 'Company restored successfully',
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
            $company = Company::withTrashed()->find($id);

            if (! $company) {
                throw ValidationException::withMessages([
                    "ids.{$index}" => ["The selected ids.{$index} is invalid."],
                ]);
            }
        }
    }
}
