<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $url_key
 * @property string $destination_url
 */
class ShortURLRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'destination_url' => ['required', 'url', 'max:2048', 'URL'],
            'url_key' => ['nullable', 'alpha_num', 'max:10', 'min:6', 'unique:short_urls,url_key'],
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'destination_url' => 'destination URL',
            'url_key' => 'URL key',
        ];
    }
}
