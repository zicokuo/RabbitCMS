<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/11/27
 * Time: 15:59
 */

namespace Rabbit\Controller;

use Rabbit\Controller\BaseController;

/**
 * 网站前端控制器
 * Class IndexController
 * @package Rabbit\Controller
 */
class IndexController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->board_init();
    }

    /**
     * 顶栏导航菜单渲染
     */
    protected function nav_init()
    {
        $this->informations = $this->unit("Website")->get_website_options();
        $menuParams = C("NAV.WEBSITE");
        $this->assign("navTitle", __("website board"));
        $this->assign("menu", $menuParams);
        $this->assign("user", S("USER_CUR"));
        $user_board = $this->fetch("Admin/User/user_board_btn");
        $this->assign("user_board", $user_board);
        $this->display("Admin/Public/navigation");
    }

    /**
     * 网站前端控制台
     * 主要用于进行网站前端界面的修改
     */
    public function control_board()
    {
        $this->display("Index/board/control_board");
    }

    function website_information()
    {
        if(I("post.submit")){
            $this->success("yse!");
        }
        $this->informations = $this->unit("Website")->get_website_options();
        $this->title = __("website information");
        $this->display("Index/board/website_information");
    }

    /**
     * 网站首页
     */
    public function index()
    {
        //加载页头文件
        $this->display("Common/html_head");
        $this->get_user_board();
        //加载页面顶部文件
        $this->display("Index/page_head");
        //加载导航文件
        if ($this->informations['custom_navigation']){
            $this->display("Index/nav_bar");
        }
        //加载大图滚动
        $this->display("Index/main_bannar");
        //加载主体内容
        $this->display("Index/main_part");
        //加载页脚
        $this->display("Common/html_foot");
    }


    public function get_user_board()
    {
        return false;
        $this->assign("user", S("USER_CUR"));
        $this->display("Admin/User/user_board_btn");
    }
}