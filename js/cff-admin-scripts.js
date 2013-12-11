jQuery(document).ready(function() {
	
	//Tooltips
	jQuery('#cff-admin .cff-tooltip-link').click(function(){
		jQuery(this).closest('tr').find('.cff-tooltip').slideToggle();
	});

	//Check Access Token length
	jQuery("#cff_access_token").change(function() {

		var cff_token_string = jQuery('#cff_access_token').val(),
			cff_token_check = cff_token_string.indexOf('|');

  		if ( (cff_token_check == -1) && (cff_token_string.length < 50) && (cff_token_string.length !== 0) ) {
  			jQuery('.cff-profile-error.cff-access-token').fadeIn();
  		} else {
  			jQuery('.cff-profile-error.cff-access-token').fadeOut();
  		}

	});

	//Is this a page, group or profile?
	var cff_page_type = jQuery('.cff-page-type select').val(),
		$cff_page_type_options = jQuery('.cff-page-options'),
		$cff_profile_error = jQuery('.cff-profile-error.cff-page-type');

	//Should we show anything initially?
	if(cff_page_type !== 'page') $cff_page_type_options.hide();
	if(cff_page_type == 'profile') $cff_profile_error.show();

	//When page type is changed show the relevant item
	jQuery('.cff-page-type').change(function(){
		cff_page_type = jQuery('.cff-page-type select').val();

		if( cff_page_type !== 'page' ) {
			$cff_page_type_options.fadeOut(function(){
				if( cff_page_type == 'profile' ) {
					$cff_profile_error.fadeIn();
				} else {
					$cff_profile_error.fadeOut();
				}
			});
			
		} else {
			$cff_page_type_options.fadeIn();
			$cff_profile_error.fadeOut();
		}
	});

});