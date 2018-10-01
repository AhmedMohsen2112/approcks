<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends MyModel {

    protected $table = "orders";

    public function reply() {
        return $this->hasMany(OrderReply::class, 'order_id')->select('orders_reply.reply', 'orders_reply.user_id');
    }

    public static function getAllFront($where_array = array()) {
        $orders = static::join('users as clients', 'clients.id', '=', 'orders.client_id');
        $orders->join('employees', 'employees.id', '=', 'clients.employee_id');
        $orders->leftJoin('users as supervisors', 'supervisors.id', '=', 'orders.supervisor_id');
        $orders->leftJoin('basic_data as request_types', 'request_types.id', '=', 'orders.request_type_id');
        $orders->join('basic_data as system_types', 'system_types.id', '=', 'orders.system_type_id');
        $orders->leftJoin('basic_data_translations as request_types_translations', function ($join) {
            $join->on('request_types.id', '=', 'request_types_translations.basic_data_id');
            $join->where('request_types_translations.locale', static::getLangCode());
        });
       
        $orders->join('basic_data_translations as system_types_translations', 'system_types.id', '=', 'system_types_translations.basic_data_id');
        $orders->join('basic_data as work_types', 'work_types.id', '=', 'clients.work_type_id');
        $orders->join('basic_data as departments', 'departments.id', '=', 'clients.department_id');
        $orders->join('basic_data as categories', 'categories.id', '=', 'clients.category_id');
        $orders->join('basic_data_translations as work_types_translations', 'work_types.id', '=', 'work_types_translations.basic_data_id');
        $orders->join('basic_data_translations as departments_translations', 'departments.id', '=', 'departments_translations.basic_data_id');
        $orders->join('basic_data_translations as categories_translations', 'categories.id', '=', 'categories_translations.basic_data_id');
        $orders->where('work_types_translations.locale', static::getLangCode());
        $orders->where('departments_translations.locale', static::getLangCode());
        $orders->where('categories_translations.locale', static::getLangCode());
 
        $orders->where('system_types_translations.locale', static::getLangCode());
        $orders->select(['orders.id','orders.permissions', 'orders.details', 'orders.status', 'request_types_translations.title as request_type_title', 'system_types_translations.title as system_type_title',
            'orders.document', 'employees.name', 'employees.ssn', 'clients.email', 'clients.mobile', 'clients.birthdate', 'work_types_translations.title as work_type_title',
            'departments_translations.title as department_title', 'categories_translations.title as category_title', 'clients.id as client_id']);

        if (isset($where_array['id'])) {
            $orders->where('orders.id', $where_array['id']);
            $orders = $orders->first();
            if ($orders) {
                $orders = static::transformFrontDetails($orders);
            }
            return $orders;
        } else {


            if (isset($where_array['status'])) {

                if ($where_array['status'] == 1) {
                    $orders->where('orders.status', $where_array['status']);
                    if ($where_array['user']->type == 1) {
                        $orders->where('orders.client_id', $where_array['user']->id);
                    } else {
                        $orders->where('orders.supervisor_id', $where_array['user']->id);
                    }
                } else {
                    if ($where_array['user']->type == 1) {
                        $orders->where('orders.status', $where_array['status']);
                        $orders->where('orders.client_id', $where_array['user']->id);
                    } else {
                         $user_id = $where_array['user']->id;
                         $status= $where_array['status'];
                        $orders->where(function ($query) use($user_id,$status) {
                           
                            $query->where('orders.status', $status);
                            $query->whereNull('orders.supervisor_id');
                            $query->whereRaw("request_types.id in (select request_type_id from supervisors_request_types where supervisor_id=$user_id)");
                            $query->whereRaw("system_types.id in (select system_type_id from supervisors_system_types where supervisor_id=$user_id)");
                        });
                        $orders->orWhere(function ($query) use($user_id,$status) {
                            $query->where('orders.status', $status);
                            $query->where('orders.supervisor_id', $user_id);
                        });

//                        $user_id = $where_array['user']->id;
//                        $orders->whereRaw("CASE WHEN orders.supervisor_id IS NULL THEN orders.supervisor_id IS NULL ELSE orders.supervisor_id = $user_id END");
                    }
                }
            }
            $orders->orderBy('orders.created_at', 'DESC');
            $orders->groupBy('orders.id');
            $orders = $orders->paginate(static::$limit);
            $orders->getCollection()->transform(function($order) use($where_array) {
                return static::transformFrontPagination($order, ['user' => $where_array['user']]);
            });

            return $orders;
        }
    }

    public static function getAllAdmin($where_array = array()) {
        $orders = static::join('users as clients', 'clients.id', '=', 'orders.client_id');
        $orders->join('employees', 'employees.id', '=', 'clients.employee_id');
        $orders->join('basic_data as request_types', 'request_types.id', '=', 'orders.request_type_id');
        $orders->join('basic_data as system_types', 'system_types.id', '=', 'orders.system_type_id');
        $orders->join('basic_data_translations as request_types_translations', 'request_types.id', '=', 'request_types_translations.basic_data_id');
        $orders->join('basic_data_translations as system_types_translations', 'system_types.id', '=', 'system_types_translations.basic_data_id');
        $orders->where('request_types_translations.locale', static::getLangCode());
        $orders->where('system_types_translations.locale', static::getLangCode());
        $orders->select(['orders.id', 'orders.created_at', 'orders.status', 'request_types_translations.title as request_type_title', 'system_types_translations.title as system_type_title',
            'employees.name', 'employees.ssn', 'clients.email', 'clients.mobile', 'clients.id as client_id']);

        if (isset($where_array['status'])) {
            $orders->where('orders.status', $where_array['status'] - 1);
        }
        if (isset($where_array['from'])) {
            $from = $where_array['from'];
            $orders->where("orders.date", ">=", "$from");
        }
        if (isset($where_array['to'])) {
            $to = $where_array['to'];
            $orders->where("orders.date", "<=", "$to");
        }
        $orders->orderBy('orders.created_at', 'DESC');
        $orders = $orders->groupBy('orders.id');

        return $orders;
    }

    public static function getOneAdmin($id) {
        $orders = static::join('users as clients', 'clients.id', '=', 'orders.client_id');
        $orders->join('employees as emp1', 'emp1.id', '=', 'clients.employee_id');
        $orders->leftJoin('users as supervisors', 'supervisors.id', '=', 'orders.supervisor_id');
        $orders->join('employees as emp2', 'emp2.id', '=', 'supervisors.employee_id');
        $orders->join('basic_data as request_types', 'request_types.id', '=', 'orders.request_type_id');
        $orders->join('basic_data as system_types', 'system_types.id', '=', 'orders.system_type_id');
        $orders->join('basic_data_translations as request_types_translations', 'request_types.id', '=', 'request_types_translations.basic_data_id');
        $orders->join('basic_data_translations as system_types_translations', 'system_types.id', '=', 'system_types_translations.basic_data_id');
        $orders->join('basic_data as work_types', 'work_types.id', '=', 'clients.work_type_id');
        $orders->join('basic_data as departments', 'departments.id', '=', 'clients.department_id');
        $orders->join('basic_data as categories', 'categories.id', '=', 'clients.category_id');
        $orders->join('basic_data_translations as work_types_translations', 'work_types.id', '=', 'work_types_translations.basic_data_id');
        $orders->join('basic_data_translations as departments_translations', 'departments.id', '=', 'departments_translations.basic_data_id');
        $orders->join('basic_data_translations as categories_translations', 'categories.id', '=', 'categories_translations.basic_data_id');
        $orders->where('work_types_translations.locale', static::getLangCode());
        $orders->where('departments_translations.locale', static::getLangCode());
        $orders->where('categories_translations.locale', static::getLangCode());
        $orders->where('request_types_translations.locale', static::getLangCode());
        $orders->where('system_types_translations.locale', static::getLangCode());
        $orders->select(['orders.id', 'orders.details', 'orders.status', 'request_types_translations.title as request_type_title', 'system_types_translations.title as system_type_title',
            'orders.document', 'emp1.name  as client_name', 'emp1.ssn as client_ssn', 'clients.id as client_id', 'emp2.name  as supervisor_name', 'emp2.ssn as supervisor_ssn', 'orders.created_at']);

        $orders->where('orders.id', $id);
        $orders = $orders->first();
        if ($orders) {
            $orders = static::transformAdminDetails($orders);
        }
        return $orders;
    }

    public static function getAllRespondToOrdersFront($where_array = array()) {
        $orders = static::join('orders_reply', 'orders.id', '=', 'orders_reply.order_id');
        $orders->select(['orders.id', 'orders.details', 'orders_reply.reply']);
        $orders->where('orders.supervisor_id', $where_array['user']->id);
        $orders->where('orders_reply.user_id', '!=', $where_array['user']->id);
        $orders->orderBy('orders_reply.created_at', 'DESC');
        $orders = $orders->paginate(static::$limit);
        $orders->getCollection()->transform(function($order) use($where_array) {
            return static::transformFrontRespondToOrdersPagination($order, ['user' => $where_array['user']]);
        });

        return $orders;
    }

    public static function transformFrontPagination($item, $extra_params) {
        $hash_id_encode = hashIdEncode($item->id);
        $item->id = $item->id;
        $item->url = customer_url('orders/' . $hash_id_encode);
        $item->open_url = customer_url('orders/' . $hash_id_encode . '/open');
        $item->edit_url = customer_url('orders/' . $hash_id_encode . '/edit');
        $item->details = str_limit($item->details, 100, '...');
        $reply = $item->reply()->where('orders_reply.user_id', $extra_params['user']->id)->get();
        $item->open = true;
        if ($extra_params['user']->type == 2 && $reply->count() == 2) {
            $item->open = false;
        }
        if ($extra_params['user']->type == 1 && $reply->count() == 1) {
            $item->open = false;
        }
        return $item;
    }

    public static function transformFrontDetails($item) {
        $item->reply = $item->reply;
        return $item;
    }

    public static function transformAdminDetails($item) {
        $item->reply = $item->reply;
        return $item;
    }

    public static function transformFrontRespondToOrdersPagination($item) {
        $item->details = str_limit($item->details, 150, '...');
        $item->reply = str_limit($item->reply, 150, '...');
        $hash_id_encode = hashIdEncode($item->id);
        $item->url = customer_url('orders/' . $hash_id_encode);
        return $item;
    }

}
