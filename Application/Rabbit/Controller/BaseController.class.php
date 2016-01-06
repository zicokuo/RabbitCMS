<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/5
 * Time: 19:39
 */

namespace Rabbit\Controller;

use Think\Controller;

/**
 * 基础控制类
 * Class BaseController
 * @package Diavision\Controller
 */
class BaseController extends Controller
{
    //控制器状态,主要用于中断自动处理流程
    protected $status = true;
    //控制器方法访问权限列表
    protected $accessList = array();
    //控制器内部功能单元
    private $units = array();

    /**
     * 基础控制器自动初始化
     * BaseController constructor.
     */
    function __construct()
    {
        //设置时区 - 北京时间
        date_default_timezone_set('Asia/beijing');

        //ThinkPHP控制器类初始化方法
        parent::__construct();
        $this->_valid();

        //重定义系统路径
        define("__VIEW__", __APP__ . "/View/");
        define("__PUBLIC__", __ROOT__ . "/Public/");
        define("__UPLOAD__", __ROOT__ . "/Uploads/");
    }

    /**
     * 系统标识与方法访问验证
     * 防止对Controller文件的单独访问
     */
    private function _valid()
    {
        //检验是否通过入口文件进行访问
        $this->status = defined(SYSTEM_ENTER);

        //ajax与免检列表的方法都省略安全认证
        if (IS_AJAX || in_array(ACTION_NAME, C('WITHOUT_CHECK'))) {
            return;
        }

        //如果控制器方法访问权限列表为空,则放行所有访问
        if (!empty($this->accessList)) {
            $this->status = $this->status && in_array(ACTION_NAME, $this->accessList);
        }

        //控制器状态中断
        if ($this->status) {
            $msg = "对不起,你的访问方式是非法的!";
            $this->error($msg, U("Admin/user_enter"));
        }
    }

    /**
     * 基础控制器自动加载对应功能单元,也是唯一的功能单元入口
     * @param $unitName 单元名(不包含Unit部分)
     * @param bool $new 是否强制建立新的单元实例
     * @return mixed 功能单元实例
     */
    protected function unit($unitName = null, $new = false)
    {
        $unitName = is_null($unitName) ? CONTROLLER_NAME : $unitName;
        $unit = ucfirst($unitName) . "Unit";
        $unit = '\\' . SYSTEM_SIGN . '\\Unit\\' . $unit;
        if ($new) {
            unset($this->units[$unitName]);
        }
        return isset($this->units[$unitName]) ? $this->units[$unitName] : new $unit;
    }

    /**
     * 后台渲染初始化
     * 为了子控制器类更好同一渲染后台,在父类中定义board_init
     */
    protected function board_init($params = array())
    {
        $default = array("head" => !IS_AJAX, "nav" => !IS_AJAX, "foot" => true,);
        $params = array_merge($default, $params);

        if ($params['head']) {
            $this->display("Admin/Public/html_head");
        }
        if ($params['nav']) {
            $this->nav_init();
        }
        if ($params['foot']) {
            $this->footer = $this->fetch("Admin/admin_footer");
        }
    }

    /**
     * 后台菜单渲染方法
     */
    protected function nav_init()
    {
        // TODO:子类请自觉重写菜单渲染
    }

    /**
     * 控制器运行结束,输出页脚
     * TODO 控制器结束应该输出重置页面javascript的脚本
     */
    function __destruct()
    {
        if (!IS_AJAX) {
            echo $this->footer;
        }
    }

    /**
     * 简易信息提示返回
     * @param $status 状态true(成功)或者false(失败)
     * @param $msg
     * @param $ero
     * @return string
     */
    public function display_msg($status, $msg, $ero, $url = "#")
    {
        if ($status) {
            $this->assign("message", $msg);
        } else {
            $this->assign("error", $ero);
            $this->status = false;
        }
        $this->assign("jumpUrl", $url);
        $model = IS_AJAX ? "msg_noajax" : "msg";
        echo $this->fetch("Public/" . $model);
        $this->status = false;
        exit;
    }
}