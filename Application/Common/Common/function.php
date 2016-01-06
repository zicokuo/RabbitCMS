<?php
/**
 * Created by PhpStorm.
 * User: Zicokuo
 * Date: 2015/7/28
 * Time: 14:32
 */

//三元判断输出变量 - being
function var_being($var)
{
    return isset($var) ? $var : null;
}

function is_exprie($t1, $t2)
{
    return $t1 - $t2;
}

function amaze_input_check($stats)
{
    if (empty($stats)) {
        return "am-form-warning";
    } else if ($stats === false) {
        return "am-form-danger";
    } else {
        return "am-form-success";
    }
}

function compare_putout($d1, $d2, $p)
{
    echo ($d1 == $d2) ? $p : "";
}
