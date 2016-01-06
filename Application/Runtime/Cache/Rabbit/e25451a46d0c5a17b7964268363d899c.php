<?php if (!defined('THINK_PATH')) exit();?><div class="am-g-fixed am-center am-cf">
    <div class="am-u-sm-4">
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <?php echo ((isset($board["title"]) && ($board["title"] !== ""))?($board["title"]):__("board title")); ?>
            </div>
            <div class="am-list am-list-static">
                <?php $menu = C("MENU.WEBSITE_BOARD_MENU");?>
                <?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="/RabbitCMS/rabbit.php/<?php echo ($vo["url"]); ?>" data-ajax-btn="{target:'#boardContent'}"><?php echo (__($vo["name"])); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>
    <div class="am-u-sm-8">
        <div class="am-panel am-panel-default " id="boardContent">
        </div>
    </div>
</div>