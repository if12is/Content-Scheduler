<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:280',
            'platform' => 'required|string|exists:platforms,name',
            'scheduled_time' => 'required|date|after:now',
            'image_url' => 'nullable|image|max:2048',
            'status' => 'required|string|in:draft,scheduled,published',
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
