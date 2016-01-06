<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/17
 * Time: 1:18
 */

return array(
    // 添加下面一行定义即可
    //'app_begin' => array('Behavior\CheckLang'),
    // 如果是3.2.1版本 需要改成
    'app_begin' => array('Behavior\CheckLangBehavior'),
);