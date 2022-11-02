<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserAddressRequest extends FormRequest
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
            'first_name' => [
                'required',
                'regex:/^[a-zA-Z ]*$/'
            ],
            'last_name' =>  [
                'required',
                'regex:/^[a-zA-Z ]*$/'
            ],
            'company_name' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'address' => [
                'required',
                'regex:/^[a-zA-Z0-9\s-]+$/'
            ],
            'address2' => [
                'required',
                'regex:/^[a-zA-Z0-9\s-]+$/'
            ],
            'town_city'=> 'required|alpha',
            'state' => 'required|alpha',
            'zip' => [
                'required|8',
                'regex:/^[0-9-]+$/'
            ],
            'phone' => 'required|digits:8'
        ];
       
    }
}
