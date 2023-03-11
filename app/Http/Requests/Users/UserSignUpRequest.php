<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserSignUpRequest extends FormRequest
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
        if ($this->request->has('email'))  {
            return [
                'email' => 'required|email|unique:users,email',
            ];
        } else {
            return [
            //     'first_name' => [
            //         'required',
            //         'regex:/^[a-zA-Z ]*$/'
            //     ],
            //     'last_name' =>  [
            //         'required',
            //         'regex:/^[a-zA-Z ]*$/'
            //     ],
                'password' => 'required',
                'confirm_password' => 'required|same:password'
            ];

        }
    }
}
