<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatbotQuestionRequest;
use App\Http\Requests\UpdateChatbotQuestionRequest;
use App\Http\Resources\ChatbotQuestionResource;
use App\Models\ChatbotQuestion;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ChatbotQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $chatbotQuestions = QueryBuilder::for(ChatbotQuestion::class)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return ChatbotQuestionResource::collection($chatbotQuestions);
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
    public function store(StoreChatbotQuestionRequest $request)
    {
        $data = $request->validated();
        $chatbotQuestion = ChatbotQuestion::create($data);
        return new ChatbotQuestionResource($chatbotQuestion);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, ChatbotQuestion $chatbotQuestion)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $chatbotQuestion = $chatbotQuestion->load($with_vals);
        return new ChatbotQuestionResource($chatbotQuestion);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChatbotQuestion $chatbotQuestion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChatbotQuestionRequest $request, ChatbotQuestion $chatbotQuestion)
    {
        $data = $request->validated();
        $chatbotQuestion->update($data);
        return new ChatbotQuestionResource($chatbotQuestion);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatbotQuestion $chatbotQuestion)
    {
        try {
            $chatbotQuestion->delete();
            return response()->json([
                'status' => true,
                'message' => 'Chatbot Question deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Chatbot Question not deleted'
            ], 500);
        }
    }
}
