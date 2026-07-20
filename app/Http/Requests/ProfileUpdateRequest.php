<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            'phone' => [
                'required',
                'digits:10',
            ],

            'card_number' => [
                'required',
                'digits_between:13,19',
            ],

            'card_exp_month' => [
                'required',
                'integer',
                'between:1,12',
            ],

            'card_exp_year' => [
                'required',
                'integer',
                'min:' . date('Y'),
            ],

            'card_cvv' => [
                'required',
                'digits_between:3,4',
            ],
        ];
    }
}
