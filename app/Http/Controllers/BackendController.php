<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use App\Models\Pages;
use App\Traits\Basic;
use App\Traits\Rate;
use Auth;
use Image;
use Config;
class BackendController extends Controller {

    use Basic;
    use Rate;

    protected $lang_code = 'en';
    protected $User;
    protected $data = array();

    public function __construct() {
        $this->middleware('auth:admin');
     
        $segment2 = \Request::segment(2);
        $this->data['page_link_name'] = $segment2;
        $this->User = Auth::guard('admin')->user();
        $this->data['User'] = $this->User;
        $this->data['languages'] = $this->languages;
        $this->getCookieLangAndSetLocale();
        $this->slugsCreate();
    }

    protected function getCookieLangAndSetLocale() {
        if (\Cookie::get('AdminLang') !== null) {
            try {
                $this->lang_code = \Crypt::decrypt(\Cookie::get('AdminLang'));
            } catch (DecryptException $ex) {
                $this->lang_code = 'en';
            }
        } else {
            $this->lang_code = 'en';
        }

        $this->data['lang_code'] = $this->lang_code;
        if ($this->lang_code == "ar") {
            $this->data['currency_sign'] = 'ريال';
        } else {
            $this->data['currency_sign'] = 'SAR';
        }

        app()->setLocale($this->lang_code);
    }


    public function err404() {
        return view('main_content/backend/err404');
    }

}
