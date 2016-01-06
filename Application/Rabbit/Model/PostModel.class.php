<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/1
 * Time: 4:15
 */

namespace Rabbit\Model;

use Think\Model;

class PostModel extends Model
{
    protected $tableName = "post";

    protected $_map = array(
        "PostThumbUrl" => "thumb",
        "postType" => "type",
        "postCate" => "category",
        "postTitle" => "title",
        "postContent" => "content",
        "postSummary" => "summary",
        "postId"=>"id",
        "postUpdateDate"=>"updateTime",
        "postAuthor" =>"author",
    );

    public function __construct()
    {
        parent::__construct();
    }

}