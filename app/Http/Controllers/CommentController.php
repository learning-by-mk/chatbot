<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Document;
use App\Models\CommentLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $comments = QueryBuilder::for(Comment::class)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $comment = Comment::create($data);
        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Comment $comment)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $comment = $comment->load($with_vals);
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $comment->update($data);
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();
            return response()->json([
                'status' => true,
                'message' => 'Comment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Comment not deleted'
            ], 500);
        }
    }

    public function get_like_ids(Request $request, Document $document)
    {
        $user_id = Auth::id();

        // Lấy tất cả comments của document
        $commentIds = $document->comments()->pluck('id');

        // Lấy tất cả comment_like của người dùng hiện tại trong các comment thuộc document này
        $likedCommentIds = CommentLike::whereIn('comment_id', $commentIds)
            ->where('user_id', $user_id)
            ->pluck('comment_id');

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách comment đã like thành công',
            'like_ids' => $likedCommentIds
        ]);
    }

    public function like(Request $request, Comment $comment)
    {
        $user_id = Auth::id();

        // Kiểm tra xem người dùng đã like comment này chưa
        $exists = CommentLike::where('comment_id', $comment->id)
            ->where('user_id', $user_id)
            ->exists();

        if (!$exists) {
            CommentLike::create([
                'comment_id' => $comment->id,
                'user_id' => $user_id
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Comment liked successfully'
        ]);
    }

    public function unlike(Request $request, Comment $comment)
    {
        $user_id = Auth::id();

        CommentLike::where('comment_id', $comment->id)
            ->where('user_id', $user_id)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Comment unliked successfully'
        ]);
    }
}
