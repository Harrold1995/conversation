jQuery(document).ready(function(){
   // jQuery('.show_if_course').each(function(){
   //                  jQuery(this).addClass('show_if_simple');
   //                  jQuery(this).show();
   //              });

   jQuery("#wdm_show_front_option").on("change",function(){
  	if(jQuery("#wdm_show_front_option").is(":checked")){
    		jQuery(".wdm-default-front-option").show();
  	}
  	else{
  		jQuery(".wdm-default-front-option").hide();
  	}
   });

   jQuery("#wdm_show_front_option").trigger('change');

  jQuery("#wdm_ld_group_registration").on("change",function(){
    if(jQuery("#wdm_ld_group_registration").is(":checked")){
        jQuery(".wdm_show_other_option").show();
    }
    else{
      jQuery(".wdm_show_other_option").hide();
    }
  });
   jQuery("#wdm_ld_group_registration").trigger('change');


   // alert(jQuery("#wdm_show_front_option").is(":checked"));

    jQuery("#ldgr_enable_unlimited_members").on("change",function(){
		if(jQuery(this).is(":checked")){
			jQuery(".ldgr-unlimited-group-members-settings").show();
		}
		else{
			jQuery(".ldgr-unlimited-group-members-settings").hide();
		}
 	});

  jQuery('.addel-container').addel({
    events: {
        added: function (event) {
            event.preventDefault();
        }
    }
  });

  jQuery('select[name="ldgr_type_bulk_discount_for_product_setting"]').on('change', function(){
		var $this = jQuery(this);
		if ($this.val() == 'Product') {
			jQuery('.ldgr_bulk_discount_setting_data').show();
		} else {
			jQuery('.ldgr_bulk_discount_setting_data').hide();
		}
	});

  jQuery('select[name="ldgr_type_bulk_discount_for_product_setting"]').trigger('change');


    jQuery('.ldgr_bulk_discount_table').on('change', '.ldgr_bulk_discount_value_validate', function(){
		var $this = jQuery(this);
		var changedValue = $this.val();
		var count = 0;
		jQuery('.ldgr_bulk_discount_value_validate').each(function(i, obj){
      		if(changedValue == jQuery(this).val()) {
				count++;
			}
    	});
		if(count >= 2) {
			jQuery('.ldgr_duplicate_row_rule_error').show();
		} else {
			jQuery('.ldgr_duplicate_row_rule_error').hide();
		}
	});

	if ( typeof ldgr_setup_wizard !== 'undefined' ) {
		if ( ldgr_setup_wizard.enable_group_product ) {
			jQuery( '#wdm_ld_group_registration' ).trigger('click');
			jQuery('html, body').animate({
				scrollTop: jQuery("#wdm_ld_group_registration").offset().top
			}, 'fast');
		}
	}
  
	jQuery('.addel-add').on('click', function(e){
		e.preventDefault();
	})
});
