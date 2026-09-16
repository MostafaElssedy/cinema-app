<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowtimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'movie_id' => 'required|exists:movies,id',
            'theater_id' => 'required|exists:theaters,id',
            'show_date' => 'required|date',
            'start_time' => 'required|string|max:30',
            'price' => 'required|numeric|min:1|max:5000',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'movie_id.required' => 'Please select a movie.',
            'movie_id.exists' => 'The selected movie does not exist.',
            'theater_id.required' => 'Please select a theater hall.',
            'theater_id.exists' => 'The selected theater does not exist.',
            'show_date.required' => 'Show date is required.',
            'start_time.required' => 'Start time is required (e.g. 08:00 PM).',
            'price.required' => 'Ticket price is required.',
            'price.min' => 'Ticket price must be at least 1 EGP.',
        ];
    }
}
