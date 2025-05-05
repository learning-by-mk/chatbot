<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        if (isset($data['search'])) {
            $posts = Post::where('title', 'like', '%' . $data['search'] . '%')
                ->orWhere('content', 'like', '%' . $data['search'] . '%')
                ->get();
        } else {
            $posts = Post::all();
        }
        return view('admin.posts.index', compact('posts', 'request'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        // Upload image
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images'), $fileName);
            $data['image'] = $fileName;
        }
        $data['user_id'] = Auth::user()->id;
        $post = Post::create($data);
        return redirect()->route('admin.posts.index')->with('success', 'Bài viết đã được tạo thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // return view('admin.posts.show', compact('post'));
        return response()->json(new PostResource($post));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());
        return redirect()->route('admin.posts.index')->with('success', 'Bài viết đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Bài viết đã được xóa thành công');
    }
}
