<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class CompanyInfoRequest extends FormRequest
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
        if ($this->request->has('company_name') || $this->request->has('company_website') || $this->request->get('phone') || ($this->request->all()) == 0) {
            return [
                'company_name' => [
                    'required',
                    'regex:/^[a-zA-Z0-9\s]+$/'
                ],
                // 'company_website' => [
                //     'required',
                //     'regex:/(?:https?:\/\/)?(?:[a-zA-Z0-9.-]+?\.(?:[a-zA-Z])|\d+\.\d+\.\d+\.\d+)/'
                // ],
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
            ];
        } else {
            return [
                'street_address' => [
                    'required',
                    'regex:/^[a-zA-Z0-9\s-]+$/'
                ],
                // 'suit_apartment' => [
                //     'required',
                //     'regex:/^[a-zA-Z0-9\s-]+$/'
                // ],
                'state_id' => 'required',
                'city_id' => 'required',
                'zip' => [
                    'required',
                    'regex:/^\d{5}(?:[- ]?\d{4})?$/s'
                ],
            ];
        }
    }
}