<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\Users\UserLogService;
use App\Services\Users\UserManagementService;
use App\Services\Users\UserQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Inject the required services into the controller.
     *
     * @param  UserLogService $logger Handles audit logging for
     * user events.
     * @param  UserManagementService $management Handles user
     * create/update/delete/restore.
     * @param  UserQueryService $query Handles user listing and
     * retrieval.
     */
    public function __construct(
        protected UserLogService $logger,
        protected UserManagementService $management,
        protected UserQueryService $query,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * Also includes the authenticated user's permissions for the User
     * resource, so the frontend can conditionally render create/view controls.
     *
     * Authorises via the 'viewAny' policy before returning data.
     *
     * @param  Request $request Incoming HTTP request; may carry
     * filter/pagination params.
     *
     * @return JsonResponse Paginated user data with pagination metadata and
     * permissions.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $users = $this->query->getPaginated($request->all());

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is handled upstream by StoreUserRequest.
     *
     * After storing, an audit log entry is written against the authenticated
     * user.
     *
     * @param  StoreUserRequest $request Validated request containing
     * user data.
     *
     * @return JsonResponse The newly created user, with HTTP 201 Created.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->management->store($request);
        $auth = auth()->user();
        $this->logger->logCreation(
            $user,
            $auth,
            $auth->id,
        );

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * Returns a single user by its model binding.
     *
     * Authorises via the 'view' policy before returning data.
     *
     * @param  User $user Route-model-bound user instance.
     *
     * @return JsonResponse The resolved user resource.
     */
    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        $user = $this->query->getById($user->id);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * Validation is handled upstream by UpdateUserRequest, which also
     * implicitly authorises the operation via its authorize() method.
     *
     * After updating, an audit log entry is written against the
     * authenticated user.
     *
     * @param  UpdateUserRequest $request Validated request containing
     * updated user data.
     * @param  User $user Route-model-bound user instance to update.
     *
     * @return JsonResponse The updated user resource.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user = $this->management->update($request, $user);

        $auth = auth()->user();

        $this->logger->logUpdate(
            $user,
            $auth,
            $auth->id,
        );

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Authorises via the 'delete' policy before proceeding.
     *
     * The audit log entry is written before the deletion so that the
     * user instance is still fully accessible during logging.
     *
     * @param  User $user Route-model-bound user instance to delete.
     *
     * @return JsonResponse Empty response with HTTP 204 No Content.
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);
        $auth = auth()->user();

        $this->logger->logDeletion(
            $user,
            $auth,
            $auth->id,
        );

        $this->management->destroy($user);

        return response()->json(null, 204);
    }

    /**
     * Restore the specified user from soft deletion.
     *
     * Looks up the user including trashed records, then authorises via
     * the 'restore' policy. Returns 404 if the user is not currently
     * soft-deleted, preventing accidental double-restores.
     *
     * @param  int|string $id The primary key of the soft-deleted user.
     *
     * @return JsonResponse The restored user resource.
     *
     * @throws HttpException If the user is not trashed (404).
     */
    public function restore($id): JsonResponse
    {
        $user = User::withTrashed()->findOrFail($id);
        $this->authorize('restore', $user);

        if (! $user->trashed()) {
            abort(404);
        }

        $user = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->logRestoration(
            $user,
            $auth,
            $auth->id,
        );

        return response()->json($user);
    }

    /**
     * Permanently delete the specified user from storage.
     *
     * Looks up the user including trashed records, then authorises via
     * the 'forceDelete' policy. This action is irreversible.
     *
     * The audit log entry is written before the permanent deletion so
     * that the user instance is still fully accessible during logging.
     *
     * @param  int|string $id The primary key of the user to permanently
     * delete.
     *
     * @return JsonResponse Empty response with HTTP 204 No Content.
     */
    public function forceDelete($id): JsonResponse
    {
        $user = User::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $user);

        $auth = auth()->user();

        $this->logger->logForceDeletion(
            $user,
            $auth,
            $auth->id,
        );

        $this->management->forceDelete((int) $id);

        return response()->json(null, 204);
    }

    /**
     * Soft delete multiple users in bulk.
     *
     * Expects a 'ids' array in the request containing user IDs to delete.
     * Each user is authorised individually via the 'delete' policy.
     *
     * @param  Request $request Incoming HTTP request with 'ids' array.
     *
     * @return JsonResponse Summary of the bulk operation.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:users,id',
        ]);

        $auth = auth()->user();
        $deleted = $this->management->bulkDelete(
            $request->input('ids'),
            $auth,
            fn ($user) => $this->authorize('delete', $user)
        );

        return response()->json([
            'message' => 'Users deleted successfully',
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
        ]);
    }

    /**
     * Restore multiple users from soft deletion in bulk.
     *
     * Expects a 'ids' array in the request containing user IDs to restore.
     * Each user is authorised individually via the 'restore' policy.
     *
     * @param  Request $request Incoming HTTP request with 'ids' array.
     *
     * @return JsonResponse Summary of the bulk operation.
     */
    public function bulkRestore(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer',
        ]);

        $auth = auth()->user();
        $restored = $this->management->bulkRestore(
            $request->input('ids'),
            $auth,
            fn ($user) => $this->authorize('restore', $user)
        );

        return response()->json([
            'message' => 'Users restored successfully',
            'restored_count' => count($restored),
            'restored_ids' => $restored,
        ]);
    }
}
