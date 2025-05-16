<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentPriceRequest;
use App\Http\Requests\UpdateDocumentPriceRequest;
use App\Http\Resources\DocumentPriceResource;
use App\Models\DocumentPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DocumentPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));

        $allowFilter = Schema::getColumnListing('users');
        $documentPrices = QueryBuilder::for(DocumentPrice::class)
            ->allowedFilters($allowFilter)
            ->with($with_vals)
            ->paginate(1000, ['*'], 'page', 1);
        return DocumentPriceResource::collection($documentPrices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentPriceRequest $request)
    {
        $data = $request->validated();
        $documentPrice = DocumentPrice::create($data);
        return new DocumentPriceResource($documentPrice);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, DocumentPrice $documentPrice)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));

        $documentPrice->load($with_vals);

        return new DocumentPriceResource($documentPrice);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentPriceRequest $request, DocumentPrice $documentPrice)
    {
        $data = $request->validated();
        $documentPrice->update($data);
        return new DocumentPriceResource($documentPrice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentPrice $documentPrice)
    {
        $documentPrice->delete();
        return response()->json(null, 204);
    }
}
