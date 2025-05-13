<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingGroupRequest;
use App\Http\Resources\SettingGroupResource;
use App\Models\SettingGroup;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class SettingGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $settingGroups = QueryBuilder::for(SettingGroup::class)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return SettingGroupResource::collection($settingGroups);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SettingGroupRequest $request)
    {
        $data = $request->validated();
        $settingGroup = SettingGroup::create($data);
        return new SettingGroupResource($settingGroup);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, SettingGroup $settingGroup)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $settingGroup = $settingGroup->load($with_vals);
        return new SettingGroupResource($settingGroup);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingGroupRequest $request, SettingGroup $settingGroup)
    {
        $data = $request->validated();
        $settingGroup->update($data);
        return new SettingGroupResource($settingGroup);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SettingGroup $settingGroup)
    {
        try {
            $settingGroup->delete();
            return response()->json([
                'status' => true,
                'message' => 'SettingGroup deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'SettingGroup deleted failed with error: ' . $e->getMessage()
            ]);
        }
    }
}
