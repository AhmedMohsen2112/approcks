<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */


$languages = array('ar', 'en', 'fr');
$defaultLanguage = 'ar';
if ($defaultLanguage) {
    $defaultLanguageCode = $defaultLanguage;
} else {
    $defaultLanguageCode = 'ar';
}

$currentLanguageCode = Request::segment(1, $defaultLanguageCode);

if (in_array($currentLanguageCode, $languages)) {
    Route::get('/', function () use($defaultLanguageCode) {
        return redirect()->to($defaultLanguageCode);
    });


    Route::group(['namespace' => 'Front', 'prefix' => $currentLanguageCode], function () use($currentLanguageCode) {
        app()->setLocale($currentLanguageCode);

        Route::get('/',function(){
            dd('test');
        });

        Auth::routes();
        /*         * ************************* ajax ************** */
        Route::group(['prefix' => 'ajax'], function () {
 
        });





     
//         Route::get('ads/ID-{any}', 'PropertyController@getAd')->name('getAd');
//        Route::get('{any}','PropertyController@index')->where('any', '.*');
    });
} else {
    Route::get('/' . $currentLanguageCode, function () use($defaultLanguageCode) {
        return redirect()->to($defaultLanguageCode);
    });
}


//Route::group(['middleware'=>'auth:admin'], function () {
Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {

    Route::get('/', 'AdminController@index')->name('admin.dashboard');
    Route::get('/error', 'AdminController@error')->name('admin.error');
    Route::get('/change_lang', 'AjaxController@change_lang')->name('ajax.change_lang');

    Route::get('profile', 'ProfileController@index');
    Route::patch('profile', 'ProfileController@update');

 

    Route::resource('companies', 'CompaniesController');
    Route::post('companies/data', 'CompaniesController@data');


   
    Route::resource('employees', 'EmployeesController');
    Route::post('employees/data', 'EmployeesController@data');
    




   



 

    Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'LoginController@login')->name('admin.login.submit');
    Route::get('logout', 'LoginController@logout')->name('admin.logout');
});
//});

