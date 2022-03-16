/**
 * @ Author: Bill Minozzi
 * */
 jQuery(document).ready(function ($) {
     // console.log('ok!!!!!');
     jQuery(".wptools_bill_go_pro_dismiss").click(function(event) {
        //  alert('xxxx');
        // console.log('clicou!');
        jQuery("#wp-memory-banner").css("display", "none");
        jQuery.cookie("wpmemory_bill_go_pro_hide", "true", {
            expires: 7,
            secure: 1
        });
     } )
});