<?php if (!defined('THINK_PATH')) exit();?><header class="am-topbar am-topbar-inversed" data-am-sticky="{animation: 'slide-top'}">
    <div class="am-g-fixed am-center">
        <h1 class="am-topbar-brand">
            <?php echo ((isset($navTitle) && ($navTitle !== ""))?($navTitle):"RABBIT"); ?>
        </h1>
        <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only"
                data-am-collapse="{target: '#doc-topbar-collapse'}"><span class="am-sr-only">导航切换</span>
            <span class="am-icon-bars"></span></button>
        <div class="am-collapse am-topbar-collapse" id="doc-topbar-collapse">
            <ul class="am-nav am-nav-pills am-topbar-nav">
                <?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['url'] == ucfirst(CONTROLLER_NAME)."/".ACTION_NAME): ?>
                    <li class="am-active">
                        <?php else:?>
                    <li>
                        <?php endif;?>
                        <a href="<?php echo (U($vo["url"])); ?>"><?php echo (__($vo["name"])); ?></a>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="am-topbar-right">
                <ul class="am-nav am-nav-pills am-topbar-nav">
                        <?php echo ($user_board); ?>
                </ul>
            </div>
        </div>
    </div>
</header>