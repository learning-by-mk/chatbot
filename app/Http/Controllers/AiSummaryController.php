<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAiSummaryRequest;
use App\Http\Requests\UpdateAiSummaryRequest;
use App\Http\Resources\AiSummaryResource;
use App\Models\AiSummary;
use App\Models\Document;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class AiSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $aiSummaries = QueryBuilder::for(AiSummary::class)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return AiSummaryResource::collection($aiSummaries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAiSummaryRequest $request)
    {
        $data = $request->all();
        $chat_controller = new ChatController();
        $document = Document::find($data['document_id']);
        $response = $chat_controller->summary($request, $document);
        $summary = $response['summary'];

        $aiSummary = AiSummary::create([
            'document_id' => $data['document_id'],
            'summary' => $summary,
        ]);
        return new AiSummaryResource($aiSummary);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, AiSummary $aiSummary)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $aiSummary = $aiSummary->load($with_vals);
        return new AiSummaryResource($aiSummary);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAiSummaryRequest $request, AiSummary $aiSummary)
    {
        $data = $request->validated();
        $aiSummary->update($data);
        return new AiSummaryResource($aiSummary);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AiSummary $aiSummary)
    {
        try {
            $aiSummary->delete();
            return response()->json([
                'status' => true,
                'message' => 'AI Summary deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'AI Summary not deleted'
            ], 500);
        }
    }
}
