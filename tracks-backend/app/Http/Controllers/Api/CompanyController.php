<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CompanyRequest;
use App\Http\Requests\Company\CompanyStoreRequest;
use App\Http\Requests\Company\CompanyUpdateRequest;
use App\Http\Resources\Company\CompanyCollection;
use App\Http\Resources\Company\CompanyJson;
use App\Models\Company;
use App\Services\Company\CompanyService;

class CompanyController extends Controller
{
    public function __construct(private CompanyService $srvCompany)
    {}

    public function index(CompanyRequest $request): CompanyCollection
    {
        $filters =  $request->validated();
        $filters['search'] = $request->input('search') ?? '';
        $companyQuery = Company::query();
        $companyQuery = $this->srvCompany->getBySearch($companyQuery, $filters);
        $companies = $companyQuery->orderBy('companies.name', 'ASC');
        $perPage = $request->input('per_page', 10);

        return new CompanyCollection($companies->paginate($perPage));
    }

    public function list(): CompanyCollection
    {
        $companyQuery = Company::query();
        $companyQuery = $this->srvCompany->getBySearch($companyQuery, []);
        $companies = $companyQuery->orderBy('companies.name', 'ASC');

        return new CompanyCollection($companies->get());
    }

    public function store(CompanyStoreRequest $request): CompanyJson
    {
        $company = $this->srvCompany->save($request->validated());

        return new CompanyJson($company);
    }

    public function show(string $uuid): CompanyJson
    {
        $company = Company::whereUuid($uuid)->firstOrFail();

        return new CompanyJson($company);
    }

    public function update(CompanyUpdateRequest $request, string $uuid): CompanyJson
    {
        $company = Company::whereUuid($uuid)->firstOrFail();
        $company = $this->srvCompany->update($company, $request->validated());

        return new CompanyJson($company);
    }

    public function destroy(string $uuid): CompanyJson
    {
        $company = Company::whereUuid($uuid)->firstOrFail();
        $this->srvCompany->delete($company);

        return new CompanyJson($company);
    }

}
