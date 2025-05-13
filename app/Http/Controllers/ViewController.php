<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreViewRequest;
use App\Http\Requests\UpdateViewRequest;
use App\Models\View;
use Illuminate\Http\Request;
use App\Http\Resources\ViewResource;

class ViewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $views = View::with($with_vals)->get();
        return ViewResource::collection($views);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreViewRequest $request)
    {
        $data = $request->validated();
        $view = View::create($data);
        return new ViewResource($view);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, View $view)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $view = $view->load($with_vals);
        return new ViewResource($view);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateViewRequest $request, View $view)
    {
        $data = $request->validated();
        $view->update($data);
        return new ViewResource($view);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(View $view)
    {
        $view->delete();
        return response()->json([
            'status' => true,
            'message' => 'View deleted successfully'
        ]);
    }
}
