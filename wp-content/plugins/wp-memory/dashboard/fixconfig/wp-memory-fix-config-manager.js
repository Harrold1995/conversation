jQuery(document).ready(function ($) {
	/*
	jQuery('html').click(function(e){
		//alert($(e.target).attr("id"));
		 alert(e.target.id+" and "+$(e.target).attr('class'));
	  });
	*/
	var key = $('#verticalmenu_urlkey').val();
	console.log('key: '+key);


	jQuery('#themefix-wpconfig-button').click(function (evt) {
		$modalvmaf = $('#themefix-wpconfig');
		$('#wpwrap').css('opacity', '.1');
		$modalvmaf.css('opacity', '1');
		$('#wpmemory-imagewait20').hide();
		$modalvmaf.prependTo($('body')).slideDown();
	});
	$("#button-cancell-wpconfig").click(function (evt) {
		if ($(".button-cancell-wpconfig").hasClass("disabled")) {
			return;
		}
		$modalvmaf.slideUp();
		$('#wpwrap').css('opacity', '1');
		location.reload();
		return '1';
	});
	$("#button-close-wpconfig").click(function () {
		// submeteu update
		if ($(".button-close-wpconfig").hasClass("disabled")) {
			return;
		}
		$('#wpmemory-imagewait20').show();
		$('#feedback_wpconfig').html("Please, wait ... ");
		var email = $('#email').val();
		var url_config = $('#url_config').val();
		var key = $('#verticalmenu_urlkey').val();
		console.log('key: '+key);
		var qmem = $("#wp_memory_select").val();
		$('#wpmemory-imagewait2').css('visibility', 'visible');
		$(".button-close-wpconfig").addClass('disabled');
		$(".button-cancell-wpconfig").addClass('disabled');

		wpmemory_createCookie('fixconfig', '1', '30')


		$.ajax({
			url: url_config,
			withCredentials: true,
			timeout: 60000,
			method: 'POST',
			crossDomain: true,
			data: {
				"email": email,
				"qmem": qmem,
				"verticalmenu_urlkey": key,
				"url_config": url_config
			},
			success: function (data) {
				$('#wpmemory-imagewait20').hide();
				result = data;
				if (result == 'WP-CONFIG.PHP File updated!') {
					result = result + '\n';
					var messageundo = 'Please keep this window opened until testing the site in another window. Use the address above to DISCARD last changes. Just copy and paste it at your browser.';
					result = result + messageundo;
					$('#feedback_wpconfig').html(result);
					$('#feedback_wpconfig').css("background", "yellow");
					alert('Just in case, we sent an email with the link to you. Check also your spam folder.')
				} else {
					$('#feedback_wpconfig').html(result);
				}
				$(".button-cancell-wpconfig").removeClass('disabled');
				$(".button-cancell-wpconfig").html('Close');
			},
			error: function (xhr, status, error) {

				if(error == 'Internal Server Error')
				{
					var result = "Unable to execute the file wp-memory-fix-config-manager.inc (path: wpmemory/dashboard/fixconfig/). Please talk with your hosting company to unblock it.";

				}
				else
				{

					result = 'Error occured, please try again later.';
				}
					//alert(error);
					// console.log(error);
				$('#wpmemory-imagewait20').hide();
				$('#feedback_wpconfig').html(result);
				$(".button-cancell-wpconfig").removeClass('disabled');
				$(".button-cancell-wpconfig").html('Close');
			},
			completed: function () {
			}
		}); // end ajax  
	}); // fix-wpconfig


    function wpmemory_createCookie(name, value, days) {
        var expires;
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
    }




}); // jquery