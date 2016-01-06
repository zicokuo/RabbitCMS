<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/30
 * Time: 17:12
 */

namespace Rabbit\Model;

use Think\Model;

class TemplateModel extends Model
{
    protected $tableName = "template";

    function __construct()
    {
        parent::__construct();
    }


}