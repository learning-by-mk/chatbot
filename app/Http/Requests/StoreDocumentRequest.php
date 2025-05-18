<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file_id' => 'required|exists:files,id',
            'image_id' => 'required|exists:files,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'topic_ids' => [
                'required',
                'array',
                'exists:topics,id',
            ],
            'author_id' => 'required|exists:users,id',
            'uploaded_by_id' => 'nullable|exists:users,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'is_free' => 'nullable|boolean',
            'price' => 'required_if:is_free,false|numeric|min:0',
            'status' => 'nullable|in:pending,approved,rejected,draft',
            'content' => 'nullable|string',
            'is_draft' => 'nullable|boolean',
        ];
    }
}
