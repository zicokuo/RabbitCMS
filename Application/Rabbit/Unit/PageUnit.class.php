<?php
/**
 * Created by PhpStorm.
 * User: Zicok
 * Date: 2015/12/29
 * Time: 11:19
 */

namespace Rabbit\Unit;

//继承自Thinkphp的page类,主要进行样式修改
class PageUnit
{
    public $firstRow; // 起始行数
    public $listRows; // 列表每页显示行数
    public $parameter; // 分页跳转时要带的参数
    public $totalRows; // 总行数
    public $totalPages; // 分页总页面数
    public $rollPage = 11;// 分页栏每页显示的页数
    public $lastSuffix = true; // 最后一页是否显示总页数

    private $p = 'p'; //分页参数名
    private $url = ''; //当前链接URL
    private $nowPage = 1;

    // 分页显示定制
    private $config = array(
        'header' => '<span class="rows">共 %TOTAL_ROW% 条记录</span>',
        'prev' => '<<',
        'next' => '>>',
        'first' => '1...',
        'last' => '...%TOTAL_PAGE%',
        'theme' => '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%',
        'element' => '<a class="%s" href="%s">%s</a>',
        'cur_element' => '<span class="current">%s</span>',
        'wrap_element' => '<div class="%s">%s</div>',
        'wrap_element_class' => 'pages',
        'url_extra' => '',//url附加项
    );

    /**
     * 架构函数
     * @param array $totalRows 总的记录数
     * @param array $listRows 每页显示记录数
     * @param array $parameter 分页跳转的参数
     */
    public function __construct($totalRows, $listRows = 20, $parameter = array())
    {
        C('VAR_PAGE') && $this->p = C('VAR_PAGE'); //设置分页参数名称
        /* 基础设置 */
        $this->totalRows = $totalRows; //设置总记录数
        $this->listRows = $listRows;  //设置每页显示行数
        $this->parameter = empty($parameter) ? $_GET : $parameter;
        $this->nowPage = empty($_GET[$this->p]) ? 1 : intval($_GET[$this->p]);
        $this->nowPage = $this->nowPage > 0 ? $this->nowPage : 1;
        $this->firstRow = $this->listRows * ($this->nowPage - 1);
    }

    /**
     * 定制分页链接设置
     * @param string $name 设置名称
     * @param string $value 设置值
     */
    public function setConfig($name, $value)
    {
        if (isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    /**
     * 生成链接URL
     * @param  integer $page 页码
     * @return string
     */
    private function url($page)
    {
        return str_replace(urlencode('[PAGE]'), $page, $this->url);
    }

    private function element_init($class = "", $href = "", $param = "")
    {
        return sprintf($this->config['element'], $class, $href, $param);
    }

    /**
     * 组装分页链接
     * @return string
     */
    public function show($wrap_class = "", $ajax_id = false)
    {
        if (0 == $this->totalRows) return '';

        /* 生成URL */
        $this->parameter[$this->p] = '[PAGE]';
        $this->url = U(ACTION_NAME, $this->parameter);
        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        if (!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }

        /* 计算分页临时变量 */
        $now_cool_page = $this->rollPage / 2;
        $now_cool_page_ceil = ceil($now_cool_page);
        $this->config['last'] = $this->lastSuffix ? $this->totalPages : $this->config['last'];

        //上一页
        $up_row = $this->nowPage - 1;
//        $up_page = $up_row > 0 ? '<a class="prev" href="' . $this->url($up_row) . '">' . $this->config['prev'] . '</a>' : '';
        $up_page = $up_row > 0 ? $this->element_init("prev", $this->url($up_row), $this->config['prev']) : '';

        //下一页
        $down_row = $this->nowPage + 1;
//        $down_page = ($down_row <= $this->totalPages) ? '<a class="next" href="' . $this->url($down_row) . '">' . $this->config['next'] . '</a>' : '';
        $down_page = ($down_row <= $this->totalPages) ? $this->element_init("next", $this->url($down_row), $this->config['next']) : '';

        //第一页
        $the_first = '';
        if ($this->totalPages > $this->rollPage && ($this->nowPage - $now_cool_page) >= 1) {
//            $the_first = '<a class="first" href="' . $this->url(1) . '">' . $this->config['first'] . '</a>';
            $the_first = $this->element_init("first", $this->url(1), $this->config['first']);
        }

        //最后一页
        $the_end = '';
        if ($this->totalPages > $this->rollPage && ($this->nowPage + $now_cool_page) < $this->totalPages) {
//            $the_end = '<a class="end" href="' . $this->url($this->totalPages) . '">' . $this->config['last'] . '</a>';
            $the_end = $this->element_init("end", $this->url($this->totalPages), $this->config['last']);
        }

        //数字连接
        $link_page = "";
        for ($i = 1; $i <= $this->rollPage; $i++) {
            if (($this->nowPage - $now_cool_page) <= 0) {
                $page = $i;
            } elseif (($this->nowPage + $now_cool_page - 1) >= $this->totalPages) {
                $page = $this->totalPages - $this->rollPage + $i;
            } else {
                $page = $this->nowPage - $now_cool_page_ceil + $i;
            }
            if ($page > 0 && $page != $this->nowPage) {

                if ($page <= $this->totalPages) {
//                    $link_page .= '<a class="num" href="' . $this->url($page) . '">' . $page . '</a>';
                    $link_page .= $this->element_init("num", $this->url($page), $page);
                } else {
                    break;
                }
            } else {
                if ($page > 0 && $this->totalPages != 1) {
//                    $link_page .= '<span class="current">' . $page . '</span>';
                    $link_page .= sprintf($this->config['cur_element'], $page);
                }
            }
        }

        //替换分页内容
        $page_str = str_replace(
            array('%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTAL_PAGE%'),
            array($this->config['header'], $this->nowPage, $up_page, $down_page, $the_first, $link_page, $the_end, $this->totalRows, $this->totalPages),
            $this->config['theme']);

        $page_str = sprintf($this->config['wrap_element'], $this->config['wrap_element_class'] . " " . $wrap_class, $page_str);

        if ($ajax_id) {
            $page_script = "
<script type='text/javascript'>
$(function(){
    $('." . $this->config['wrap_element_class'] . "').find('a').on('click',function(){
        var _this = $(this);
        var ajax = new tz_ajax();
        ajax.url(_this.attr('href')).go().updateDom($('#" . $ajax_id . "'));
        return false;
    });
})
</script>";
            return $page_str . $page_script;
        } else {
            return $page_str;
        }
    }

    public function theme($theme = "")
    {
        switch ($theme) {
            case "amaze":
                $this->setConfig("element", '<li><a class="%s" href="%s">%s</a></li>');
                $this->setConfig("cur_element", '<li class="am-active"><a href="#">%s</a></li>');
                $this->setConfig("wrap_element", '<ul class="%s">%s</ul>');
                $this->setConfig("wrap_element_class", "am-pagination");
                break;
        }
        return $this;
    }
}