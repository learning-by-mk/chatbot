<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $allowFilter = Schema::getColumnListing('menus');
        $allowFilter = array_merge($allowFilter, [
            AllowedFilter::callback('parent_id', function ($query, $value) {
                if ($value === 'null') {
                    return $query->whereNull('parent_id');
                } elseif ($value === 'not_null') {
                    return $query->whereNotNull('parent_id');
                }
                return $query->where('parent_id', $value);
            }),
        ]);

        // $menus = Menu::filter($allowFilter)->with($with_vals)->paginate(10);
        $menus = QueryBuilder::for(Menu::class)
            ->with($with_vals)
            ->allowedFilters($allowFilter)
            ->orderBy('order', 'asc')
            ->paginate(10);
        return MenuResource::collection($menus);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request)
    {
        $data = $request->validated();
        if (empty($data['order'])) {
            $data['order'] = Menu::max('order') + 1;
        }
        $data['slug'] = Str::slug($data['name']);
        $menu = Menu::create($data);
        return new MenuResource($menu);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return new MenuResource($menu);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $data = $request->validated();
        if (empty($data['order'])) {
            $data['order'] = Menu::max('order') + 1;
        }
        $data['slug'] = Str::slug($data['name']);
        $menu->update($data);
        return new MenuResource($menu);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json(['message' => 'Menu deleted successfully']);
    }
}
