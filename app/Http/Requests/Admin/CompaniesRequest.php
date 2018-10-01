<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CompaniesRequest extends FormRequest
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
    public function rules(Request $request)
    {
 
        $rules = [
            'name' => 'required',
            'website' => 'required',
            'email' => 'required|email|unique:companies,email',
            'image' => 'required|image:jpg,png|max:1000|dimensions:width=100,height=100',
        ];
        if($id = $request->segment(3)){
            $rules['email']= 'required|email|unique:companies,email,' . $id;
            $rules['image']= 'image:jpg,png|max:1000|dimensions:width=100,height=100';
        }

        return $rules;
    }
}
