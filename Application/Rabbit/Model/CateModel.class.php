<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/5
 * Time: 14:14
 */

namespace Rabbit\Model;

use Think\Model;

class CateModel extends Model
{
    protected $tableName = "category";

    protected $_map = array(
        "cateName"=>"name",
        "search"=>"alias",
        "cateAlias"=>"alias",
        "cateParent"=>"parent",
        "cateThumbUrl"=>"thumb",
    );

    public function __construct()
    {
        parent::__construct();
    }
}