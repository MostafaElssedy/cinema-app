<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TheaterRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'rows_count' => 'required|integer|min:2|max:20',
            'seats_per_row' => 'required|integer|min:4|max:30',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Theater hall name is required.',
            'type.required' => 'Please select the theater screen type.',
            'rows_count.required' => 'Number of rows is required.',
            'rows_count.min' => 'Theater must have at least 2 rows.',
            'seats_per_row.required' => 'Seats per row is required.',
            'seats_per_row.min' => 'Each row must have at least 4 seats.',
        ];
    }
}
