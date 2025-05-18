<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\HistoryPointResource;
use App\Http\Resources\UserResource;
use App\Models\Document;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));

        $allowFilter = Schema::getColumnListing('users');
        $allowFilter = array_merge($allowFilter, [
            AllowedFilter::callback('ids', function ($query, $value) {
                $query->whereIn('id', $value);
            })
        ]);

        $users = QueryBuilder::for(User::class)
            ->allowedFilters($allowFilter)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return UserResource::collection($users);
    }

    public function documents(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $user = $request->user();

        $allowFilter = Schema::getColumnListing('documents');
        $resource = QueryBuilder::for(Document::class)
            ->allowedFilters($allowFilter)
            ->where(function ($query) use ($user) {
                $query->where('author_id', $user->id)
                    ->orWhere('uploaded_by_id', $user->id);
            })
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);

        return DocumentResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        if (isset($data['avatar_file_id'])) {
            $file = File::find($data['avatar_file_id']);
            $file->user_id = Auth::user()->id;
            $file->save();
        }
        $user = User::create($data);
        $user->syncRoles([$data['role']]);
        return new UserResource($user);
    }

    public function statistics(Request $request)
    {
        $user = $request->user();
        $statistics = $user->statistics();
        return response()->json([
            'data' => $statistics,
            'status' => true,
            'message' => 'Statistics retrieved successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $user = $user->load($with_vals);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        if (empty($data['password'])) {
            unset($data['password']);
            unset($data['password_confirmation']);
        } else {
            if ($data['password'] !== $data['password_confirmation']) {
                throw new \Exception('Mật khẩu không khớp');
            }
            $data['password'] = Hash::make($data['password']);
        }
        if ($request->hasFile('avatar') && $request->file('avatar') !== null) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', ['disk' => 'public']);
            $data['avatar'] = $path;
        } else {
            unset($data['avatar']);
        }
        if (isset($data['avatar_file_id'])) {
            $file = File::find($data['avatar_file_id']);
            $file->user_id = $user->id;
            $file->save();
        }
        $user->syncRoles($data['role']);
        unset($data['role']);
        $user->update($data);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json([
                'status' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'User not deleted'
            ], 500);
        }
    }

    public function destroy_document(Document $document)
    {
        $document->delete();
        return response()->json([
            'status' => true,
            'message' => 'Document deleted successfully'
        ]);
    }

    public function favorites(Request $request)
    {
        $load = $request->get('load', "");
        $user = $request->user();
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $documents = $user->favorites()->with($with_vals)->paginate(1000, ['*'], 'page', 1);
        return DocumentResource::collection($documents);
    }

    public function history_points(Request $request)
    {
        $user = $request->user();
        $historyPoints = $user->historyPoints()->orderBy('created_at', 'desc')->paginate(1000, ['*'], 'page', 1);
        return HistoryPointResource::collection($historyPoints);
    }
}
