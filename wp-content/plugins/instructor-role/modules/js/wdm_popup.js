function toggle(div_id)
{
	var el = document.getElementById( div_id );
	if ( el.style.display == 'none' ) {
		el.style.display = 'block';} else {
		el.style.display = 'none';}
}
function blanket_size(popUpDivVar)
{
	if (typeof window.innerWidth != 'undefined') {
		viewportheight = window.innerHeight;
	} else {
		viewportheight = document.documentElement.clientHeight;
	}
	if ((viewportheight > document.body.parentNode.scrollHeight) && (viewportheight > document.body.parentNode.clientHeight)) {
		blanket_height = viewportheight;
	} else {
		if (document.body.parentNode.clientHeight > document.body.parentNode.scrollHeight) {
			blanket_height = document.body.parentNode.clientHeight;
		} else {
			blanket_height = document.body.parentNode.scrollHeight;
		}
	}
	var blanket          = document.getElementById( 'blanket' );
	blanket.style.height = blanket_height + 'px';
	var popUpDiv         = document.getElementById( popUpDivVar );
	popUpDiv_height      = blanket_height / 2 - 200;// 200 is half popup's height
	// popUpDiv.style.top = popUpDiv_height + 'px';
		// popUpDiv.style.top = '12%';
		var win_height     = jQuery( window ).height();
		var per            = (25 / 100) * win_height;
		popUpDiv.style.top = (jQuery( window ).scrollTop() + per ) + 'px';

		// alert( jQuery(window).scrollTop() );
}

function window_pos(popUpDivVar)
{
	if (typeof window.innerWidth != 'undefined') {
		viewportwidth = window.innerHeight;
	} else {
		viewportwidth = document.documentElement.clientHeight;
	}
	if ((viewportwidth > document.body.parentNode.scrollWidth) && (viewportwidth > document.body.parentNode.clientWidth)) {
		window_width = viewportwidth;
	} else {
		if (document.body.parentNode.clientWidth > document.body.parentNode.scrollWidth) {
			window_width = document.body.parentNode.clientWidth;
		} else {
			window_width = document.body.parentNode.scrollWidth;
		}
	}
	var popUpDiv = document.getElementById( popUpDivVar );
	window_width = window_width / 2 - 200;// 200 is half popup's width
		// window_width=window_width/2 - 500;
	// popUpDiv.style.left = window_width + 'px';
		popUpDiv.style.left = '17%';
}
function popup(windowname)
{
	blanket_size( windowname );
	window_pos( windowname );
	toggle( 'blanket' );
	toggle( windowname );
}

/*
@since 2.1
 --- For other user */
jQuery( "document" ).ready(
	function () {

		jQuery( ".wdmir-email-heading" ).each(
			function () {
				jQuery( this ).find( ".wdmir-shortcode-callback" ).css( "left", jQuery( this ).find( ".heading" ).css( "width" ) );
			}
		);

		jQuery( ".wdmir-shortcode-close" ).click(
			function () {
				jQuery( this ).closest( ".wdmir-shortcode-callback" ).slideUp();

			}
		);

		jQuery( ".wdmir-shortcodes" ).click(
			function () {
				jQuery( this ).next( ".wdmir-shortcode-callback" ).slideDown();
			}
		);

	}
);
