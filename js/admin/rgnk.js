jQuery(document).ready(function() {
	if (jQuery('#loginDialog').length) {
		jQuery('#loginDialog').dialog();
	}
	
	if (jQuery('.cancelBtn').length) {
		jQuery('.cancelBtn').click(function() {
			location.href='/admin/' + jQuery(this).attr('rel') + '/';
		});
	}
	
	if (jQuery('#publish').length) {
		jQuery('#publish').change(function() {
			if (jQuery(this).val() == 'DATETIME') {
				jQuery('#publish_DATETIME').show();
			} else {
				jQuery('#publish_DATETIME').hide();
			}
		});
		
		if (jQuery('#publish').val() == 'DATETIME') {
			jQuery('#publish_DATETIME').show();
		} else {
			jQuery('#publish_DATETIME').hide();
		}
	}
	
	if (jQuery('.dpicker').length) {
		jQuery('.dpicker').datepicker();
	}
	
	if (jQuery('#allCheckBox').length) {
		jQuery('#allCheckBox').click(function() {
			myChecked = jQuery('#allCheckBox').attr("checked");
			
			jQuery('td.cbox input').each(function() {
				jQuery(this).attr('checked', myChecked);
			});
		});
	}
	
	jQuery("input[type=button],input[type=submit]").button();
	
	if (jQuery('.b_Feedback,.g_Feedback').length) {
		setTimeout("jQuery('.b_Feedback,.g_Feedback').hide('fade').html('').css('display', 'none');", 3000);
	}
	
	if (jQuery('.delBtn').length) {
		jQuery('.delBtn').click(function(e) {
			if (!confirm('Are you sure you want to delete the selected ' + jQuery(this).attr('rel') + '? This is permanent!')) {
				e.preventDefault();
			}
		});
	}
});