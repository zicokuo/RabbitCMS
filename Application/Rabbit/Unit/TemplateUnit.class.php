<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/30
 * Time: 17:00
 */

namespace Rabbit\Unit;

use Rabbit\Unit\BaseUnit;

/**
 * 模板单元
 * Class TemplateUnit
 * @package Diavision\Unit
 */
class TemplateUnit extends BaseUnit
{
    public $unit;
    public $model;
    public $page;

    function __construct()
    {
        $this->model = D("Template");
    }

    public function get_template($params)
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


}