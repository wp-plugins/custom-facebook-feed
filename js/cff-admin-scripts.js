jQuery(document).ready(function() {
	jQuery('#cff-admin .tooltip-link').click(function(){
		jQuery(this).closest('tr').find('.tooltip').slideToggle();
	});
});