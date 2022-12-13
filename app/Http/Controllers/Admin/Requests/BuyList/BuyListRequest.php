<?php

namespace \App\Http\Admin\Requests\BuyList;

use Illuminate\Foundation\Http\FormRequest;

class BuyListRequest extends FormRequest
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
                'title' => 'required',
                'status' =>  'required',
                'description' => 'required|regex:/^[a-zA-Z ]*$/',
            ];

        }
    }
}
