<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'release_year' => 'required|integer|min:1888|max:' . (date('Y') + 5),
            'rating' => 'required|numeric|min:0|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'trailer_url' => 'nullable|url|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Movie title is required.',
            'description.required' => 'Movie description is required.',
            'release_year.required' => 'Release year is required.',
            'rating.required' => 'Rating is required.',
            'rating.max' => 'Rating cannot exceed 10.',
            'image.image' => 'File must be a valid image (jpeg, png, jpg, webp).',
            'image.max' => 'Image size cannot exceed 4MB.',
        ];
    }
}
