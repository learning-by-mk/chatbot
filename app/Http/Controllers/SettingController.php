<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Http\Resources\SettingResouce;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Schema;
use Spatie\QueryBuilder\AllowedFilter;

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

        $columns = Schema::getColumnListing('settings');

        $settings = QueryBuilder::for(Setting::class)
            ->with($with_vals)
            ->allowedFilters([
                ...$columns,
                AllowedFilter::callback('group.key', function ($query, $value) {
                    $query->whereHas('group', function ($query) use ($value) {
                        $query->where('key', $value);
                    });
                }),
            ])
            ->whereNull('parent_id')
            ->paginate(1000, ['*'], 'page', 1);
        return SettingResource::collection($settings);
    }

    private function createNullableFilter($field)
    {
        return AllowedFilter::callback($field, function ($query, $value) use ($field) {
            if ($value === 'null') {
                $query->whereNull($field);
            } else if ($value === 'not_null') {
                $query->whereNotNull($field);
            } else {
                $query->where($field, $value);
            }
        });
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
