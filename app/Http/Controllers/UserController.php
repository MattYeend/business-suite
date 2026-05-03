<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\Users\UserLogService;
use App\Services\Users\UserManagementService;
use App\Services\Users\UserQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    /**
     * Service responsible for writing audit log entries for user events.
     *
     * @var UserLogService
     */
    protected UserLogService $logger;

    /**
     * Service responsible for creating, updating, deleting, and restoring
     * users.
     *
     * @var UserManagementService
     */
    protected UserManagementService $management;

    /**
     * Service responsible for querying and listing users.
     *
     * @var UserQueryService
     */
    protected UserQueryService $query;

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
        UserLogService $logger,
        UserManagementService $management,
        UserQueryService $query,
    ) {
        $this->logger = $logger;
        $this->management = $management;
        $this->query = $query;
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

        $users = $this->query->list($request);

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
        $this->logger->userCreated(
            $auth,
            $auth->id,
            $user,
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

        $user = $this->query->show($user);

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

        $this->logger->userUpdated(
            $auth,
            $auth->id,
            $user,
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

        $this->logger->userDeleted(
            $auth,
            $auth->id,
            $user,
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

        $this->logger->userRestored(
            $auth,
            $auth->id,
            $user,
        );

        return response()->json($user);
    }
}
