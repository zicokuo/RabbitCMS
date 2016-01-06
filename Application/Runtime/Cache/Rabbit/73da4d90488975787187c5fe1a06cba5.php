<?php if (!defined('THINK_PATH')) exit();?><h2 class="am-text-center"><?php echo ($title); ?></h2>
<form class="am-form" method="post" action="/RabbitCMS/rabbit.php/Index/website_information">
    <fieldset>
        <?php if(is_array($informations)): $i = 0; $__LIST__ = $informations;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="am-form-group">
                <label class="am-form-label">
                    <?php echo (__($vo["key"],"_")); ?>
                </label>
                <?php switch($vo["type"]): case "text": ?><input name="<?php echo ($vo["key"]); ?>" class="am-form-field" value="<?php echo ($vo["value"]); ?>"/><?php break;?>
                    <?php case "textarea": ?><textarea name="<?php echo ($vo["key"]); ?>" rows="3" class="am-form-field" value="<?php echo ($vo["value"]); ?>"></textarea><?php break;?>
                    <?php case "switch": ?><div class="am-form-group">
                            <label class="am-checkbox-inline">
                                <input type="radio" name="<?php echo ($vo["key"]); ?>" value="1" data-am-ucheck
                                <?php if(vo.value == '1'): ?>checked<?php endif; ?>
                                />
                                开启
                            </label>
                            <label class="am-checkbox-inline">
                                <input type="radio" name="<?php echo ($vo["key"]); ?>" value="0" data-am-ucheck
                                <?php if($vo["value"] == '0'): ?>checked<?php endif; ?>
                                />
                                关闭
                            </label>
                        </div><?php break; endswitch;?>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
        <div class="am-form-group">
            <label>
            </label>
        </div>
        <button type="submit" name="submit" href="/RabbitCMS/rabbit.php/Index/website_information" class="am-btn am-btn-success am-u-sm-3" data-ajax-btn="{target:'#alert'}">
            <?php echo (L("submit")); ?>
        </button>
    </fieldset>
</form>