<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProfileRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request) {
        $id = $request->input('id');
        //dd($id);
        $rules = [
            'username' => 'required|unique:admins,username,' . $id,
            'email' => 'required|email|unique:admins,email,' . $id,
        ];

        return $rules;
    }

}
