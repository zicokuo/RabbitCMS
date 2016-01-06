<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/6
 * Time: 2:53
 */

namespace Rabbit\Unit;

use Think\Think;

/**
 * 上传功能单元
 * Class UploadUnit
 * @package Diavision\Unit
 */
class UploadUnit
{

    private $_upload;
    //图片路径
    protected $configs = array();

    const UPLOAD_PATH = "Uploads/";//根目录
    const IMG_PATH = "image/";//图片
    const AVATAR_PATH = "avatar/";//头像
    const TEMP_PATH = "temp/";//临时文件

    public function __construct()
    {
        $this->customPath = "";
    }

    public function __set($name, $value)
    {
        if (is_null($value)) {
            unset($this->configs[$name]);
        } else {
            $this->configs[$name] = $value;
        }
    }

    public function __get($name)
    {
        return $this->configs[$name];
    }

    /**
     * 用作批量添加上传规则,一般不需要手动使用
     * @param $config
     */
    private function _create($config)
    {
        $this->configs = array_merge($config, $this->configs);
        return $this;
    }

    /**
     * 增加指定路径
     * 指定路径将会加插到根目录与相对保存路径之后
     * 一般用作临时修改存放文件夹,或者保存暂存图片
     * @param bool $path
     * @return $this
     */
    public function path($path = false)
    {
        if ($path) {
            $this->customPath = $path;
        } else {
            $this->customPath = self::TEMP_PATH;
        }
        return $this;
    }


    private function _init_upload($type = '')
    {
        $params = array();
        //默认上传文件为图片
        $params['maxSize'] = 3 * 1024 * 1024; // 3MB图片
        $params['savePath'] = C("SYSTEM_FLAG") . "/" . self::IMG_PATH;
        $params['exts'] = array('jpg', 'gif', 'png', 'jpeg');
        $params['rootPath'] = './' . self::UPLOAD_PATH; //保存根路径
        switch ($type) {
            case"avatar":
                $params['maxSize'] = 1 * 1024 * 1024; // 1MB图片
                $params['savePath'] = C("SYSTEM_FLAG") . "/" . self::AVATAR_PATH;
                $params['autoSub'] = false;
                break;
        }
        //处理自定义文件路径
        $params['savePath'] = str_replace("//", "/", $params['savePath'] . "/" . $this->customPath);
        $this->_create($params);
//        var_dump($this->configs);
        $this->_upload = new \Think\Upload($this->configs);
    }

    /**
     * 文件上传路径返回处理,不适合base64上传使用
     * @param $files
     * @return string
     */
    private function _return($files)
    {
        if (count($files) < 2) {
            return __ROOT__ . "/" . self::UPLOAD_PATH . $files[0]['savepath'] . $files[0]['savename'];
        } else {
            foreach ($files as $key => $file) {
                $files[$key]['fileUrl'] = __ROOT__ . "/" . self::UPLOAD_PATH . $file['savepath'] . $file['savename'];
            }
            return $files;
        }
    }

    /**
     * 上传图片
     * @param string $files
     * @param string $type
     * @return string
     */
    public function upload_image($files = '', $type = '')
    {
        $this->_init_upload($type);
        $info = $this->_upload->upload($files);
        return $this->_return($info);
    }

    public function upload_avatar($file = '')
    {
        return $this->upload_image($file, "avatar");
    }

    /**
     * 上传Base64码,解码并保存为图片文件
     * @param $base64_image_content
     * @return bool|string
     */
    public function upload_base64($base64_image_content)
    {
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $this->_init_upload("avatar");
            $saveName = md5(time());
            $relativeFilePath = '/' . self::UPLOAD_PATH . $this->savePath;
            $absFilePath = "." . $relativeFilePath;
            if (!is_dir($absFilePath)) {
                //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
                $res = mkdir($absFilePath, 0777, true);
                if (!$res) {
                    return false;
                }
            }
            $new_file = $absFilePath . $saveName . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return $relativeFilePath . $saveName . ".{$type}";
            }
        }
        return false;
    }

}