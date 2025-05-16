<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentPurchaseRequest;
use App\Http\Requests\UpdateDocumentPurchaseRequest;
use App\Http\Resources\DocumentPurchaseResource;
use App\Models\DocumentPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Spatie\QueryBuilder\QueryBuilder;

class DocumentPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));

        $allowFilter = Schema::getColumnListing('users');
        $documentPurchases = QueryBuilder::for(DocumentPurchase::class)
            ->allowedFilters($allowFilter)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return DocumentPurchaseResource::collection($documentPurchases);
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
    public function store(StoreDocumentPurchaseRequest $request)
    {
        $data = $request->validated();
        $documentPurchase = DocumentPurchase::create($data);
        return new DocumentPurchaseResource($documentPurchase);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, DocumentPurchase $documentPurchase)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));

        $documentPurchase->load($with_vals);

        return new DocumentPurchaseResource($documentPurchase);
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentPurchaseRequest $request, DocumentPurchase $documentPurchase)
    {
        $data = $request->validated();
        $documentPurchase->update($data);
        return new DocumentPurchaseResource($documentPurchase);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentPurchase $documentPurchase)
    {
        $documentPurchase->delete();
        return response()->json(null, 204);
    }
}
