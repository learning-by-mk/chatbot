<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChatRequest extends FormRequest
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
            'user_id' => 'sometimes|exists:users,id',
            'title' => 'sometimes|string|max:255',
            'last_message' => 'nullable|string',
            'uuid' => 'sometimes|string|unique:chats,uuid,' . $this->route('chat')->id,
            'messages' => 'sometimes|array',
            'chatbot_question_id' => 'nullable|exists:chatbot_questions,id',
        ];
    }
}
