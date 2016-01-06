<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/11/28
 * Time: 13:47
 */

namespace Rabbit\Controller;

use Rabbit\Controller\BaseController;

/**
 * Class AdminController - 后台控制器
 * @package Diavision\Controller
 */
class AdminController extends BaseController
{

    protected $accessList = array(
        "index", "post", "post_submit", "cate", "template", "user", "setting",
    );

    public function __construct($params = array())
    {
        parent::__construct();
        $this->_initial();
        $this->broad_init($params);
    }

    /**
     * 后台初始化
     * 负责处理各种功能单元的载入与初始化
     */
    private function _initial()
    {
        define("IS_ADMIN", $this->_valid_admin());
    }

    /**
     * 后台渲染初始化
     * 可以通过url中加入hasNav和hasUserGrid为0来手动不加载导航菜单与用户菜单
     * @param bool|true $hasNav
     * @param bool|true $hasUserGrid
     */
    protected function nav_init()
    {
        $menuParams = C("NAV.ADMIN");
        $this->assign("navTitle", "控制后台");
        $this->assign("menu", $menuParams);
        $this->assign("user", S("USER_CUR"));
        $user_broad = $this->fetch("Admin/User/user_broad_btn");
        $this->assign("user_broad", $user_broad);
        $this->display("Admin/Public/navigation");
    }

    /**
     * 检验用户是否管理员的快捷方法
     * 通过检验全局变量IS_ADMIN来进行区分,与实时用户数据耦合挂钩
     * 在config.php中可以手动向ADMIN_FILTER_ACTION添加免管理权限认证的页面
     * @return mixed
     */
    private function _valid_admin()
    {
        if (defined("IS_ADMIN")) {
            return IS_ADMIN;
        }

        if (!$this->unit("user")->admin) {
            $this->status = $this->unit("user")->admin;
            $this->error("对不起,您的身份不是管理员,不能进入管理后台", U("Index/index"));
        }

        return $this->status;
    }

    /**
     * ajax读取框架
     * @param string $title 框架标题
     * @param string $left 框架主题内容
     * @param string $right 框架副内容
     */
    private function _ajax_frame($title = "", $left = "", $right = "")
    {
        if (IS_AJAX) {
            $frame['title'] = $title;
            $frame['left'] = $left;
            $frame['right'] = $right;
            $this->assign("frame", $frame);
            $this->display("Admin/Public/admin_ajax_frame");
        }
    }

    /******************************** 页面访问方法 *******************************/

    /**
     * 后台首页
     */
    public function index()
    {
        echo L("home_page");
    }

    /**
     * 内容页
     */
    public function post()
    {
        $cates = $this->unit("cate")->get_cates();
        $this->assign("cateList", $cates);
        $this->assign("cateName", "全部分类");
        $this->display("Admin/Post/post_index");
    }

    /**
     * 内容列表页
     */
    public function post_list()
    {
        $args = array();
        $search = I("get.search");
        if (!empty($search)) {
            $args['title'] = array("like", "%" . $search . "%");
        }
        $cate = I("get.cate");
        if (!empty($cate)) {
            $args['category'] = array("eq", $cate);
        }
        $args['hasPage'] = true;
        $posts = $this->unit("post")->get_post($args);
        $this->assign("posts", $posts);
        $postList = $this->fetch("Admin/Post/post_list");
        $this->show($postList);
        $pages = $this->unit("post")->page->theme("amaze")->show("am-text-center", "PostListSection");// 分页显示输出
        $this->show($pages);
    }


    public function post_publish()
    {
        $pid = is_null(I('get.pid')) ? null : I('get.pid');
        if (!is_null($pid)) {
            $args['id'] = array("eq", $pid);
            $post = $this->unit("post")->get_post($args);
            $post = $post[0];
            $this->assign("post", $post);
        }
        $postPublish = $this->fetch("Admin/Post/post_publish_baidu");
        $title = $post ? $post['title'] . " - 正在编辑..." : "撰写新内容";
        $this->show($postPublish);
    }

    public function post_submit()
    {
        $postDatas = I("post.");
        $subMod = I("post.submitMod");
        switch ($subMod) {
            case "insert":
                $flag = $this->unit("post")->insert($postDatas) ? true : false;
                break;
            case "update":
                $flag = $this->unit("post")->update($postDatas) ? true : false;
                break;
        }
        $msg = $flag ? "操作成功~" : "操作失败";
        $this->success($msg);
    }

    public function post_del()
    {
        $postId = I("post.id");
        $flag = $this->unit("post")->del_post($postId) ? true : false;
        $this->display_msg($flag, "已经删除指定内容", "出错!不能删除");
    }

    public function post_view()
    {
        $postId = I("get.pid");
        $post = $this->unit("post")->get_post($postId);
        $this->assign("post", $post);
        $this->display("Admin/Post/post_preview");
    }


    /* 栏目 */
    public function cate()
    {
        $cates = $this->unit("cate")->get_cates();
        $this->assign("cates", $cates);
        $this->display("Admin/Cate/cate_index");
    }

    public function cate_list()
    {
        $search = I("get.search");
        if (!empty($search)) {
            $args['name'] = array("like", "%" . $search . "%");
        }
        $cate = I("get.cate");
        if (!empty($cate)) {
            $args['parent'] = array("eq", $cate);
        }
        $cates = $this->unit("cate")->get_cates($args);
        $cates = $this->unit("cate")->get_cates_tree($cates);
        $this->assign("cates", $cates);
        $display = $this->fetch("Admin/Cate/cate_list");
        $this->show($display);
    }

    public function cate_submit()
    {
        $cateDatas = I("post.");
        $subMod = I("post.submitMod");
        switch ($subMod) {
            case "insert":
                $msg = $this->unit("cate")->insert($cateDatas) ? "~操作成功~" : "@操作失败@";
                break;
            case "update":
                $msg = $this->unit("cate")->update($cateDatas) ? "~操作成功~" : "@操作失败@";
                break;
        }
        $this->show($msg);
    }

    public function cate_insert()
    {
        $id = I("post.id");
        if ($id) {
            $cate = $this->unit("cate")->get_cate($id);
            $this->assign("cate", $cate);
        }
        $cates = $this->unit("cate")->get_cates();
        $cates = $this->unit("cate")->get_cates_tree($cates);
        $this->assign("cates", $cates);
        $res = $this->fetch("Admin/Cate/cate_insert");
        $this->show($res);
    }

    /**
     * 栏目删除
     */
    public function cate_del()
    {
        $cateId = I("post.id");
        $flag = $this->unit("cate")->del($cateId);
        echo $this->display_msg($flag, "栏目已经删除!", "不能删除指定栏目");
    }

    function setting()
    {
        $setting = $this->unit['set']->get_settings();
        $this->assign("settings", $setting);
        $this->display("Admin/setting");

    }

    function set_submit()
    {
        $flag = $this->unit['set']->update();
        echo $flag ? "成功更新" : "更新失败";
    }

    public function user()
    {
        $users = $this->userUnit->get_users();
        $this->assign("users", $users);
        $this->display("Admin/User/user_index");
    }

    public function user_list()
    {
        $params = I("get.");
        $users = $this->userUnit->get_users($params);
        $this->assign("users", $users);
        $this->display("Admin/User/user_list");
    }

    /* 模板 */
    public function template()
    {
        $params = I("get.");
        $templates = $this->unit("template")->get_template($params);
        $this->assign("templates", $templates);
        $this->display("Admin/Template/template_index");
    }

    public function template_list()
    {
        $params = I("post.");
        $templates = $this->unit("template")->get_template($params);
        $this->assign("templates", $templates);
        $this->display("Admin/Template/template_list");
    }


    function cateImgUpload()
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->savePath = C("SYSTEM_FLAG") . "/"; // 设置附件上传目录
        $info = $upload->upload();
        $this->ajaxReturn(__ROOT__ . "/Uploads/" . $info[0]['savepath'] . $info[0]['savename']);
    }

    /**
     * 更新日志
     */
    function what_new()
    {
        $this->_ajax_frame("更新日志", $this->fetch("what_new"), "zicok");
    }


}