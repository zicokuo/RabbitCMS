<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2016/1/5
 * Time: 22:13
 */

namespace Rabbit\Model;

use \Think\Model;

class RecordModel extends Model
{
    protected $tableName = "record";

    function __construct()
    {
        parent::__construct();
    }


}