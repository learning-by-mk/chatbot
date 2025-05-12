<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDownloadRequest;
use App\Http\Requests\UpdateDownloadRequest;
use App\Http\Resources\DownloadResource;
use App\Models\Download;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class DownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $downloads = QueryBuilder::for(Download::class)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return DownloadResource::collection($downloads);
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
    public function store(StoreDownloadRequest $request)
    {
        $data = $request->validated();
        $download = Download::create($data);
        return new DownloadResource($download);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Download $download)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $download = $download->load($with_vals);
        return new DownloadResource($download);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Download $download)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDownloadRequest $request, Download $download)
    {
        $data = $request->validated();
        $download->update($data);
        return new DownloadResource($download);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Download $download)
    {
        try {
            $download->delete();
            return response()->json([
                'status' => true,
                'message' => 'Download deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Download not deleted'
            ], 500);
        }
    }
}
