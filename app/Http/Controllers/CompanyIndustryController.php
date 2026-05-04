<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyIndustryRequest;
use App\Http\Requests\UpdateCompanyIndustryRequest;
use App\Models\CompanyIndustry;
use App\Services\CompanyIndustries\CompanyIndustryLogService;
use App\Services\CompanyIndustries\CompanyIndustryManagementService;
use App\Services\CompanyIndustries\CompanyIndustryQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CompanyIndustryController extends Controller
{
    /**
     * Inject the required services into the controller.
     *
     * @param  CompanyIndustryLogService $logger Handles audit logging for
     * company industry events.
     * @param  CompanyIndustryManagementService $management Handles company industry
     * create/update/delete/restore.
     * @param  CompanyIndustryQueryService $query Handles company industry listing and
     * retrieval.
     */
    public function __construct(
        protected CompanyIndustryLogService $logger,
        protected CompanyIndustryManagementService $management,
        protected CompanyIndustryQueryService $query,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyIndustryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyIndustry $companyIndustry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyIndustryRequest $request, CompanyIndustry $companyIndustry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyIndustry $companyIndustry)
    {
        //
    }

    /**
     * Restore the specified company industry from soft deletion.
     *
     * Looks up the company industry including trashed records, then authorises via
     * the 'restore' policy. Returns 404 if the company industry is not currently
     * soft-deleted, preventing accidental double-restores.
     *
     * @param  int|string $id The primary key of the soft-deleted company industry.
     *
     * @return JsonResponse The restored company industry resource.
     *
     * @throws HttpException If the company industry is not trashed (404).
     */
    public function restore($id): JsonResponse
    {
        $companyIndustry = CompanyIndustry::withTrashed()->findOrFail($id);
        $this->authorize('restore', $companyIndustry);

        if (! $companyIndustry->trashed()) {
            abort(404);
        }

        $companyIndustry = $this->management->restore((int) $id);

        $auth = auth()->user();

        $this->logger->companyIndustryRestored(
            $auth,
            $auth->id,
            $companyIndustry,
        );

        return response()->json($companyIndustry);
    }
}
