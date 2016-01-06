<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/1
 * Time: 4:22
 */

namespace Rabbit\Unit;

use Diavision\Unit\BaseUnit;
use Think\Crypt\Driver\Think;

class PostUnit extends BaseUnit
{
    public $unit;
    public $model;

    const max_select = 1000;

    public $post, $postNum, $page;

    public function __construct()
    {
        $this->model = D('Post');
    }

    public function get_post($params = array())
    {
        if (is_array($params)) {
            $params['status'] = isset($params['status']) ? $params['status'] : "0";
            $this->postNum = $this->model->where($params)->count();
            if (isset($params['hasPage'])) {
                $this->page = new \Diavision\Unit\PageUnit($this->postNum, C('INQUIRY.limit'));
                $list = $this->model->where($params)->order('updateTime desc')->limit($this->page->firstRow . ',' . $this->page->listRows)->select();
            } else {
                $list = $this->model->where($params)->order('updateTime desc')->limit(C('INQUIRY.limit'))->select();
            }
            return $list;
        } else {
            $this->model->where("id = %s", $params);
            return $this->model->find();
        }
    }

    public function get_all_post()
    {

    }

    public function insert($params)
    {
        $time = date("h:i:s", time() - strtotime(date("Y-m-d")));
        $params['postDate'] .=" ". $time;
        $params['createTime'] = $params['postDate'] ? date_to_time($params['postDate']) : time();
        $params['postUpdateDate'] = $params['createTime'];
        $userCur = S("USER_CUR");
        $params['postAuthor'] = $params['postAuthor']?$params['postAuthor']:$userCur['nick'];
        $this->model->create($params);
        return $this->model->add();
    }

    public function update($params)
    {
        $time = date("h:i:s", time() - strtotime(date("Y-m-d")));
        $params['postDate'] .=" ". $time;
        $params['postUpdateDate'] = $params['postDate'] ? date_to_time($params['postDate']) : time();
        $userCur = S("USER_CUR");
        $params['postAuthor'] = $params['postAuthor']?$params['postAuthor']:$userCur['nick'];
        $this->model->create($params);
        return $this->model->where("id=" . $params['postId'])->save();
    }

    public function del_post($id)
    {
        return $this->model->where("id=%s", $id)->delete();
    }

}