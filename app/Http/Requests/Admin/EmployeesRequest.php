<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EmployeesRequest extends FormRequest
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
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|unique:employees,phone',
            'company' => 'required',
        ];
        if($id = $request->segment(3)){
            $rules['email']= 'required|email|unique:employees,email,' . $id;
            $rules['phone']= 'required|unique:employees,phone,' . $id;
        }

        return $rules;
    }
}
