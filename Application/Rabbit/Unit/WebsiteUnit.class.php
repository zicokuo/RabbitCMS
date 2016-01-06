<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-01-06
 * Time: 19:46
 */

namespace Rabbit\Unit;

use \Rabbit\Unit\BaseUnit;

class WebsiteUnit extends BaseUnit
{

    private $_model = null;

    function __construct()
    {
    }

    private function model()
    {
        if (is_null($this->_model)) {
            $this->_model = D("Website");
        }
        return $this->_model;
    }

    public function get_website_options($params = null)
    {
        $params['group'] = "information";
        return $this->model()->where($params)->select();
    }
}