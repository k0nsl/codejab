jQuery(document).ready(function() {
	if (jQuery('.deleteTagLink').length) {
		jQuery('.deleteTagLink').click(function(e) {
			var myTagId = jQuery(this).attr('href');
			var myRefId = jQuery(this).attr('rel');
			
			jQuery.get('/tags/delete/' + myTagId, function(r) {
				if (r == '1') {
					jQuery('#tag_' + myTagId).hide().html('').removeClass('tag');
					
					if (jQuery('#tagContainer' + myRefId + ' .tag').length == 0) {
						jQuery('#tagContainer' + myRefId).html('<span class="errors">No Tags To Display</span>');
					}
				}
			});
			e.preventDefault();
		});
	}
});