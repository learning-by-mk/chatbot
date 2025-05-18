<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Rating;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $ratings = QueryBuilder::for(Rating::class)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return RatingResource::collection($ratings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRatingRequest $request)
    {
        $data = $request->validated();
        $rating = Rating::create($data);
        return new RatingResource($rating);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Rating $rating)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $rating = $rating->load($with_vals);
        return new RatingResource($rating);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRatingRequest $request, Rating $rating)
    {
        $data = $request->validated();
        $rating->update($data);
        return new RatingResource($rating);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        try {
            $rating->delete();
            return response()->json([
                'status' => true,
                'message' => 'Rating deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Rating not deleted'
            ], 500);
        }
    }
}
