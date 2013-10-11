jQuery(document).ready(function() {

	//Wpautop fix
	if( jQuery('.cff-viewpost').parent('p').length ){
		jQuery('.cff-viewpost').unwrap('p');
	}
	if( jQuery('#cff .link').parent('p').length ){
		jQuery('#cff .link').unwrap('p');
	}
	
	jQuery('#cff .cff-item').each(function(){
		//Expand post
		var $self = jQuery(this),
			expanded = false,
			$post_text = $self.find('.cff-post-text .text'),
			text_limit = $self.closest('#cff').attr('rel');
		if (typeof text_limit === 'undefined' || text_limit == '') text_limit = 99999;
		
		//If the text is linked then use the text within the link
		if ( $post_text.find('a.cff-post-text-link').length ) $post_text = $self.find('.cff-post-text .text a');
		var	full_text = $post_text.html();
		if(full_text == undefined) full_text = '';
		var short_text = full_text.substring(0,text_limit);
		
		//Cut the text based on limits set
		$post_text.html( short_text );
		//Show the 'See More' link if needed
		if (full_text.length > text_limit) $self.find('.cff-expand').show();
		//Click function
		$self.find('.cff-expand a').click(function(e){
			e.preventDefault();
			var $expand = jQuery(this),
				$more = $expand.find('.more'),
				$less = $expand.find('.less');
			if (expanded == false){
				$post_text.html( full_text );
				expanded = true;
				$more.hide();
				$less.show();
			} else {
				$post_text.html( short_text );
				expanded = false;
				$more.show();
				$less.hide();
			}
		});
	});

});