<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Company extends MyModel
{
    protected $table = "companies";
      public static $sizes = array(
        's' => array('width' => 100, 'height' => 100),
        'm' => array('width' => 400, 'height' => 400),
    );
      
      public static function getAllAdmin() {
        $data = static::select(['id', 'name'])->get();
    
        return $data;
    }
      public static function getOneAdmin($id) {
        $one = static::select(['id', 'name','website','email' ,'logo'])->where('id',$id);
        $one = $one->first();
        //dd();
        if ($one) {
            $one = static::transform($one);
        }
        return $one;
    }

    public static function transform($item) {

        $item->logo = url('public/uploads/companies') . '/' . $item->logo;
        return $item;
    }
    
   protected static function boot() {
        parent::boot();

        static::deleted(function($one) {
            static::deleteUploaded('companies', $one->logo);
        });
    }
}
