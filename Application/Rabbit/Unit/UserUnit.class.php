<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/11/28
 * Time: 13:48
 */

namespace Rabbit\Unit;

use Rabbit\Unit\BaseUnit;

/**
 * 用户功能单元
 * Class UserUnit
 * @package Diavision\Unit
 */
class UserUnit extends BaseUnit
{
    public $unit;
    public $model;

    public function __construct()
    {
        $this->model = D("User");
        $this->usid = session_id();
        $this->unit = array_merge($this->unit, $this->_user_init());
        $this->accTime = time();
        S("USER_CUR", $this->unit, C('CACHE_EXPIRED'));
    }

    /**
     * 用户初始化
     */
    private function _user_init()
    {
        $userCache = $this->get_user_cache();
        if (is_null($userCache) || is_null($userCache['nick'])) {
            $userCache = $this->_create_visitor("访客");
        }
        $this->_set_user_cache($userCache);
        return $userCache;
    }

    /**
     * 设置用户缓存
     * @param $user
     */
    private function _set_user_cache($user)
    {
        $user['accTime'] = time();
        $user['expire'] = C('CACHE_EXPIRED');
        $user['identName'] = $user['admin'] ? "管理员" : "用户";
        session("usid_" . $this->usid, $user);
    }

    /**
     * 更新用户缓存
     */
    private function _update_user_cache()
    {
        $uid = $this->id;
        $user = M("User");
        $user = $user->getById($uid);
        $this->_set_user_cache($user);
    }

    /**
     * 清除登陆的用户对应缓存
     * @return mixed
     */
    private function _clear_user_cache()
    {
        session("usid_" . $this->usid, null);
        return $this->get_user_cache();
    }

    /**
     * 从缓存中取出用户信息
     */
    public function get_user_cache()
    {
        return $userCache = session("usid_" . $this->usid);
    }

    /**
     * 初始化访客信息 - 最经常调用
     * @param string $nick
     * @param int $exp
     * @return array
     */
    private function _create_visitor($nick = "Visitor", $exp = 7200)
    {
        $visitorField = array("nick", "ident", "group", "admin", "expire");
        $visitorData = array($nick, 0, 0, false, $exp);
        return array_combine($visitorField, $visitorData);
    }


    private function _is_admin()
    {
        return $this->admin;
    }

    public function login_in($datas)
    {
        $rules = array(
            array('account', '3,12', '登陆账户错误', 1, 'length'),
            array('password', '3,20', '登陆密码长度不正确', 1, 'length'),
        );
        $userModel = M('User');
        $flag = $userModel->validate($rules)->create($datas);
        if (!$flag) {
            return $userModel->getError();
        }
        $user = $userModel->where("account = '%s'", $datas['account'])->find();
        if ($user && $user['password'] == $datas['password']) {
            unset($user['password']);
            $this->_set_user_cache($user);
            return true;
        } else {
            return "帐号或密码错误";
        }
    }

    public function login_out()
    {
        return $this->_clear_user_cache();
    }

    public function get_users($args = null)
    {
        if (is_array($args)) {
            $this->model->where($args);
        }
        return $this->model->order(array('group'))->select();
    }

    public function get_user($field, $param)
    {
        $args[$field] = array("eq", $param);
        $set = $this->get_settings($args);
        return $set[0];
    }

    public function update($params, $where = "id")
    {
        if (isset($params[$where])) {
            $whereVal = $params[$where];
            unset($params[$where]);
            $flag = $this->model->where($where . "='%s'", $whereVal)->save($params);
            if ($flag) {
                $this->_update_user_cache();
            }
            return $flag;
        } else {
            return false;
        }
    }

    public function user_register($params)
    {

    }
}