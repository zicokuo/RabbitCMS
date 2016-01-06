<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/3
 * Time: 15:17
 */

namespace Rabbit\Unit;

use Rabbit\Unit\BaseUnit;

class CateUnit extends BaseUnit
{
    public $unit;
    public $model;

    const cateCacheName = "CATES_ALL";

    function __construct()
    {
        $this->model = D("Cate");
        $cateCache = $this->get_cate_cache();
        if (!$cateCache) {
            $this->set_cate_cache();
        }
    }

    function get_cate_cache()
    {
        return S($this::cateCacheName);
    }

    function set_cate_cache($params = null)
    {
        if (is_null($params)) {
            $cateCache = $this->get_cates_all();
        } else {
            $cateCache = $params;
        }
        S($this::cateCacheName, $cateCache, C("CACHE_EXPIRED"));
    }

    public function get_cate($id)
    {
        return $this->model->find($id);
    }

    public function get_cates($params = array())
    {
        return $this->model->where($params)->order("parent")->select();
    }

    public function get_cates_all()
    {
        $cates = $this->model->order("id")->select();
        foreach ($cates as $key => $cate) {
            $catesAll[$cate['id']] = $cate;
        }
        return $catesAll;
    }

    public function insert($args)
    {
        if ($args['cateParent'] > 0) {
            $cateParent = D('Cate');
            $pcate = $cateParent->find($args['cateParent']);
            $args['level'] = $pcate['level'] + 1;
        }
        if (empty($args['cateAlias'])) {
            $args['cateAlias'] = time();
        }
        $this->model->create($args);
        $res = $this->model->add();
        $this->record_log($this->dump(),$res);
        $this->set_cate_cache();
        return $res;
    }

    public function update($args)
    {
        if ($args['cateParent'] > 0) {
            $cateParent = D('Cate');
            $pcate = $cateParent->find($args['cateParent']);
            $args['level'] = $pcate['level'] + 1;
        }
        if (empty($args['cateAlias'])) {
            $args['cateAlias'] = time();
        }
        $this->model->create($args);
        $res = $this->model->where("id=" . $args['cateId'])->save();
        $this->set_cate_cache();
        return $res;
    }

    /**
     * 获取全部栏目
     * @return mixed
     */
    public function get_cates_sort()
    {
        $cates = $this->model->select();
        foreach ($cates as $cate) {
            $newCates[$cate['id']] = $cate;
        }
        return $newCates;
    }

    /**
     * 获取全部栏目(按结构排序)
     * @param int $point 节点,默认为0,则以根目录开始
     * @return array
     */
    public function get_cates_tree($cates)
    {
//        $cates = $this->model->order("parent")->select();
        $cateTree = array();
        $this->_sort_cate($cates, $cateTree);
        return $cateTree;
    }

    /**
     * 私有方法 - 栏目排序(无限级结构)
     * @param $datas 元数据
     * @param $newDatas 处理后的数据
     * @param int $point 节点
     * @param int $level 层数
     */
    private function _sort_cate(&$datas, &$newDatas, $point = 0, $level = 0)
    {
        $level++;
        foreach ($datas as $key => $data) {
            if ($data['parent'] == $point) {
                $data['level'] = $level;
                $newDatas[] = $data;
                unset($datas[$key]);
                $this->_sort_cate($datas, $newDatas, $data['id'], $level);
            }
        }
    }

    public function del($id)
    {
        $postUnit = new \Diavision\Unit\PostUnit();
        $params = array("category" => $id);
        $posts = $postUnit->get_post($params);
        if (is_array($posts) && empty($posts)) {
            $res = $this->model->delete($id);
            if ($res) {
                $this->set_cate_cache();
            }
            return $res;
        } else {
            return false;
        }
    }
}