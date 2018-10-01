<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\BackendController;
use App\Models\Admin;
use App\Http\Requests\Admin\ProfileRequest;

class ProfileController extends BackendController {

    public function index() {
        return $this->_view('profile/index', 'backend');
    }

    public function update(ProfileRequest $request) {



        try {
            $this->User->username = $request->input('username');
            $this->User->email = $request->input('email');
            if ($request->input('password')) {
                $this->User->password = bcrypt($request->input('password'));
            }
            $this->User->save();
            return _json('success', _lang('app.updated_successfully'));
        } catch (\Exception $ex) {
            return _json('error', _lang('app.error_is_occured'));
        }
    }

}
