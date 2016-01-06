<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/11/30
 * Time: 19:41
 */

namespace Rabbit\Model;

use Think\Model;

class UserModel extends Model
{

    protected $tableName = "user";

    public function __construct()
    {
        parent::__construct();
    }



}