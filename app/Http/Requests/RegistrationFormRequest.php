<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationFormRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:6|max:10',
            'role_id' => 'required|integer|min:1|max:6', // nuevo
            'branch_id' => 'required|integer|min:1|max:6', // nuevo
            'status' => 'required|string|min:1|max:10' // nuevo
        ];
    }
}
