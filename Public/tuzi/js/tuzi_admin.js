/**
 * Created by Zicokuo on 2015/9/2.
 */

if (typeof jQuery == 'undefined' || jQuery) {
    console.log("jQuery检测通过");
}

$(function(){
    $("[date-ajax-btn]").each(function(){

    })

    var ajaxBtn = function(e){
        var $this = $(this);
        var $options = $this.data("ajax-btn");
    }
})



