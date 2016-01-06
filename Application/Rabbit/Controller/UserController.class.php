<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/30
 * Time: 19:14
 */

namespace Rabbit\Controller;

use Rabbit\Controller\BaseController;

class UserController extends BaseController
{

    public function __construct($params = array())
    {
        parent::__construct();
        $this->broad_init($params);
    }

    /**
     * 后台渲染初始化
     */
    protected function nav_init()
    {
        $menuParams = C("NAV.USER_CENTER");
        $this->assign("navTitle", "用户中心");
        $this->assign("menu", $menuParams);
        $this->assign("user", S("USER_CUR"));
        $user_broad = $this->fetch("Admin/User/user_broad_btn");
        $this->assign("user_broad", $user_broad);
        $this->display("Admin/Public/navigation");
    }

    public function index()
    {
        $this->display("Admin/User/user_center");
    }

    /**
     * 用户登录登出和注册的入口
     * 排除在权限验证之外
     */
    public function user_enter()
    {
        $subMod = I("get.subMod");

        //用户登出
        if ($subMod == "logout") {
            $this->userUnit->login_out();
            $this->success("已经成功登出~", U("User/user_enter"), 10);
            return;
        }

        //用户身份为管理员,则自动进行登陆
        if ($this->userUnit->admin) {
            $this->success("欢迎!管理员[" . $this->userUnit->nick . "],稍后自动进入后台", U("Admin/index"), 5);
            return;
        }

        $pageInfo["title"] = "后台系统用户入口";
        switch ($subMod) {
            case "login":
                $flag = $this->userUnit->login_in(I("post."));
                if ($flag === true) {
                    $this->success("恭喜!您已经成功登陆", U("Admin/index"), 10);
                } else {
                    $this->error($flag, U("Admin/user_enter"), 10);
                }
                break;
            case "register":
                $this->userUnit->user_register(I("post."));
                $this->success("恭喜!您已经成功注册", "", 10);
                break;
            case "logout":
                $this->userUnit->login_out();
                $this->success("已经成功登出~", U("Admin/User/user_enter"), 10);
                break;
            default:
                $this->display("Admin/User/user_enter");
        }
    }

    public function user_record(){
        $this->display("Admin/User/user_record");
    }

    public function user_avatar()
    {
        $this->display("Admin/User/user_avatar");
    }

    function upload_avatar_submit()
    {
        $params = array("head" => false, "nav" => false);
        $uploadCon = new \Diavision\Controller\MediaController($params);
        $uploadCon->upload_avatar("userAvatar");
    }




}