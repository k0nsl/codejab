function fixLinks() {
	if (jQuery('#shareLink').length) {
		$("#shareLink").click(function(e) {
			$("#shareBox").slideToggle();
			e.preventDefault();
		});
	}
	
	if (jQuery('#embedLink').length) {
		$("#embedLink").click(function(e) {
			$("#embedBox").slideToggle();
			e.preventDefault();
		});
	}

	jQuery('.ajaxLink, .pagingLinks a').unbind('click').click(function(e) {
		var myHref = jQuery(this).attr('href');
		//alert(myHref);
		$.get(myHref, function(r) {
			jQuery("#content").html(jQuery("#content", r).html());
			setTimeout('fixLinks();', 100);
		});
		
		e.preventDefault();
	});

	jQuery(".print").click(function(){
    	jQuery('#theCode').jqprint();
		return false;
	});
	

	$("form").unbind('submit');
	
	if (jQuery('#content #loginForm').length) {
		jQuery('#content #loginForm').ajaxForm({
			success: function(r) {
				if (jQuery("#content", r).length) {
					jQuery("#content").html(jQuery("#content", r).html());
				} else {
					jQuery("#content").html(r);
				}
			
				setTimeout('fixLinks();', 100);
			}
		});
	}
	
	if (jQuery('#content #sgnpForm').length) {
		jQuery('#content #sgnpForm').ajaxForm({
			success: function(r) {
				jQuery("#content").html(jQuery("#content", r).html());
			
				setTimeout('fixLinks();', 100);
			}
		});
	}

	
	$("#searchForm").unbind('submit').submit(function(e) {
		if ($("#searchTerm").val().length) {
			$.get($(this).attr('action') + '/' + $("#searchTerm").val(), function(r) {
				jQuery("#content").html(jQuery("#content", r).html());
				$("#searchTerm").val('');
				setTimeout('fixLinks();', 100);
			});
		}
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
	
	if (jQuery('#searchTerm').length) {
		jQuery('#searchTerm').autocomplete({source: '/autocomplete.php', select: function(event, ui) {
			setTimeout("$('#searchForm').submit();", 100);
		}});
	}
	
	jQuery('input[type=submit],input[type=button]').button();
}

jQuery(document).ready(function() {
	jQuery('input[type=submit],input[type=button]').button();
	fixLinks();	
});
