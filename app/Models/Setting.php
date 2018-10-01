<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends MyModel {

    protected $table = 'settings';
    protected $fillable = ['name', 'value'];
    public static $sizes = array(
        's' => array('width' => 120, 'height' => 120),
        'm' => array('width' => 400, 'height' => 400),
    );

    public static function getAll() {
        $settings = static::get()->keyBy('name');
        $new = array();
        if ($settings->count() > 0) {
            foreach ($settings as $one) {
                if ($one->name == 'copyright' || $one->name == 'address') {
                    $value = json_decode($one->value);
                    $one->value = $value->{static::getLangCode()};
                }
                if ($one->name == 'social_media') {
                    $one->value = json_decode($one->value);
                }
                if ($one->name == 'other_site_image'||$one->name == 'vision_image') {
                    $one->value = url('public/uploads/settings') . '/m_' . static::rmv_prefix($settings[$one->name]->value);
                }
                $new[$one->name] = $one->value;
            }
        }
       
        return $new;
    }

    public static function transform($item) {


        return $item;
    }

}
