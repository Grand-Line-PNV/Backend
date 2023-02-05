<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username' => 'required|string|max:20|min:8|unique:accounts',
            'email' => 'required|email|max:50|unique:accounts',
            'password' => 'required|min:8|max:50',
            'password_confirmation' => 'required|same:password',
            'fullname' => 'required|string|max:255',
            'avatar_url'=>'required|string',
            'phone_number' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'required|string|max:255',
            'address_line3' => 'required|string|max:255',
            'address_line4' => 'required|string|max:255',
        ];
    }
}
