jQuery(document).ready(function() {
	
	jQuery('#cff .cff-item').each(function(){
		var $self = jQuery(this);

		//Wpautop fix
		if( $self.find('.cff-viewpost-facebook').parent('p').length ){
			$self.find('.cff-viewpost-facebook').unwrap('p');
		}
		if( $self.find('.cff-author').parent('p').length ){
			$self.find('.cff-author').eq(1).unwrap('p');
			$self.find('.cff-author').eq(1).remove();
		}
		if( $self.find('#cff .cff-link').parent('p').length ){
			$self.find('#cff .cff-link').unwrap('p');
		}

		//Expand post
		var expanded = false,
			$post_text = $self.find('.cff-post-text .cff-text'),
			text_limit = $self.closest('#cff').attr('rel');
		if (typeof text_limit === 'undefined' || text_limit == '') text_limit = 99999;
		
		//If the text is linked then use the text within the link
		if ( $post_text.find('a.cff-post-text-link').length ) $post_text = $self.find('.cff-post-text .cff-text a');
		var	full_text = $post_text.html();
		if(full_text == undefined) full_text = '';
		var short_text = full_text.substring(0,text_limit);
		
		//Cut the text based on limits set
		$post_text.html( short_text );
		//Show the 'See More' link if needed
		if (full_text.length > text_limit) $self.find('.cff-expand').show();
		//Click function
		$self.find('.cff-expand a').unbind('click').bind('click', function(e){
			e.preventDefault();
			var $expand = jQuery(this),
				$more = $expand.find('.cff-more'),
				$less = $expand.find('.cff-less');
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

		//Hide the shared link box if it's empty
		$sharedLink = $self.find('.cff-shared-link');
		if( $sharedLink.text() == '' ){
			$sharedLink.remove();
		}

		//Link hashtags
		if(cfflinkhashtags == 'true'){
			var str = $self.find('.cff-text').html(),
				descstr = $self.find('.cff-post-desc').html(),
				regex = /(?:\s|^)(?:#(?!\d+(?:\s|$)))(\w+)(?=\s|$)/gi,
				linkcolor = $self.find('.cff-text').attr('rel');

			function replacer(hash){
				var replacementString = jQuery.trim(hash);
				return ' <a href="https://www.facebook.com/hashtag/'+ replacementString.substring(1) +'" target="_blank" style="color: #' + linkcolor + '">' + replacementString + '</a>';
			}
			//Replace hashtags in text
			$self.find('.cff-text').html( str.replace( regex , replacer ) );
			//Replace hashtags in desc
			if( $self.find('.cff-post-desc').length ) $self.find('.cff-post-desc').html( descstr.replace( regex , replacer ) );
		}
		
	});
});