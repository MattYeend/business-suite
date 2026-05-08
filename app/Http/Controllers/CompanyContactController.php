<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyContactRequest;
use App\Http\Requests\UpdateCompanyContactRequest;
use App\Models\CompanyContact;
use App\Services\CompanyContacts\CompanyContactLogService;
use App\Services\CompanyContacts\CompanyContactManagementService;
use App\Services\CompanyContacts\CompanyContactQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CompanyContactController extends Controller
{
    use AuthorizesRequests;
    /**
     * Inject the required services into the controller.
     *
     * @param  CompanyContactLogService $logger Handles audit logging for
     * company contact events.
     * @param  CompanyContactManagementService $management Handles
     * company contact create/update/delete/restore.
     * @param  CompanyContactQueryService $query Handles company contact
     * listing and retrieval.
     */
    public function __construct(
        protected CompanyContactLogService $logger,
        protected CompanyContactManagementService $management,
        protected CompanyContactQueryService $query,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * Also includes the authenticated user's permissions for the
     * CompanyContact resource, so the frontend can conditionally render
     * create/view controls.
     *
     * Authorises via the 'viewAny' policy before returning data.
     *
     * @param  Request $request Incoming HTTP request; may carry
     * filter/pagination params.
     *
     * @return JsonResponse Paginated company contact data with pagination
     * metadata and permissions.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', CompanyContact::class);

        $companyContacts = $this->query->getPaginated($request->all());

        return response()->json($companyContacts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is handled upstream by StoreCompanyContactRequest.
     *
     * After storing, an audit log entry is written against the
     * authenticated user.
     *
     * @param  StoreCompanyContactRequest $request Validated request
     * containing company contact data.
     *
     * @return JsonResponse The newly created company contact, with HTTP
     * 201 Created.
     */
    public function store(StoreCompanyContactRequest $request): JsonResponse
    {
        $companyContact = $this->management->store($request);
        $auth = auth()->user();
        $this->logger->logCreation(
            $companyContact,
            $auth,
            $auth->id,
        );

        return response()->json($companyContact, 201);
    }

    /**
     * Display the specified resource.
     *
     * Returns a single company contact by its model binding.
     *
     * Authorises via the 'view' policy before returning data.
     *
     * @param  CompanyContact $companyContact Route-model-bound company
     * contact instance.
     *
     * @return JsonResponse The resolved company contact resource.
     */
    public function show(CompanyContact $companyContact): JsonResponse
    {
        $this->authorize('view', $companyContact);

        $companyContact = $this->query->getById($companyContact->id);

        return response()->json($companyContact);
    }

    /**
     * Update the specified resource in storage.
     *
     * Validation is handled upstream by UpdateCompanyContactRequest, which
     * also implicitly authorises the operation via its authorize() method.
     *
     * After updating, an audit log entry is written against the
     * authenticated user.
     *
     * @param  UpdateCompanyContactRequest $request Validated request
     * containing updated company contact data.
     * @param  CompanyContact $companyContact Route-model-bound company
     * contact instance to update.
     *
     * @return JsonResponse The updated company contact resource.
     */
    public function update(
        UpdateCompanyContactRequest $request,
        CompanyContact $companyContact
    ): JsonResponse {
        $companyContact = $this->management->update(
            $request,
            $companyContact
        );

        $auth = auth()->user();

        $this->logger->logUpdate(
            $companyContact,
            $auth,
            $auth->id,
        );

        return response()->json($companyContact);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Authorises via the 'delete' policy before proceeding.
     *
     * The audit log entry is written before the deletion so that the
     * company contact instance is still fully accessible during logging.
     *
     * @param  CompanyContact $companyContact Route-model-bound company
     * contact instance to delete.
     *
     * @return JsonResponse Empty response with HTTP 204 No Content.
     */
    public function destroy(CompanyContact $companyContact): JsonResponse
    {
        $this->authorize('delete', $companyContact);
        $auth = auth()->user();

        $this->logger->logDeletion(
            $companyContact,
            $auth,
            $auth->id,
        );

        $this->management->destroy($companyContact);

        return response()->json(null, 204);
    }

    /**
     * Restore the specified company contact from soft deletion.
     *
     * Looks up the company contact including trashed records, then
     * checks if it exists and is trashed before authorization.
     * Returns 404 if the company contact is not currently soft-deleted.
     *
     * @param  int|string $id The primary key of the soft-deleted
     * company contact.
     *
     * @return JsonResponse The restored company contact resource.
     *
     * @throws HttpException If the company contact is not trashed (404).
     */
    public function restore($id): JsonResponse
    {
        $companyContact = CompanyContact::withTrashed()->findOrFail($id);

        if (! $companyContact->trashed()) {
            abort(404);
        }

        $this->authorize('restore', $companyContact);

        $companyContact = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->logRestoration(
            $companyContact,
            $auth,
            $auth->id,
        );

        return response()->json($companyContact);
    }

    /**
     * Permanently delete the specified company contact from storage.
     *
     * Looks up the company contact including trashed records, then
     * authorises via the 'forceDelete' policy. This action is irreversible.
     *
     * The audit log entry is written before the permanent deletion so
     * that the company contact instance is still fully accessible during
     * logging.
     *
     * @param  int|string $id The primary key of the company contact to
     * permanently delete.
     *
     * @return JsonResponse Empty response with HTTP 204 No Content.
     */
    public function forceDelete($id): JsonResponse
    {
        $companyContact = CompanyContact::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $companyContact);

        $auth = auth()->user();

        $this->logger->logForceDeletion(
            $companyContact,
            $auth,
            $auth->id,
        );

        $this->management->forceDelete((int) $id);

        return response()->json(null, 204);
    }

    /**
     * Soft delete multiple company contacts in bulk.
     *
     * Expects a 'ids' array in the request containing company contact IDs
     * to delete. Each company contact is authorised individually via the
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
            fn ($contact) => $this->authorize('delete', $contact)
        );

        return response()->json([
            'message' => 'Company contacts deleted successfully',
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
        ]);
    }

    /**
     * Restore multiple company contacts from soft deletion in bulk.
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
            fn ($contact) => $this->authorize('restore', $contact)
        );

        return response()->json([
            'message' => 'Company contacts restored successfully',
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
            $contact = CompanyContact::withTrashed()->find($id);

            if (! $contact) {
                throw ValidationException::withMessages([
                    "ids.{$index}" => ["The selected ids.{$index} is invalid."],
                ]);
            }
        }
    }
}
