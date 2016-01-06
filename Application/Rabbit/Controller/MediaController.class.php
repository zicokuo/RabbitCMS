<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2016/1/3
 * Time: 9:44
 */

namespace Rabbit\Controller;

use Rabbit\Controller\BaseController;

/**
 * 媒体控制器
 * Class MediaController
 * @package Diavision\Controller
 */
class MediaController extends BaseController
{

    function __construct($params = array())
    {
        parent::__construct();
        $this->broad_init($params);
    }

    /**
     * 重写菜单初始化
     */
    protected function nav_init()
    {
        $menuParams = C("NAV.MEDIA");
        $this->navTitle = "媒体库";
        $this->assign("menu", $menuParams);
        $this->assign("user", S("USER_CUR"));
        $user_broad = $this->fetch("Admin/User/user_broad_btn");
        $this->assign("user_broad", $user_broad);
        $this->display("Admin/Public/navigation");
    }

    public function index()
    {
        $this->display("Admin/Media/media_index");
    }

    public function browse_files()
    {
        $dir = "./Uploads";
        $pathSrc = I("get.path") ? I("get.path") : "";
        $path = str_replace("#", "/", I("get.path"));
        $dir .= html_entity_decode($path) . "/";
        //扫描文件夹
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {
                        //转换文件名
                        $fileInfo["file"] = iconv("gb2312", "UTF-8", $file);
                        //分析文件类型
                        $type = explode(".", $file);
                        $fileInfo["type"] = ($type[0] === $file) ? filetype(($dir . $file)) : array_pop($type);
                        //文件相对路径
                        $fileInfo["path"] = $pathSrc . "#" . $file;
                        $files[] = $fileInfo;
                    }
                }
                closedir($dh);
            }
        }

        //二级目录后,增加返回
        if (!empty($pathSrc)) {
            $backPath = explode("#", $pathSrc);
            $backPath = implode("#", array_slice($backPath, 0, -1));
            $files[] = array(
                "file" => "上级目录",
                "type" => "back",
                "path" => $backPath
            );
        }
        $this->assign("rootPath", $dir);
        $this->assign("files", array_reverse($files));
        $this->display("Admin/Media/media_browse");
    }

    /**
     * 获取上传文件界面
     */
    public function upload_broad()
    {

    }

    /**
     * 上传缓存文件
     */
    function upload_image_temp()
    {
        $upload = new \Diavision\Unit\UploadUnit();// 实例化上传类
        $temp = $upload->path()->upload_image();
        echo $temp;
    }

    /**
     * 上传头像
     */
    function upload_avatar($inputName)
    {
        $baseCode = I("post." . $inputName);
        $upload = new \Diavision\Unit\UploadUnit();// 实例化上传类
        $avatarPath = $this->userUnit->unit['id'] . "/";
        $avatar = $upload->path($avatarPath)->upload_base64($baseCode);
        if ($avatar) {
            $params['id'] = $this->userUnit->unit['id'];
            $params['avatar'] = __ROOT__ . $avatar;
            $avatar = $this->userUnit->update($params);
            echo $this->display_msg($avatar, "成功更新头像!", "~上传头像失败~");
        }
        echo $this->display_msg($avatar, "成功更新头像!", "~更新头像失败~");
    }
}