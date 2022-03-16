export default class Dashboard {

	tabs(){
		jQuery(document).on('click', '.ldgr-tabs li', function(){
			var attr = jQuery(this).attr('data-name');
			jQuery(this).parent().find('li').removeClass('current');
			jQuery(this).addClass('current');
			jQuery('.ldgr-tabs-content > div').removeClass('current');
			jQuery('.ldgr-tabs-content').find('[data-name='+attr+']').addClass('current');	
		})
	}


	toggleCheckbox(){
		jQuery(document).on('click', '.empty-bg', function(){
			jQuery(this).parent().toggleClass('enabled');
			jQuery(this).trigger('checkboxToggle');
		})
	}


	searchfromList(){
		jQuery(".ldgr-search").on("keyup", function() {
		    var value = jQuery(this).val().toLowerCase();
		    jQuery(this).parents('.ldgr-search-list-wrap').find('.ldgr-chk-item').filter(function() {
		      jQuery(this).toggle(jQuery(this).text().toLowerCase().indexOf(value) > -1)
		    });
	  	});
	}


	scrolToElement(){
		jQuery(document).on( 'click', '.ldgr-alphabets span', function(){
			var key = jQuery(this).text();
			var list = jQuery(this).parents('.ldgr-search-list-wrap').find('.ldgr-list');
			var element = list.find('input[data-name^='+key.toLowerCase()+']');
			if(element.length){
				var elementWrap = element.parents('.ldgr-chk-item')[0];
				var offset = elementWrap.offsetTop - elementWrap.parentNode.offsetTop;
				list.animate({scrollTop: offset}, 200);	
			}
			
		})
	}

	replaceContent(trigger_element, hide_element, show_element){
		jQuery(document).on('click', trigger_element, function(){
			jQuery(hide_element).hide();
			jQuery(show_element).show();
		})
	}

	openLightbox(trigger_element, show_element){
		jQuery(trigger_element).on('click', function(){
			jQuery(show_element).css('display', 'flex');
		})
	}

	closeLightbox(trigger_element, hide_element){
		jQuery(trigger_element).on('click', function(){
			jQuery(hide_element).hide();
		})
	}

	closePopupOutsideClick(){
		jQuery('.ldgr-lightbox').on('click', function(e) {
			if (!jQuery(e.target).closest('.ldgr-popup').length){
	        	jQuery('.ldgr-lightbox').hide();
	    	}
		});
	}

	addMoreUsers(){
		jQuery(document).on('click', '.ldgr-add-more-users', function(){
			jQuery('.ldgr-tabs-content .ldgr-add-users').append(ldgr_dashboard_loc.row_html);	
		})
				    
	}

	removeUsers(){
		jQuery(document).on('click', '.remove-user', function(){
			jQuery(this).parent().remove();
		})
	}

}
