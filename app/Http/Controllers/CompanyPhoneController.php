<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyPhoneRequest;
use App\Http\Requests\UpdateCompanyPhoneRequest;
use App\Models\CompanyPhone;
use App\Services\CompanyPhones\CompanyPhoneLogService;
use App\Services\CompanyPhones\CompanyPhoneManagementService;
use App\Services\CompanyPhones\CompanyPhoneQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CompanyPhoneController extends Controller
{
    use AuthorizesRequests;
    /**
     * Inject the required services into the controller.
     *
     * @param  CompanyPhoneLogService $logger
     * @param  CompanyPhoneManagementService $management
     * @param  CompanyPhoneQueryService $query
     */
    public function __construct(
        protected CompanyPhoneLogService $logger,
        protected CompanyPhoneManagementService $management,
        protected CompanyPhoneQueryService $query,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * Also includes the authenticated user's permissions for the
     * CompanyPhone resource, so the frontend can conditionally render
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
        $this->authorize('viewAny', CompanyPhone::class);

        $companyPhones = $this->query->getPaginated($request->all());

        return response()->json($companyPhones);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is handled upstream by StoreCompanyPhoneRequest.
     *
     * After storing, an audit log entry is written against the
     * authenticated user.
     *
     * @param  StoreCompanyPhoneRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreCompanyPhoneRequest $request): JsonResponse
    {
        $companyPhone = $this->management->store($request);
        $auth = auth()->user();
        $this->logger->logCreation(
            $companyPhone,
            $auth,
            $auth->id,
        );

        return response()->json($companyPhone, 201);
    }

    /**
     * Display the specified resource.
     *
     * Returns a single company phone by its model binding.
     *
     * Authorises via the 'view' policy before returning data.
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return JsonResponse
     */
    public function show(CompanyPhone $companyPhone): JsonResponse
    {
        $this->authorize('view', $companyPhone);

        $companyPhone = $this->query->getById($companyPhone->id);

        return response()->json($companyPhone);
    }

    /**
     * Update the specified resource in storage.
     *
     * Validation is handled upstream by UpdateCompanyPhoneRequest, which
     * also implicitly authorises the operation via its authorize() method.
     *
     * After updating, an audit log entry is written against the
     * authenticated user.
     *
     * @param  UpdateCompanyPhoneRequest $request
     * @param  CompanyPhone $companyPhone
     *
     * @return JsonResponse
     */
    public function update(
        UpdateCompanyPhoneRequest $request,
        CompanyPhone $companyPhone
    ): JsonResponse {
        $companyPhone = $this->management->update(
            $request,
            $companyPhone
        );

        $auth = auth()->user();

        $this->logger->logUpdate(
            $companyPhone,
            $auth,
            $auth->id,
        );

        return response()->json($companyPhone);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Authorises via the 'delete' policy before proceeding.
     *
     * The audit log entry is written before the deletion so that the
     * company phone instance is still fully accessible during logging.
     *
     * @param  CompanyPhone $companyPhone
     *
     * @return JsonResponse
     */
    public function destroy(CompanyPhone $companyPhone)
    {
        $this->authorize('delete', $companyPhone);
        $auth = auth()->user();

        $this->logger->logDeletion(
            $companyPhone,
            $auth,
            $auth->id,
        );

        $this->management->destroy($companyPhone);

        return response()->json(null, 204);
    }

    /**
     * Restore the specified company phone from soft deletion.
     *
     * Looks up the company phone including trashed records, then
     * checks if it exists and is trashed before authorization.
     * Returns 404 if the company phone is not currently soft-deleted.
     *
     * @param  int|string $id
     *
     * @return JsonResponse
     *
     * @throws HttpException
     */
    public function restore($id): JsonResponse
    {
        $companyPhone = CompanyPhone::withTrashed()->findOrFail($id);

        if (! $companyPhone->trashed()) {
            abort(404);
        }

        $this->authorize('restore', $companyPhone);

        $companyPhone = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->logRestoration(
            $companyPhone,
            $auth,
            $auth->id,
        );

        return response()->json($companyPhone);
    }

    /**
     * Permanently delete the specified company phone from storage.
     *
     * Looks up the company phone including trashed records, then
     * authorises via the 'forceDelete' policy. This action is irreversible.
     *
     * The audit log entry is written before the permanent deletion so
     * that the company phone instance is still fully accessible during
     * logging.
     *
     * @param  int|string $id
     *
     * @return JsonResponse
     */
    public function forceDelete($id): JsonResponse
    {
        $companyPhone = CompanyPhone::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $companyPhone);

        $auth = auth()->user();

        $this->logger->logForceDeletion(
            $companyPhone,
            $auth,
            $auth->id,
        );

        $this->management->forceDelete((int) $id);

        return response()->json(null, 204);
    }

    /**
     * Soft delete multiple company phones in bulk.
     *
     * Expects a 'ids' array in the request containing company phone IDs
     * to delete. Each company phone is authorised individually via the
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
            'ids.*' => 'required|integer|exists:company_phones,id',
        ]);

        $auth = auth()->user();
        $deleted = $this->management->bulkDelete(
            $request->input('ids'),
            $auth,
            fn ($phone) => $this->authorize('delete', $phone)
        );

        return response()->json([
            'message' => 'Company phones deleted successfully',
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
        ]);
    }

    /**
     * Restore multiple company phones from soft deletion in bulk.
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
            fn ($phone) => $this->authorize('restore', $phone)
        );

        return response()->json([
            'message' => 'Company phones restored successfully',
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
            $phone = CompanyPhone::withTrashed()->find($id);

            if (! $phone) {
                throw ValidationException::withMessages([
                    "ids.{$index}" => ["The selected ids.{$index} is invalid."],
                ]);
            }
        }
    }
}
