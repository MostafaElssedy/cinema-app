<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
            'seats' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $seats = array_filter(array_map('trim', explode(',', $value)));
                    if (empty($seats)) {
                        $fail('Please select at least one seat to book.');
                    }
                    if (count($seats) > 8) {
                        $fail('You can book a maximum of 8 seats per order.');
                    }
                },
            ],
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'required|email|max:100',
            'customer_phone' => 'required|string|max:25',
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'seats.required' => 'Please select at least one seat to book.',
            'customer_name.required' => 'Customer name is required.',
            'customer_email.required' => 'Customer email is required.',
            'customer_email.email' => 'Please enter a valid email address.',
            'customer_phone.required' => 'Customer phone number is required.',
        ];
    }
}
