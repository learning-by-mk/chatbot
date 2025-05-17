<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAiSummaryRequest;
use App\Http\Requests\StoreAiVoiceRequest;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\Document;
use App\Models\DocumentLike;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $allowFilter = Schema::getColumnListing('documents');
        $allowFilter = array_merge($allowFilter, [
            AllowedFilter::callback('search', function ($query, $value) {
                $query->where('title', 'like', "%$value%")
                    ->orWhere('description', 'like', "%$value%")
                    ->orWhereHas('topics', function ($query) use ($value) {
                        $query->where('name', 'like', "%$value%");
                    })
                    ->orWhereHas('category', function ($query) use ($value) {
                        $query->where('name', 'like', "%$value%");
                    })
                    ->orWhereHas('author', function ($query) use ($value) {
                        $query->where('name', 'like', "%$value%");
                    });
            }),
            AllowedFilter::callback('categories', function ($query, $value) {
                $categories = is_array($value) ? $value : array_map('intval', explode(',', $value));
                return $query->whereIn('category_id', $categories);
            }),

            AllowedFilter::callback('topics', function ($query, $value) {
                $topics = is_array($value) ? $value : array_map('intval', explode(',', $value));
                return $query->whereHas('topics', function ($query) use ($topics) {
                    $query->whereIn('topics.id', $topics);
                });
            }),

            AllowedFilter::callback('rating', function ($query, $value) {
                if ($value && isset($value['operator'])) {
                    $operator = $value['operator'];
                    $ratingValue = $value['value'];
                    if ($ratingValue == 0) {
                        return null;
                    }

                    switch ($operator) {
                        case 'gte':
                            return $query->where('average_rating', '>=', $ratingValue);
                        case 'lte':
                            return $query->where('average_rating', '<=', $ratingValue);
                        case 'gt':
                            return $query->where('average_rating', '>', $ratingValue);
                        case 'lt':
                            return $query->where('average_rating', '<', $ratingValue);
                        default:
                            return $query->where('average_rating', $ratingValue);
                    }
                } else {
                    return $query->where('average_rating', $value);
                }
            }),
        ]);


        $documents = QueryBuilder::for(Document::class)
            ->allowedFilters($allowFilter)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return DocumentResource::collection($documents);
    }

    public function user_favorites(Request $request)
    {
        $user_id = $request->user()->id;
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $documents = Document::whereHas('favorites', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->with($with_vals)->paginate(1000, ['*'], 'page', 1);
        return DocumentResource::collection($documents);
    }

    public function user_liked(Request $request)
    {
        $user_id = $request->user()->id;
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $documents = Document::whereHas('likes', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->with($with_vals)->paginate(1000, ['*'], 'page', 1);
        return DocumentResource::collection($documents);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['uploaded_by_id'])) {
            $data['uploaded_by_id'] = Auth::id();
        }
        if (isset($data['is_draft']) && $data['is_draft']) {
            unset($data['is_draft']);
            $data['status'] = 'draft';
        }
        $data['slug'] = Str::slug($data['title']);
        $document = Document::create($data);
        $document->topics()->sync($data['topic_ids']);
        return new DocumentResource($document);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Document $document)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $document = $document->load($with_vals);
        return new DocumentResource($document);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['title']);
        $document->update($data);
        $document->topics()->sync($data['topic_ids']);
        $document->save();
        return new DocumentResource($document);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        try {
            $document->delete();
            return response()->json([
                'status' => true,
                'message' => 'Document deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Document not deleted'
            ], 500);
        }
    }

    public function is_favorite(Document $document)
    {
        $is_favorite = $document->favorites()->where('user_id', Auth::id())->exists();
        return response()->json([
            'data' => [
                'is_favorite' => $is_favorite
            ]
        ]);
    }

    // public function list_favorite(Request $request)
    // {
    //     $load = $request->get('load', "");
    //     $with_vals = array_filter(array_map('trim', explode(',', $load)));
    //     $documents = Document::whereHas('favorites', function ($query) {
    //         $query->where('user_id', Auth::id());
    //     })->with($with_vals)->paginate(1000, ['*'], 'page', 1);
    //     return DocumentResource::collection($documents);
    // }

    public function favorite(Document $document)
    {
        $document->favorites()->create(['user_id' => Auth::id()]);
        return response()->json([
            'message' => 'Document favorited successfully'
        ]);
    }

    public function unfavorite(Document $document)
    {
        $document->favorites()->where('user_id', Auth::id())->delete();
        return response()->json([
            'message' => 'Document unfavorited successfully'
        ]);
    }

    public function get_comments(Request $request, Document $document)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $comments = $document->comments()->with($with_vals)->paginate(1000, ['*'], 'page', 1);
        return CommentResource::collection($comments);
    }

    public function is_liked(Document $document)
    {
        $is_liked = $document->likes()->where('user_id', Auth::id())->exists();
        return response()->json([
            'data' => [
                'is_liked' => $is_liked
            ]
        ]);
    }

    public function like(Request $request, Document $document)
    {
        $user_id = Auth::id();

        $exists = DocumentLike::where('document_id', $document->id)
            ->where('user_id', $user_id)
            ->exists();

        if (!$exists) {
            DocumentLike::create([
                'document_id' => $document->id,
                'user_id' => $user_id
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Document liked successfully'
        ]);
    }

    public function unlike(Request $request, Document $document)
    {
        $user_id = Auth::id();

        DocumentLike::where('document_id', $document->id)
            ->where('user_id', $user_id)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Document unliked successfully'
        ]);
    }

    public function download(Request $request, Document $document)
    {
        if ($document->downloads()->where('user_id', Auth::id())->exists()) {
            return response()->json([
                'status' => true,
                'message' => 'Document already downloaded'
            ], 200);
        }
        $document->downloads()->create(['user_id' => Auth::id()]);
        return response()->json([
            'status' => true,
            'message' => 'Document downloaded successfully'
        ], 200);
    }

    public function ai_summary(Request $request, Document $document)
    {
        $data = $request->all();
        $ai_summary_request = StoreAiSummaryRequest::create(route('ai-summaries.store', [
            'document_id' => $document->id
        ]), 'POST', $data);

        $ai_summary_controller = new AiSummaryController();
        $ai_summary_controller->store($ai_summary_request);
        return response()->json([
            'status' => true,
            'message' => 'Document ai summary created successfully'
        ], 200);
    }

    public function ai_voice(Request $request, Document $document)
    {
        $data = $request->all();
        $ai_voice_request = StoreAiVoiceRequest::create(route('ai-voices.store', [
            'document_id' => $document->id
        ]), 'POST', $data);

        $ai_voice_controller = new AiVoiceController();
        $ai_voice_controller->store($ai_voice_request);
        return response()->json([
            'status' => true,
            'message' => 'Document ai voice created successfully'
        ], 200);
    }

    public function chat(Request $request, Document $document)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $chat = $document->chat();
        if ($chat instanceof Chat) {
            $chat = $chat->load($with_vals);
            return new ChatResource($chat);
        }
        return response()->json([
            'data' => []
        ], 200);
    }
}
