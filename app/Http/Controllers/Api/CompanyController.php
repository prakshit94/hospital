<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 50);
        $query = Company::query()->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $companies = $query->orderBy('name')->paginate($perPage);

        return response()->json($companies);
    }

    public function show(Company $company): JsonResponse
    {
        return response()->json($company);
    }
}
