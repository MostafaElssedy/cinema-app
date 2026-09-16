<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WatchlistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => ['required', 'string', 'max:255'],
            'movie_id' => ['required', 'exists:movies,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'device_id.required' => 'Device ID is required.',
            'movie_id.required' => 'Movie ID is required.',
            'movie_id.exists' => 'The selected movie does not exist.',
        ];
    }
}
