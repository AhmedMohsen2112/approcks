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

        Route::get('/', 'HomeController@index')->name('home');

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

    Route::resource('groups', 'GroupsController');
    Route::post('groups/data', 'GroupsController@data');

    Route::resource('admins', 'AdminsController');
    Route::post('admins/data', 'AdminsController@data');

    Route::resource('companies', 'CompaniesController');
    Route::post('companies/data', 'CompaniesController@data');

    Route::resource('packages', 'PackagesController');
    Route::post('packages/data', 'PackagesController@data');

    Route::resource('slider', 'SliderController');
    Route::post('slider/data', 'SliderController@data');

    Route::resource('currency', 'CurrencyController');
    Route::post('currency/data', 'CurrencyController@data');

    Route::resource('categories', 'CategoriesController');
    Route::post('categories/data', 'CategoriesController@data');

    Route::resource('request_types', 'RequestTypesController');
    Route::post('request_types/data', 'RequestTypesController@data');
    Route::get('request_types/permissions/{id}', 'RequestTypesController@permissions');
    Route::resource('work_types', 'WorkTypesController');
    Route::post('work_types/data', 'WorkTypesController@data');
    Route::resource('departments', 'DepartmentsController');
    Route::post('departments/data', 'DepartmentsController@data');
    Route::resource('system_types', 'SystemTypesController');
    Route::post('system_types/data', 'SystemTypesController@data');
    Route::resource('permissions', 'PermissionsController');
    Route::post('permissions/data', 'PermissionsController@data');
    Route::resource('supervisors', 'SupervisorsController');
    Route::post('supervisors/data', 'SupervisorsController@data');
    Route::resource('employees', 'EmployeesController');
    Route::post('employees/data', 'EmployeesController@data');
    




    Route::resource('users', 'UsersController');
    Route::post('users/data', 'UsersController@data');
    Route::get('users/status/{id}', 'UsersController@status');


    Route::resource('orders', 'OrdersController');
    Route::post('orders/data', 'OrdersController@data');






    Route::resource('orders_reports', 'OrdersReportsController');

    Route::post('settings', 'SettingsController@store');
    Route::get('notifications', 'NotificationsController@index');
    Route::post('notifications', 'NotificationsController@store');



    Route::get('settings', 'SettingsController@index');


    Route::resource('contact_messages', 'ContactMessagesController');
    Route::post('contact_messages/data', 'ContactMessagesController@data');
    Route::post('contact_messages/reply', 'ContactMessagesController@reply');

    Route::resource('ad_reports', 'AdReportsController');
    Route::post('ad_reports/data', 'AdReportsController@data');

    Route::resource('user_packages', 'UserPackagesController');
    Route::post('user_packages/data', 'UserPackagesController@data');
    Route::get('user_packages/status/{id}', 'UserPackagesController@status');

    Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'LoginController@login')->name('admin.login.submit');
    Route::get('logout', 'LoginController@logout')->name('admin.logout');
});
//});

