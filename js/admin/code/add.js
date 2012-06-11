jQuery(document).ready(function() {
	jQuery("input[name=uploadCode]").click(function() {
		var myVal = jQuery(this).val();
		
		if (myVal == 1) {
			jQuery('#codeUpld').show();
			jQuery('#codeArea').hide();
		} else {
			jQuery('#codeUpld').hide();
			jQuery('#codeArea').show();
		}
	});
	
	jQuery("input[name=doesExpire]").click(function() {
		var myVal = jQuery(this).val();
		
		if (myVal == 1) {
			jQuery('#expContainer').show();
		} else {
			jQuery('#expContainer').hide();
		}
	});
	
	var myVal = jQuery('input[name=uploadCode]:checked').val();
		
	if (myVal == 1) {
		jQuery('#codeUpld').show();
		jQuery('#codeArea').hide();
	} else {
		jQuery('#codeUpld').hide();
		jQuery('#codeArea').show();
	}
	
	var myVal = jQuery('input[name=doesExpire]:checked').val();
		
	if (myVal == 1) {
		jQuery('#expContainer').show();
	} else {
		jQuery('#expContainer').hide();
	}
	
	$("#expires").datetimepicker({
		showSecond: true,
		timeFormat: 'hh:mm:ss'
	});
});