<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlatformRequest extends FormRequest
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
        $platform = strtolower($this->route('platform'));

        $rules = [];

        switch ($platform) {
            case 'twitter':
                $rules = [
                    'api_key' => 'nullable|string',
                    'api_secret_key' => 'nullable|string',
                    'access_token' => 'nullable|string',
                    'access_token_secret' => 'nullable|string',
                ];
                break;
            case 'facebook':
                $rules = [
                    'app_id' => 'nullable|string',
                    'app_secret' => 'nullable|string',
                    'page_id' => 'nullable|string',
                    'user_access_token' => 'nullable|string',
                ];
                break;
            case 'instagram':
                $rules = [
                    'app_id' => 'nullable|string',
                    'app_secret' => 'nullable|string',
                    'instagram_business_id' => 'nullable|string',
                    'user_access_token' => 'nullable|string',
                ];
                break;
            case 'linkedin':
                $rules = [
                    'client_id' => 'nullable|string',
                    'client_secret' => 'nullable|string',
                    'access_token' => 'nullable|string',
                    'organization_id' => 'nullable|string',
                ];
                break;
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            '*.required' => 'This field is required to connect the platform.',
        ];
    }
}
