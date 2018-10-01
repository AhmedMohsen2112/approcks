<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;


class AjaxController extends BackendController {

    public function change_lang(Request $request) {
        //dd('here');
        $lang_code = $request->input('lang_code');
        //dd($lang_code);
        $long = 7 * 60 * 24;
        return response()->json([
                    'type' => 'success',
                    'message' => $lang_code
                ])->cookie('AdminLang', $lang_code, $long);
    }

}
