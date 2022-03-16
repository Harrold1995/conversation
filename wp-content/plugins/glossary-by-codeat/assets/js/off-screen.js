jQuery( document ).ready(function($){
	function move_offscreen_if_on_left(window_width, tooltip_left, tooltip_width, element) {
		var tooltip_left_edge = tooltip_left + tooltip_width;
		if( tooltip_left_edge > window_width ) {
			var move_tooltip = tooltip_left_edge - window_width;

			element.css('left', '-' + move_tooltip + 'px');
			jQuery( '<style type="text/css">.move_right::after { left:90%; }</style>').appendTo( 'head' );
			element.addClass( 'move_right' );

			return;
		}
	}

	function move_offscreen_if_on_right(element) {
		var tooltip_left = element.offset().left;
		var window_width = jQuery( window ).width();
		if( Math.sign(tooltip_left) === -1 ) {
			if( ( window_width/2 ) < Math.abs( tooltip_left ) ) {
				jQuery( '<style type="text/css">.move_left::after { left:10%; }</style>').appendTo( 'head' );
				element.addClass( 'move_left' );
			}
			var left = Math.abs(tooltip_left);
			element.css( 'left', left + 'px' );
			element.css( 'left', left + Math.abs(element.offset().left)+ 'px' );
		}
	}

	function move_offscreen( element ) {
		var move_tooltip = '';
		var tooltip_left = element.offset().left;
		if( element.css('transform') !== 'none' ) {
			// Workaround to let JS to parse also the transform values for the position
			element.css('transform', 'translateZ(0)');
		}
		// Reset tooltip position to avoid issue on resize
		if( typeof element.attr( 'original_left' ) === 'undefined' ) {
			element.attr( 'original_left', tooltip_left );
		} else {
			element.css( 'left', element.attr( 'original_left' ) );
		}
		var tooltip_width = element.width();
		var window_width = jQuery( window ).width();

		move_offscreen_if_on_left(window_width, tooltip_left, tooltip_width, element);
	}

	function fix_all_tooltips() {
		jQuery('.glossary-tooltip-content').each( function(){
			move_offscreen( jQuery( this ) );
		});
	}

	if ( window.matchMedia( "(any-hover: hover)") ) {
		fix_all_tooltips();
		jQuery( window ).on('resize', function () {
			fix_all_tooltips();
		});
		jQuery('.glossary-tooltip').on('mouseover', function(){
			move_offscreen_if_on_right( jQuery( this ).find('.glossary-tooltip-content') );
			jQuery(this).off('mouseover');
		});
	}

});
