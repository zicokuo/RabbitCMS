<?php if (!defined('THINK_PATH')) exit();?><li class="am-dropdown" data-am-dropdown>
    <a class="am-dropdown-toggle">
        <?php if(empty($user["avatar"])): ?><i class="am-icon-user am-icon-fw"></i>
            <?php else: ?>
            <img src="<?php echo ($user["avatar"]); ?>" class="am-icon-fw am-text-xxl"/><?php endif; ?>
        <i class="am-icon-exclamation-circle am-text-danger am-fr am-text-sm"></i>
        <?php echo ($user["nick"]); ?>
    </a>

    <div class="am-dropdown-content ">
        <?php if(empty($user["avatar"])): ?><i class="am-icon-user am-fl am-icon-fw am-text-xxxl"></i>
            <?php else: ?>
            <img src="<?php echo ($user["avatar"]); ?>" class="am-fl am-icon-fw am-text-xxxl"/><?php endif; ?>
        <dt class="am-text-center">
            <?php echo ($user["nick"]); ?>
            <br>
            <span class="am-badge am-badge-secondary"><?php echo ((isset($user["identName"]) && ($user["identName"] !== ""))?($user["identName"]):"访客"); ?></span>
        </dt>
        <hr>
        <?php if($user["nick"] == '访客'): ?><!--登陆-->
            <a href="/RabbitCMS/rabbit.php/User/user_enter" class="am-btn am-btn-primary am-block">
                <i class="am-icon-sign-in am-icon-fw"></i>用户登陆
            </a>
            <?php else: ?>
            <!--用户中心-->
            <a href="/RabbitCMS/rabbit.php/User/index">
                <i class="am-icon-user-md am-icon-fw"></i>用户中心
            </a><br>
            <a href="/RabbitCMS/rabbit.php/User/user_avatar" data-ajax-btn='{target:"#user_avatar_change"}'>
                <i class="am-icon-camera am-icon-fw"></i>更换头像
            </a><br>
            <!--登出-->
            <a href="/RabbitCMS/rabbit.php/User/user_enter?subMod=logout">
                <i class="am-icon-sign-out am-icon-fw"></i>登出
            </a><?php endif; ?>
    </div>
</li>