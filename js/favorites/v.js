jQuery(document).ready(function() {
	jQuery("#shareLink").click(function(e) {
		jQuery("#shareBox").slideToggle();
		e.preventDefault();
	});
	
	jQuery(".favLink").unbind('click').click(function(e) {
		var myLink = this;
		$.get(jQuery(this).attr('href'), function(r) {
			if (r == '0') {
				alert('Error');
			} else {
				jQuery(myLink).text(r);
			}
		});
		e.preventDefault();
	});
});