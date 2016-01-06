<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/11
 * Time: 10:40
 */

function addition_tag($con, $tag)
{
    echo $con ? $tag : "";
}


/**
 * 时间转换成日期
 * @param bool|false $time
 * @param bool|false $formatStr
 * @return bool|string
 */
function time_to_date($time = false, $formatStr = false)
{
    $time = $time ? $time : time();
    $formatStr = $formatStr ? $formatStr : C("DATA_FORMAT");
    return date($time, $formatStr);
}

/**
 * 日期转换时间
 * @param bool|false $date
 * @return int
 */
function date_to_time($date = false)
{
    $date = $date ? $date : date(C("DATA_FORMAT"), time());
    return strtotime($date);
}

function __($key = null, $sign = " ", $newWord = "")
{
    //中文自动去掉字间空格
    $break = (LANG_SET == "zh-cn") ? "" : " ";
    $words = explode($sign, $key);
    foreach ($words as $k => $w) {
        $newWord .= $break . strtolower(L($w));
    }
    return trim($newWord);
}