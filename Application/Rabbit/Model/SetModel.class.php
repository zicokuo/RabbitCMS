<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/11/30
 * Time: 19:41
 */

namespace Rabbit\Model;

use Think\Model;
class SetModel extends Model
{

    protected $tableName = "setting";

    public function __construct(){
        parent::__construct();
    }



}