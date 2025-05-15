<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));

        $topics = QueryBuilder::for(Topic::class)
            ->with($with_vals)
            ->latest()
            ->paginate(1000, ['*'], 'page', $request->get('page', 1));

        return TopicResource::collection($topics);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTopicRequest $request)
    {
        $data = $request->validated();

        $topic = Topic::create($data);

        return new TopicResource($topic);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Topic $topic)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));

        $topic->load($with_vals);

        return new TopicResource($topic);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        $data = $request->validated();

        $topic->update($data);

        return new TopicResource($topic);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();

        return response()->json(['message' => 'Topic deleted successfully']);
    }
}
