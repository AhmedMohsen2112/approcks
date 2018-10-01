<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\ModelTrait;
use DB;

class User extends Authenticatable {

    use Notifiable;
    use ModelTrait;

    protected $casts = array(
        'id' => 'integer',
        'mobile' => 'string',
    );
    public static $sizes = array(
        's' => array('width' => 120, 'height' => 120),
        'm' => array('width' => 400, 'height' => 400),
    );
    public static $client_permissions = array(
        'orders' => array('add', 'edit', 'view','pending', 'closed', 'open_order'),
    );
    public static $supervisor_permissions = array(
        'permissions' => array('open'),
        'orders' => array('view','pending', 'closed','open_order','respond'),
    );

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function request_types() {
        return $this->belongsToMany(BasicData::class, 'supervisors_request_types', 'supervisor_id', 'request_type_id');
    }

    public function system_types() {
        return $this->belongsToMany(BasicData::class, 'supervisors_system_types', 'supervisor_id', 'system_type_id');
    }

    public static function getOne($id) {
        $one = User::join('employees', 'employees.id', '=', 'users.employee_id')
                ->join('basic_data as work_types', 'work_types.id', '=', 'users.work_type_id')
                ->join('basic_data as departments', 'departments.id', '=', 'users.department_id')
                ->join('basic_data as categories', 'categories.id', '=', 'users.category_id')
                ->join('basic_data_translations as work_types_translations', 'work_types.id', '=', 'work_types_translations.basic_data_id')
                ->join('basic_data_translations as departments_translations', 'departments.id', '=', 'departments_translations.basic_data_id')
                ->join('basic_data_translations as categories_translations', 'categories.id', '=', 'categories_translations.basic_data_id')
                ->where('work_types_translations.locale', static::getLangCode())
                ->where('departments_translations.locale', static::getLangCode())
                ->where('categories_translations.locale', static::getLangCode())
                ->where('users.id', $id)
                ->select(['users.id', 'users.active','users.birthdate', 'users.image', 'employees.name', 'employees.ssn', 'work_types_translations.title as work_type_title',
            'departments_translations.title as department_title', 'categories_translations.title as category_title', 'users.mobile', 'users.email',
            'work_types.id as work_type_id', 'departments.id as department_id', 'categories.id as category_id']);
        $one = $one->first();
        //dd();
        if ($one) {
            $one = static::transform($one);
        }
        return $one;
    }

    public static function transform($item) {

        $item->image = url('public/uploads/users') . '/' . $item->image;
        return $item;
    }

    protected static function boot() {
        parent::boot();

        static::deleted(function($user) {
            if ($user->user_image != 'default.png') {
                User::deleteUploaded('users', $user->image);
            }
        });
    }

}
