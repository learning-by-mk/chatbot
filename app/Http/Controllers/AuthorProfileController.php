<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorProfileRequest;
use App\Http\Requests\UpdateAuthorProfileRequest;
use App\Http\Resources\AuthorProfileResource;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\UserResource;
use App\Models\AuthorProfile;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AuthorProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));

        $allowFilter = Schema::getColumnListing('author_profiles');
        $allowFilter = array_merge($allowFilter, [
            AllowedFilter::callback('search', function ($query, $value) {
                $query->whereHas('user', function ($q) use ($value) {
                    $q->where('name', 'like', "%$value%");
                })->orWhere('biography', 'like', "%$value%")
                    ->orWhere('education', 'like', "%$value%")
                    ->orWhere('specialization', 'like', "%$value%");
            }),
        ]);

        $profiles = QueryBuilder::for(AuthorProfile::class)
            ->allowedFilters($allowFilter)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return AuthorProfileResource::collection($profiles);
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
    public function store(StoreAuthorProfileRequest $request)
    {
        $data = $request->validated();
        $authorProfile = AuthorProfile::create($data);
        $authorProfile->updateStatistics();
        return new AuthorProfileResource($authorProfile);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, AuthorProfile $authorProfile)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $authorProfile = $authorProfile->load($with_vals);
        return new AuthorProfileResource($authorProfile);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorProfileRequest $request, AuthorProfile $authorProfile)
    {
        $data = $request->validated();
        $authorProfile->update($data);
        return new AuthorProfileResource($authorProfile);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuthorProfile $authorProfile)
    {
        try {
            $authorProfile->delete();
            return response()->json([
                'status' => true,
                'message' => 'Author profile deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Author profile not deleted'
            ], 500);
        }
    }

    public function documents(Request $request, AuthorProfile $authorProfile)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $userId = $authorProfile->user_id;
        $documents = Document::where('author_id', $userId)->with($with_vals)->paginate(1000, ['*'], 'page', 1);
        return DocumentResource::collection($documents);
    }

    public function getAuthors(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));

        $authors = User::whereHas('roles', function ($q) {
            $q->where('name', 'author');
        })->with($with_vals)->paginate(1000, ['*'], 'page', 1);

        return UserResource::collection($authors);
    }
}
