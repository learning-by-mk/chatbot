<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSystemConfigRequest;
use App\Http\Requests\UpdateSystemConfigRequest;
use App\Http\Resources\SystemConfigResource;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class SystemConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $configs = QueryBuilder::for(SystemConfig::class)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return SystemConfigResource::collection($configs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSystemConfigRequest $request)
    {
        $data = $request->validated();
        $config = SystemConfig::create($data);
        return new SystemConfigResource($config);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, SystemConfig $systemConfig)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $systemConfig = $systemConfig->load($with_vals);
        return new SystemConfigResource($systemConfig);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SystemConfig $systemConfig)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSystemConfigRequest $request, SystemConfig $systemConfig)
    {
        $data = $request->validated();
        $systemConfig->update($data);
        return new SystemConfigResource($systemConfig);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SystemConfig $systemConfig)
    {
        try {
            $systemConfig->delete();
            return response()->json([
                'status' => true,
                'message' => 'System Config deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'System Config not deleted'
            ], 500);
        }
    }
}
