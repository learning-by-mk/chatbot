<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));

        $documents = QueryBuilder::for(Document::class)
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['uploaded_by_id'])) {
            $data['uploaded_by_id'] = Auth::id();
        }
        $document = Document::create($data);
        $document->categories()->sync($data['category_ids']);
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
        $document->update($data);
        $document->categories()->sync($data['category_ids']);
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
            'is_favorite' => $is_favorite
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
}
