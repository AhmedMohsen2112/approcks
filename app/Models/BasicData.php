<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasicData extends MyModel {

    protected $table = "basic_data";
    public static $types = [
        1 => 'request_types',
        2 => 'work_types',
        3 => 'departments',
        4 => 'system_types',
        5 => 'categories',
        6 => 'permissions',
    ];

      public static function getAll($where_array=array()) {
        $data = static::join('basic_data_translations as trans', 'basic_data.id', '=', 'trans.basic_data_id')
                ->orderBy('basic_data.this_order', 'ASC')
                ->where('trans.locale', static::getLangCode())
                ->where('basic_data.active', true);
        if(isset($where_array['type'])){
            $data->where('basic_data.type', $where_array['type']);
        }
        if(isset($where_array['ids'])){
            $data->whereIn('basic_data.id', $where_array['ids']);
        }
        $data->select('basic_data.id','basic_data.permissions' ,'trans.title');
        $data = $data->get();

        return $data;
    }
    public function translations() {
        return $this->hasMany(BasicDataTranslation::class, 'basic_data_id');
    }

    public static function transform($item) {
        $transformer = new \stdClass();
        $transformer->id = $item->id;
        $transformer->title = $item->title;
        return $transformer;
    }

    protected static function boot() {
        parent::boot();

        static::deleting(function($bath) {
            foreach ($bath->translations as $translation) {
                $translation->delete();
            }
        });

        static::deleted(function($bath) {
            
        });
    }

}
