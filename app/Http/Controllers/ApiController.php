<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\AUTHORIZATION;
use App\Models\User;
use App\Models\Friendship;
use App\Models\UserBlockPost;
use App\Models\AdminNotification;
use App\Models\Order;
use App\Models\Notification;
use App\Models\Setting;
use App\Traits\Basic;
use App\Traits\Rate;
use App\Models\Category;
use Request;

class ApiController extends Controller {

    use Basic;
    use Rate;

    protected $lang_code;
    protected $User;
    protected $data;
    protected $limit = 10;
    protected $expire_no = 1;
    protected $expire_type = 'day';

    public function __construct() {
        $this->getLangAndSetLocale();
        $this->slugsCreate();
    }

  

    private function getLangAndSetLocale() {
        $languages = array('ar', 'en','ur');
        $lang = Request::header('lang');
        if ($lang == null || !in_array($lang, $languages)) {
            $lang = 'ar';
        }

        $this->lang_code = $lang;
        app()->setLocale($lang);
    }


    private function slugsCreate() {

        $this->title_slug = 'title_' . $this->lang_code;
        $this->data['title_slug'] = $this->title_slug;
    }
 
    protected function auth_user() {
        $token = Request::header('authorization');
  
        $token = Authorization::validateToken($token);
        $user = null;
        if ($token) {
            $user = User::find($token->id);
        }

        return $user;
    }

 

   

}
