<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublisherRequest;
use App\Http\Requests\UpdatePublisherRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\PublisherResource;
use App\Models\File;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));

        $allowFilter = Schema::getColumnListing('publishers');
        $allowFilter = array_merge($allowFilter, [
            AllowedFilter::callback('search', function ($query, $value) {
                $query->where('name', 'like', "%$value%")
                    ->orWhere('description', 'like', "%$value%")
                    ->orWhere('address', 'like', "%$value%");
            }),
        ]);

        $publishers = QueryBuilder::for(Publisher::class)
            ->allowedFilters($allowFilter)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return PublisherResource::collection($publishers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePublisherRequest $request)
    {
        $data = $request->validated();

        if (isset($data['logo_file_id'])) {
            $file = File::find($data['logo_file_id']);
            $file->user_id = Auth::user()->id;
            $file->save();
        }
        $publisher = Publisher::create($data);
        return new PublisherResource($publisher);
    }

    public function statistics(Request $request, Publisher $publisher)
    {
        $statistics = $publisher->statistics();
        return response()->json([
            'data' => $statistics,
            'status' => true,
            'message' => 'Statistics retrieved successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Publisher $publisher)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $publisher = $publisher->load($with_vals);
        return new PublisherResource($publisher);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePublisherRequest $request, Publisher $publisher)
    {
        $data = $request->validated();

        if (isset($data['logo_file_id'])) {
            $file = File::find($data['logo_file_id']);
            $file->user_id = Auth::user()->id;
            $file->save();
        }

        $publisher->update($data);
        return new PublisherResource($publisher);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher)
    {
        try {
            $publisher->delete();
            return response()->json([
                'status' => true,
                'message' => 'Publisher deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Publisher not deleted'
            ], 500);
        }
    }

    public function documents(Request $request, Publisher $publisher)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $documents = $publisher->documents()->with($with_vals)->paginate(1000, ['*'], 'page', 1);
        return DocumentResource::collection($documents);
    }
}
