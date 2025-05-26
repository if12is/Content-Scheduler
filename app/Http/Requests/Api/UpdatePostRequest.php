<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string|max:280',
            'platform' => 'sometimes|required|string|exists:platforms,name',
            'scheduled_time' => 'sometimes|required|date|after:now',
            'image_url' => 'sometimes|nullable|image|max:2048',
            'status' => 'sometimes|required|string|in:draft,scheduled,published',
        ];
    }

    public function messages()
    {
        return [
            'platform.exists' => 'The selected platform is not available.',
            'content.max' => 'The content must not be greater than 280 characters.',
            'image_url.max' => 'The image size must not be greater than 2MB.',
        ];
    }
}
