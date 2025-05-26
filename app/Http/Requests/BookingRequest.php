<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

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
            'room_type_id' => 'required|exists:room_types,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'room_type_id.required' => 'Room type is required',
            'room_type_id.exists' => 'Selected room type does not exist',
            'check_in_date.required' => 'Check-in date is required',
            'check_in_date.after_or_equal' => 'Check-in date must be today or later',
            'check_out_date.required' => 'Check-out date is required',
            'check_out_date.after' => 'Check-out date must be after check-in date',
            'guests.required' => 'Number of guests is required',
            'guests.min' => 'At least 1 guest is required',
            'special_requests.max' => 'Special requests cannot exceed 1000 characters',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->check_in_date && $this->check_out_date) {
                $checkIn = Carbon::parse($this->check_in_date);
                $checkOut = Carbon::parse($this->check_out_date);
                
                // Check if booking is too far in advance (max 1 year)
                if ($checkIn->diffInDays(now()) > 365) {
                    $validator->errors()->add('check_in_date', 'Check-in date cannot be more than 1 year in advance');
                }
                
                // Check minimum stay (1 night)
                if ($checkIn->diffInDays($checkOut) < 1) {
                    $validator->errors()->add('check_out_date', 'Minimum stay is 1 night');
                }
                
                // Check maximum stay (30 nights)
                if ($checkIn->diffInDays($checkOut) > 30) {
                    $validator->errors()->add('check_out_date', 'Maximum stay is 30 nights');
                }
            }
        });
    }
}
