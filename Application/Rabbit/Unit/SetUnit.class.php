<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/22
 * Time: 16:31
 */

namespace Rabbit\Unit;

use Rabbit\Unit\BaseUnit;

class SetUnit extends BaseUnit
{
    public $unit;
    public $model;

    const setCacheName = "SETTINGS";

    public function __construct()
    {
        $this->model = D("Set");

        $setCache = $this->get_settings_cache();
        if (!$setCache) {
            $this->set_settings_cache($setCache);
        }
    }

    function get_settings_cache()
    {
        return S(self::setCacheName);
    }

    function set_settings_cache($params = null)
    {
        $settings = $params ? $params:$this->get_settings();
        S(self::setCacheName, $settings, C("CACHE_EXPIRED"));
    }

    public function get_settings($params = null)
    {
        if (is_array($params)) {
            $this->where($params);
        }
        $res =  $this->model->order(array('group'))->select();
        $this->record_log("系统初始化查询配置项",$res);
        return $res;
    }

    public function get_setting($name)
    {
        $args['alias'] = array("eq", $name);
        $set = $this->get_settings($args);
        return $set[0];
    }

    public function update()
    {
        $name = I("post.name");
        $value = I("post.value");
        $args['value'] = $value;
        $res =  $this->model->where("alias='%s'", $name)->setField($args);
        $this->record_log("更新系统配置",$res);
        return $res;
    }
}