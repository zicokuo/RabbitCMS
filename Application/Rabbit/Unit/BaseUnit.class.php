<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/3
 * Time: 15:19
 */

namespace Rabbit\Unit;


class BaseUnit
{
    public $unit;
    public $model;

    protected $modelName;
    protected $modelStatus = false;


    function __set($key, $value)
    {
        $this->unit[$key] = $value;
    }

    function __get($key)
    {
        return $this->unit[$key];
    }

    /**
     * 记录数据库操作
     * @param $logs
     * @param $status
     */
    protected function record_log($log, $status)
    {
        $params['logs'] = $log;
        $params['value'] = $this->dump();
        $params['result'] = $status ? "success" : "error";
        $params['createTime'] = time();
        $params['author'] = S("USER_CUR")["nick"];
        $record = M("record");
        $record->add($params);
    }

    protected function get_logs($author)
    {
        $record = M("record");
        return $record->where("author ='%s'", $author)->limit("20")->order("createTime")->select();
    }

    function dump()
    {
        return $this->model->getLastSql();
    }
}