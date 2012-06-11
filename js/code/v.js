jQuery(document).ready(function() {

          jQuery(".print").click(function(e){
                e.preventDefault();
                jQuery('#theCode').jqprint();
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
