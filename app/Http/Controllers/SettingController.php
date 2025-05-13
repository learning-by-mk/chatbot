<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Http\Resources\SettingResouce;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $settings = Setting::all();
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $settings = QueryBuilder::for(Setting::class)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return SettingResource::collection($settings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SettingRequest $request)
    {
        $data = $request->validated();
        $setting = Setting::create($data);
        return new SettingResource($setting);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Setting $setting)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $setting = $setting->load($with_vals);
        return new SettingResource($setting);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingRequest $request, Setting $setting)
    {
        $data = $request->validated();
        $setting->update($data);
        return new SettingResource($setting);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        try {
            $setting->delete();
            return response()->json([
                'status' => true,
                'message' => 'Setting deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Setting deleted failed with error: ' . $e->getMessage()
            ]);
        }
    }
}
