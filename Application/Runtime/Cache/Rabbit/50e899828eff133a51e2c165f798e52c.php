<?php if (!defined('THINK_PATH')) exit();?><section id="framePop"></section>
<div class="am-footer am-footer-default">
    <a class="" href="/RabbitCMS/rabbit.php/Admin/what_new" data-ajax-btn>
        <i class="am-icon-exclamation-triangle am-icon-fw"></i>
    </a>Public By Zico. @2015
</div>
<script type="text/javascript">
    $(function () {
        framePop = $("#framePop");
        if (framePop.length != 1) {
            framePop.remove();
            $("body").append("<section id='framePop'></section>");
        }
        $("[data-ajax-btn]").ajaxFrame("#framePop");

        $(document).ajaxComplete(function () {
            $("[data-ajax-btn]").ajaxFrame("#framePop");
            $("[data-am-popover]").popover('close').popover();
            $("[data-am-selected]").selected();
            $(".am-datepicker").remove();
            $("[data-am-datepicker]").datepicker();
            $("input[type='checkbox'], input[type='radio']").uCheck();
        })
    })
</script>
</body>
</html>