<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePointPackageRequest;
use App\Http\Requests\UpdatePointPackageRequest;
use App\Http\Resources\PointPackageResource;
use App\Models\PointPackage;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Schema;

class PointPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $limit = $request->get('limit', 10);
        $allowFilter = Schema::getColumnListing('point_packages');
        $resource = QueryBuilder::for(PointPackage::class)
            ->allowedFilters($allowFilter)
            ->with($with_vals)
            ->paginate($limit, ['*'], 'page', 1);

        return PointPackageResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePointPackageRequest $request)
    {
        $data = $request->validated();
        $pointPackage = PointPackage::create($data);
        return new PointPackageResource($pointPackage);
    }

    /**
     * Display the specified resource.
     */
    public function show(PointPackage $pointPackage)
    {
        return new PointPackageResource($pointPackage);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PointPackage $pointPackage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePointPackageRequest $request, PointPackage $pointPackage)
    {
        $data = $request->validated();
        $pointPackage->update($data);
        return new PointPackageResource($pointPackage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PointPackage $pointPackage)
    {
        $pointPackage->delete();
        return response()->json([
            'message' => 'Point package deleted successfully'
        ]);
    }
}
