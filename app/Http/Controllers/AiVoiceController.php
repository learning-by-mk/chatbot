<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAiVoiceRequest;
use App\Http\Requests\UpdateAiVoiceRequest;
use App\Http\Resources\AiVoiceResource;
use App\Models\AiVoice;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;

class AiVoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $aiVoices = QueryBuilder::for(AiVoice::class)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return AiVoiceResource::collection($aiVoices);
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
    public function store(StoreAiVoiceRequest $request)
    {
        $data = $request->all();
        $chat_controller = new ChatController();
        $document = Document::find($data['document_id']);
        $response = $chat_controller->convertToSpeech($request, $document);
        $audioPath = $response['audio_path'];
        $data['url'] = Storage::url('audio/' . $audioPath);
        $data['absolute_path'] = env('APP_URL') . Storage::url('audio/' . $audioPath);
        $data['audio_path'] = $audioPath;
        $aiVoice = AiVoice::create($data);
        return new AiVoiceResource($aiVoice);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, AiVoice $aiVoice)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $aiVoice = $aiVoice->load($with_vals);
        return new AiVoiceResource($aiVoice);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AiVoice $aiVoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAiVoiceRequest $request, AiVoice $aiVoice)
    {
        $data = $request->validated();
        $aiVoice->update($data);
        return new AiVoiceResource($aiVoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AiVoice $aiVoice)
    {
        try {
            $aiVoice->delete();
            return response()->json([
                'status' => true,
                'message' => 'AI Voice deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'AI Voice not deleted'
            ], 500);
        }
    }
}
