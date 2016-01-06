<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/11/30
 * Time: 14:52
 */
return array(

    //系统开发模式
    'APP_DEV' => true,

    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => 'Public:msg',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Public:msg',
    //缓存时效
    'CACHE_EXPIRED' => 7200,

    //数据库配置信息
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => 'localhost', // 服务器地址
    'DB_NAME' => 'rabbitcms',// 数据库名
    'DB_USER' => 'root',// 用户名
    'DB_PWD' => '',// 密码
    'DB_PORT' => 3306,// 端口
    'DB_PREFIX' => '',// 数据库表前缀
    'DB_CHARSET' => 'utf8',// 字符集

    //语言包功能配置信息
    'LANG_SWITCH_ON' => true,   // 开启语言包功能
    'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST' => 'zh-cn', // 允许切换的语言列表 用逗号分隔
//    'VAR_LANGUAGE' => 'l', // 默认语言切换变量

    //免检页面
    'WITHOUT_CHECK' => array(
        "user_login",
        "user_logout",
        "user_enter"
    ),

    //导航配置
    "NAV" => array(
        "ADMIN" => array(
            "home" => array("url" => "Index/index", "name" => "index"),
            "post" => array("url" => "Admin/post", "name" => "内容"),
            "cate" => array("url" => "Admin/cate", "name" => "栏目"),
            "media" => array("url" => "Media/index", "name" => "媒体库"),
            "template" => array("url" => "Admin/template", "name" => "模板"),
            "user" => array("url" => "Admin/user", "name" => "用户"),
            "setting" => array("url" => "Admin/setting", "name" => "设置"),
        ),
        "USER_CENTER" => array(
            "home" => array("url" => "Index/index", "name" => "返回首页"),
            "admin" => array("url" => "Admin/index", "name" => "管理后台"),
        ),
        "MEDIA" => array(
            "home" => array("url" => "Index/index", "name" => "返回首页"),
            "admin" => array("url" => "Admin/index", "name" => "管理后台"),
        ),
        "WEBSITE" => array(
            "home" => array("url" => "Index/index", "name" => "return index"),
            "broad" => array("url" => "Index/control_board", "name" => "website board"),
        )
    ),
    "MENU" => array(
        "WEBSITE_BOARD_MENU" => array(
            "home" => array("url" => "Index/website_information", "name" => "website information"),
            "broad" => array("url" => "Index/main_navigation", "name" => "main navigation"),
        ),
    ),
    //管理后台默认菜单
    'ADMIN_TOPNAV' => array(
        'Admin/index' => '后台首页',
        'Admin/post' => '内容',
        'Admin/cate' => '栏目',
        'Admin/template' => '模板',
        'Admin/user' => '用户',
        'Admin/setting' => '设置',
    ),


    //用户中心菜单
    'USER_TOPNAV' => array(),

    //系统格式化输出
    'SYS_MSG' => array(
        0 => "测试:%s",
        200 => "对不起,%s您不是管理员!",
        201 => "请输入正确的用户名和密码.",
    ),

    //查询配置
    "INQUIRY" => array(
        "limit" => 5,
    ),

    //日期格式化
    "DATA_FORMAT" => "Y-m-d h:i:s",

    //日志记录
    'LOG_RECORD' => true,   // 默认不记录日志
    'LOG_TYPE' => 'File',  // 日志记录类型 默认为文件方式
    'LOG_LEVEL' => 'EMERG,ALERT,CRIT,ERR,',// 允许记录的日志级别
    'LOG_EXCEPTION_RECORD' => false,    // 是否记录异常信息日志
);