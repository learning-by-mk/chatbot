<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavoriteRequest;
use App\Http\Requests\UpdateFavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $favorites = QueryBuilder::for(Favorite::class)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return FavoriteResource::collection($favorites);
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
    public function store(StoreFavoriteRequest $request)
    {
        $data = $request->validated();
        $favorite = Favorite::create($data);
        return new FavoriteResource($favorite);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Favorite $favorite)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $favorite = $favorite->load($with_vals);
        return new FavoriteResource($favorite);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFavoriteRequest $request, Favorite $favorite)
    {
        $data = $request->validated();
        $favorite->update($data);
        return new FavoriteResource($favorite);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite)
    {
        try {
            $favorite->delete();
            return response()->json([
                'status' => true,
                'message' => 'Favorite deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Favorite not deleted'
            ], 500);
        }
    }
}
