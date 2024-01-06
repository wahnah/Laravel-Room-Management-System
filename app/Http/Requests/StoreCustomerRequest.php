<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            //'address' => 'required|max:255',
            //'job' => 'required',
            'birthdate' => 'required',
            'gender' => 'required|in:Male,Female',
            'email' => 'unique:users,email',
            'phone_number' => 'required|unique:users,phone_number',
            'avatar' => 'mimes:png,jpg',
        ];
    }
}
