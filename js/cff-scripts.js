jQuery(document).ready(function() {

	//Wpautop fix
	if( jQuery('.cff-viewpost').parent('p').length ){
		jQuery('.cff-viewpost').unwrap('p');
	}
	if( jQuery('#cff .link').parent('p').length ){
		jQuery('#cff .link').unwrap('p');
	}

	//Expand post
	jQuery('.cff-item').each(function(){
		var $self = jQuery(this),
			expanded = false,
			$post_text = $self.find('.cff-post-text span');
		//If the text is linked then use the text within the link
		if ( $post_text.find('a.cff-post-text-link').length ) $post_text = $self.find('.cff-post-text span a');
		var	full_text = $post_text.html(),
			short_text = $post_text.html().substring(0,text_limit);
		
		//Cut the text based on limits set
		$post_text.html( short_text );
		//Append link to end of text
		if (full_text.length > text_limit) $post_text.after('... <a class="cff-expand" href="#">See More</a>');

		//Click function
		$self.find('.cff-expand').click(function(e){
			e.preventDefault();
			var $expand = jQuery(this);

			if (expanded == false){
				$post_text.html( full_text );
				expanded = true;
				$expand.text('See Less');
			} else {
				$post_text.html( short_text );
				expanded = false;
				$expand.text('See More');
			}
		});
	});

	console.log(text_limit);

});