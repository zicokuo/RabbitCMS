<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-01-06
 * Time: 19:48
 */

namespace Rabbit\Model;

use Think\Model;

class Website extends Model
{
    protected $tableName = "website";

    public function __construct()
    {
        parent::__construct();
    }
}