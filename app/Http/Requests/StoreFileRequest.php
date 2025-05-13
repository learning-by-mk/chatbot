<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class StoreFileRequest extends FormRequest
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
            'file' => 'required|file',
            'name' => 'nullable|string',
            'folder' => 'nullable|string|max:255',
            'disk' => [
                'nullable',
                'string',
                'max:255',
                function (string $attribute, string|null $value, Closure $fail) {
                    if ($value) {
                        try {
                            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
                            $disk = Storage::disk($value);
                        } catch (\Throwable $th) {
                            $fail("The disk {$value} does not exist");
                        }
                    }
                },
            ],
        ];
    }
}
