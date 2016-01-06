<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <!--最高版本浏览器指定-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--针对移动设备,不进行按比例缩放-->
    <?php if($pageInfo["mode"] == 1): ?><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"><?php endif; ?>
    <!--页面标题-->
    <title>[title]</title>
    <!--简介-->
    <meta name="description" content="[description]">
    <!--关键词-->
    <meta name="keywords" content="[keywords]">

    <!--ios系统配置-->
    <meta name="apple-mobile-web-app-title" content="<?php echo (var_being($htmlTitle)); ?> - <?php echo (L("TZ_name")); ?>"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <meta name="renderer" content="webkit">
    <!-- No Baidu Siteapp 禁止网页转码-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>

    <!--外部文件引入-->
    <link rel="stylesheet" type="text/css" href="/RabbitCMS/Public/amaze/css/amazeui.min.css" />

    <script type="text/javascript" src="/RabbitCMS/Public/amaze/js/jquery.min.js"></script>
    <script type="text/javascript" src="/RabbitCMS/Public/amaze/js/amazeui.min.js"></script>
    <script type="text/javascript" src="/RabbitCMS/Public/tuzi/js/Common.js"></script>

    <link rel="stylesheet" type="text/css" href="/RabbitCMS/Public/diavision/css/diavision.css" />
</head>

<body>