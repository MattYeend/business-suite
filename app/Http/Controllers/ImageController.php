<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Models\Image;
use App\Services\Images\ImageLogService;
use App\Services\Images\ImageManagementService;
use App\Services\Images\ImageQueryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ImageController extends Controller
{
    use AuthorizesRequests;
    /**
     * Inject the required services into the controller.
     *
     * @param  ImageLogService $logger
     * @param  ImageManagementService $management
     * @param  ImageQueryService $query
     */
    public function __construct(
        protected ImageLogService $logger,
        protected ImageManagementService $management,
        protected ImageQueryService $query,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * Also includes the authenticated user's permissions for the
     * Image resource, so the frontend can conditionally render
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
        $this->authorize('viewAny', Image::class);

        $images = $this->query->getPaginated($request->all());

        return response()->json($images);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation is handled upstream by StoreImageRequest.
     *
     * After storing, an audit log entry is written against the
     * authenticated user.
     *
     * @param  StoreImageRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreImageRequest $request): JsonResponse
    {
        $image = $this->management->store($request);
        $auth = auth()->user();
        $this->logger->logCreation(
            $image,
            $auth,
            $auth->id,
        );

        return response()->json($image, 201);
    }

    /**
     * Display the specified resource.
     *
     * Returns a single image by its model binding.
     *
     * Authorises via the 'view' policy before returning data.
     *
     * @param  Image $image
     *
     * @return JsonResponse
     */
    public function show(Image $image): JsonResponse
    {
        $this->authorize('view', $image);

        $image = $this->query->getById($image->id);

        return response()->json($image);
    }

    /**
     * Update the specified resource in storage.
     *
     * Validation is handled upstream by UpdateImageRequest, which
     * also implicitly authorises the operation via its authorize() method.
     *
     * After updating, an audit log entry is written against the
     * authenticated user.
     *
     * @param  UpdateImageRequest $request
     * @param  Image $image
     *
     * @return JsonResponse
     */
    public function update(
        UpdateImageRequest $request,
        Image $image
    ): JsonResponse {
        $image = $this->management->update(
            $request,
            $image
        );

        $auth = auth()->user();

        $this->logger->logUpdate(
            $image,
            $auth,
            $auth->id,
        );

        return response()->json($image);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Authorises via the 'delete' policy before proceeding.
     *
     * The audit log entry is written before the deletion so that the
     * image instance is still fully accessible during logging.
     *
     * @param  Image $image
     *
     * @return JsonResponse
     */
    public function destroy(Image $image): JsonResponse
    {
        $this->authorize('delete', $image);
        $auth = auth()->user();

        $this->logger->logDeletion(
            $image,
            $auth,
            $auth->id,
        );

        $this->management->destroy($image);

        return response()->json(null, 204);
    }

    /**
     * Restore the specified image from soft deletion.
     *
     * Looks up the image including trashed records, then
     * checks if it exists and is trashed before authorisation.
     * Returns 404 if the image is not currently soft-deleted.
     *
     * @param  int|string $id The primary key of the soft-deleted
     * image.
     *
     * @return JsonResponse
     *
     * @throws HttpException
     */
    public function restore($id): JsonResponse
    {
        $image = Image::withTrashed()->findOrFail($id);

        if (! $image->trashed()) {
            abort(404);
        }

        $this->authorize('restore', $image);

        $image = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->logRestoration(
            $image,
            $auth,
            $auth->id,
        );

        return response()->json($image);
    }

    /**
     * Permanently delete the specified image from storage.
     *
     * Looks up the image including trashed records, then
     * authorises via the 'forceDelete' policy. This action is irreversible.
     *
     * The audit log entry is written before the permanent deletion so
     * that the image instance is still fully accessible during
     * logging.
     *
     * @param  int|string $id
     *
     * @return JsonResponse
     */
    public function forceDelete($id): JsonResponse
    {
        $image = Image::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $image);

        $auth = auth()->user();

        $this->logger->logForceDeletion(
            $image,
            $auth,
            $auth->id,
        );

        $this->management->forceDelete((int) $id);

        return response()->json(null, 204);
    }

    /**
     * Soft delete multiple image in bulk.
     *
     * Expects a 'ids' array in the request containing image image IDs
     * to delete. Each image image is authorised individually via the
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
            'ids.*' => 'required|integer|exists:images,id',
        ]);

        $auth = auth()->user();
        $deleted = $this->management->bulkDelete(
            $request->input('ids'),
            $auth,
            fn ($image) => $this->authorize('delete', $image)
        );

        return response()->json([
            'message' => 'Image deleted successfully',
            'deleted_count' => count($deleted),
            'deleted_ids' => $deleted,
        ]);
    }

    /**
     * Restore multiple image from soft deletion in bulk.
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
            fn ($image) => $this->authorize('restore', $image)
        );

        return response()->json([
            'message' => 'Image restored successfully',
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
            $image = Image::withTrashed()->find($id);

            if (! $image) {
                throw ValidationException::withMessages([
                    "ids.{$index}" => ["The selected ids.{$index} is invalid."],
                ]);
            }
        }
    }
}
