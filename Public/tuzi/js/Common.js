/**
 * Created by Zicok on 2015/11/20.
 */
/**
 * Created by Zicok on 2015/11/4.
 */

//本js以来jQuery库驱动,使用前请确保jQuery库已经成功导入


/**
 * 兔子系统 js 脚本总类
 */
var rabbit = function () {

    var _this = this;
    var _win = "window";
    var _doc = "window.document";


    this.layout_verticalMiddle = function ($parent,$target) {
        $target = $target ? $target : $(_this);
        $parent = $parent ? $parent : $target.parent();
        $target.css({"margin-top": ($parent.height() - $target.height()) / 2});
    }

    return this;
}

$.prototype.rabbit = rabbit;
$.rabbit = rabbit;

//布局类
function tz_layout() {

    this._win = $(window);
    this._dom = $(window.document);

    //流体布局
    this.fluorLayout = function ($WholeScreenDom, $fartherDom) {
        $fartherDom = $fartherDom ? $fartherDom : this._dom;
        $fartherDom.css({'overflow': 'hidden'});
        $WholeScreenDom.width($fartherDom.width()).height($fartherDom.height());
    }

    //元素满屏
    this.fullscreen = function ($tarDom, $fartherDom, $offset) {
        $fartherDom = $fartherDom ? $fartherDom : this._dom;
        $offset = $offset ? $offset : 0;
        height = $fartherDom.height();
        $tarDom.height(height - $offset);
    }

    //元素居中
    this.verticalAlignMiddle = function ($tarDom, $fartherDom) {
        $fartherDom = $fartherDom ? $fartherDom : $tarDom.parent();
        //$tarDom.css({"position": "relative", "top": ($fartherDom.height() - $tarDom.height()) / 2});
        $tarDom.css({"margin-top": ($fartherDom.height() - $tarDom.height()) / 2});
    }
}

$.prototype.ajaxFrame = function ($target) {
    this.each(function () {
        $this = $(this);
        //console.log($this);
        // 如果对象已经拥有ajaxFramed,则跳过
        if ($this.hasClass("ajaxFramed")) {
            return false;
        }
        // 获取对象的参数
        $datastr = $this.data("ajax-btn") ? $this.data("ajax-btn") : "{}";
        if (typeof $datastr == "object") {
            var $options = $datastr;
        } else {
            var $options = (new Function("return " + $datastr))();
        }
        // 对象为链接,则优先使用链接本身的目标路径
        if ($this.is("a")) {
            $options.url = $this.attr("href");
        }
        // 对象为提交按钮,则根据按钮的data-form属性,对表单提交数据进行处理
        if($this.is("button[type='submit']")){
            $form = $("#"+$this.data("form"));
            $options.url = $form.attr("action");
            $options.data = $form.serializeArray();
        }
        // 如果返回结果处理对象元素不存在,则使用参数中的对象
        if (typeof $target != "undefined") {
            $options.target = $options.target ? $options.target : $target;
        }
        // 获取对触发对象的响应事件 - 默认为click
        var $trigger = $options.trigger ? $options.trigger : "click";
        $this.on($trigger, function () {
            ajax = new tz_ajax();
            $status = ajax.type("post").data($options.data).url($options.url).go().updateDom($options.target);
            return false;
        })
        $this.addClass("ajaxFramed").removeAttr("data-ajax-btn");
    })

}

function tz_ajax() {

    this.options = new Object();
    this.defaultTpyes = "&get&GET&post&POST";
    //引用 AmazeUI 的 progress 进度条组件
    //详细使用方法参照 http://amazeui.org/javascript/nprogress

    if (typeof $.AMUI.progress != "undefined") {
        $(document).ajaxStart(function () {
            $.AMUI.progress.start();
        })

        $(document).ajaxStop(function () {
            $.AMUI.progress.done();
        })
    }

    this.type = function ($type) {
        this.options.type = this.defaultTpyes.indexOf($type) > 0 ? $type : "get";
        return this;

    }

    this.url = function ($url) {
        this.options.url = $url;
        return this;
    }

    this.data = function ($data) {
        this.options.data = $data;
        return this;
    }

    this.init = function ($isUpload, $tarDom) {
        $isUpload = $isUpload ? true : false;
        $tarDom = $tarDom ? $tarDom : "input[type='file']";
        if ($isUpload) {
            this.options.contentType = false;
            this.options.processData = false;
            var $formdata = new FormData();
            var $fileObj = $($tarDom).prop('files');
            for (var i = 0; i < $fileObj.length; i++) {
                $formdata.append(i, $fileObj[i]);
            }
            this.options.data = $formdata;
        } else {
            if (typeof this.options.data == "undefined") {
                this.options.data = "";
            } else if (typeof this.options.data != "string") {
                this.options.data = $.param(this.options.data);
            }
        }
    }

    this.upload = function () {
        this.init(true);
        this.send();
        return this.xhr;
    }

    this.go = function () {
        this.init(false);
        this.send();
        return this;
    }

    this.send = function () {
        return this.xhr = $.ajax(this.options);
    }

    this.alert = function () {
        this.xhr.always(function ($r, $s) {
            //alert($s + ":" + $r);
            $news = new tz_news();
            $news.status($s).tips($r);
        })
    }

    this.log = function () {
        this.xhr.always(function ($r, $s) {
            console.log($s + ":" + $r);
        })
    }

    this.updateDom = function ($tarDom) {
        this.xhr.always(function ($r, $s) {
            if ($s == "success") {
                if (typeof $tarDom == "undefined") {
                    $tarDom = $("#extraDom");
                    if ($tarDom.length < 1) {
                        $extra = "<div id='extraDom'></div>";
                        $("body").append($extra);
                    }
                    $tarDom = "#extraDom";
                }
                if($($tarDom).length<1){
                    $("body").append("<div id='"+$tarDom.slice(1,$tarDom.length)+"'></div>");
                }
                $($tarDom).html($r);
                return $s;
            } else {
                if (typeof $r == "string") {
                    alert($r);
                } else {
                    console.log($r);
                }
            }
        })
    }
}

/**
 * 表单验证
 */
function tz_valid() {
    this._form = "";
    this.valid = function () {

    }
}

/**
 * 消息提示 - 以来于amazeui
 */
function tz_news() {

    this._msg = "";
    this._status = "";
    this._id = "";
    this._sign = "";

    this.msg = function ($str) {
        this._msg = $str;
        return this;
    }

    this.status = function ($str) {
        this._status = $str;
        return this;
    }

    this.tips = function ($msg) {
        this.msg($msg);
        this.init("body", "tips");
    }

    this.init = function ($tarDom, $direction) {
        this._id = generateMixed(8);
        if (this._status == "success" || this.stauts == "200") {
            //this._sign = '<span class="am-icon-btn am-success am-icon-check am-text-xl"></span><br>';
        } else {
            this._status = "danger";
            this._sign = '<span class="am-icon-btn am-danger am-icon-exclamation am-text-xl"></span><br>';
            this._msg = '后台断开连接';
        }
        $tarDom = typeof $tarDom == "undefined" ? $tarDom : "body";
        //默认在后面添加
        switch ($direction) {
            case "tips":
                $msg = '<div class="am-modal am-modal-alert" tabindex="-1" id="' + this._id + '"><div class="am-modal-dialog"><div class="am-modal-hd">提示</div><div class="am-modal-bd">' + this._sign + this._msg + '</div><div class="am-modal-footer"><span class="am-modal-btn">确定</span></div></div></div>';
                $("body").append($msg);
                $("#" + this._id).modal();
                break;
            case "append":
            default :
                var $id = this._id;
                $msg = "<span id=" + this._id + " class='am-text-sm am-text-" + this._status + " am-animation-reverse'>" + this._msg + "</span>";
                $($tarDom).parent().append($msg);
        }
        setTimeout(function () {
            $("#" + this._id).addClass("am-animation-fade");
        }, 3000);
        setTimeout(function () {
            $("#" + this._id).remove();
        }, 5000);
    }


    this.pop = function () {
        this.tips("body");
    }
}

function popover_rebuild() {
    $(".am-popover").remove();
    setTimeout(function () {
        $('[data-am-popover]').popover();
    }, 1000);
}

var chars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

function generateMixed(n) {
    var res = "";
    for (var i = 0; i < n; i++) {
        var id = Math.ceil(Math.random() * 35);
        res += chars[id];
    }
    return res;
}






