jQuery(document).ready(function() {
	var changePass = jQuery('input[name=changePass]:checked').val();
	
	if (changePass == 1) {
		jQuery('#passwordContainer').show();
	} else {
		jQuery('#passwordContainer').hide();
	}
	
	jQuery('input[name=changePass]').change(function() {
		var changePass = jQuery('input[name=changePass]:checked').val();
		
		if (changePass == 1) {
			jQuery('#passwordContainer').show();
		} else {
			jQuery('#passwordContainer').hide();
		}
	});
});