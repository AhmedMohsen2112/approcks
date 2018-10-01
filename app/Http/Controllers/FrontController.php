<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\Basic;
use Auth;
use App\Models\Setting;
use App\Models\SettingTranslation;
use App\Models\Category;
use App\Models\Location;

class FrontController extends Controller {

    use Basic;

    protected $lang_code;
    protected $User = false;
    protected $isUser = false;
    protected $_Request = false;
    protected $limit = 1;
    protected $settings;
    protected $data = array();

    public function __construct() {

        $this->init();
    }

    private function init() {
        //$this->middleware('https');
        $this->check_auth();
        $this->getLangCode();

       
    }

    private function getLangCode() {
        $this->lang_code = app()->getLocale();
        $this->data['lang_code'] = $this->lang_code;
        session()->put('lang_code', $this->lang_code);
        if ($this->data['lang_code'] == 'ar') {
            $this->data['next_lang_code'] = 'en';
            $this->data['next_lang_text'] = 'English';
        } else {
            $this->data['next_lang_code'] = 'ar';
            $this->data['next_lang_text'] = 'العربية';
        }
        $this->slugsCreate();
    }

    private function check_auth() {
        if (Auth::guard('web')->user() != null) {
            $this->User = Auth::guard('web')->user();
            $this->isUser = true;
        }
        $this->data['User'] = $this->User;
        $this->data['isUser'] = $this->isUser;
    }


    protected function _view($main_content, $type = 'front') {
        $main_content = "main_content/$type/$main_content";
        return view($main_content, $this->data);
    }

    protected function err404() {
   
        return $this->_view('errors.404');
    }
    protected function err403() {
        
        return $this->_view('errors.403');
    }

}
