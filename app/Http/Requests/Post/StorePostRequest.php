<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:280',
            'platform' => 'required|string|exists:platforms,name',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required|string',
            'image_url' => 'nullable|image|max:2048', // 2MB Max
            'status' => 'required|in:draft,scheduled',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'platform.exists' => 'The selected platform is not available.',
            'content.max' => 'The content must not be greater than 280 characters.',
            'image.max' => 'The image size must not be greater than 2MB.',
        ];
    }
}
