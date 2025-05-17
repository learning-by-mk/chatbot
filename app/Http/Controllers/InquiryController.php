<?php

namespace App\Http\Controllers;

use App\Http\Requests\RespondToInquiryRequest;
use App\Http\Requests\StoreInquiryRequest;
use App\Http\Requests\UpdateInquiryRequest;
use App\Http\Resources\InquiryResource;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        // $this->authorize('viewAny', Inquiry::class);

        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));

        $allowFilter = Schema::getColumnListing('inquiries');
        $allowFilter = array_merge($allowFilter, [
            AllowedFilter::callback('ids', function ($query, $value) {
                $query->whereIn('id', $value);
            }),
            AllowedFilter::callback('status', function ($query, $value) {
                $query->where('status', $value);
            }),
        ]);

        $inquiries = QueryBuilder::for(Inquiry::class)
            ->allowedFilters($allowFilter)
            ->with($with_vals)
            ->latest()
            ->paginate(1000, ['*'], 'page', $request->get('page', 1));

        return InquiryResource::collection($inquiries);
    }

    public function store(StoreInquiryRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $inquiry = Inquiry::create($data);

        return new InquiryResource($inquiry);
    }

    public function update(UpdateInquiryRequest $request, Inquiry $inquiry)
    {
        $data = $request->validated();
        $inquiry->update($data);

        return new InquiryResource($inquiry);
    }

    public function show(Request $request, Inquiry $inquiry)
    {
        // $this->authorize('view', $inquiry);

        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));
        $inquiry = $inquiry->load($with_vals);

        return new InquiryResource($inquiry);
    }

    public function respond(UpdateInquiryRequest $request, Inquiry $inquiry)
    {
        // $this->authorize('respond', $inquiry);

        $data = $request->validated();

        if ($data['status'] === 'resolved') {
            $inquiry->resolve($data['admin_response'], $request->user());
        } elseif ($data['status'] === 'rejected') {
            $inquiry->reject($data['admin_response'], $request->user());
        } else {
            $inquiry->update([
                'status' => $data['status'],
                'admin_response' => $data['admin_response'],
                'responded_by_id' => $request->user()->id,
                'responded_at' => now(),
            ]);
        }

        return new InquiryResource($inquiry->fresh(['user', 'respondedBy']));
    }

    public function userInquiries(Request $request)
    {
        $load = $request->get('load', "");
        $with_vals = array_filter(array_map('trim', explode(',', $load)));

        $inquiries = Inquiry::where('user_id', $request->user()->id)
            ->with($with_vals)
            ->latest()
            ->paginate(1000, ['*'], 'page', $request->get('page', 1));

        return InquiryResource::collection($inquiries);
    }
}
