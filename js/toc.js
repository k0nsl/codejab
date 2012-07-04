jQuery(document).ready(function($) {

	if ( $.smoothScroll ) {
		var target = hostname = pathname = qs = hash = null;

		$('body a').click(function(event) {
			hostname = $(this).prop('hostname');
			pathname = $(this).prop('pathname');
			qs = $(this).prop('search');
			hash = $(this).prop('hash');

			if ( pathname.length > 0 ) {
				if ( pathname.charAt(0) != '/' ) {
					pathname = '/' + pathname;
				}
			}
			
			if ( (window.location.hostname == hostname) && (window.location.pathname == pathname) && (window.location.search == qs) && (hash !== '') ) {
				var hash_selector = hash.replace(/([ !"$%&'()*+,.\/:;<=>?@[\]^`{|}~])/g, '\\$1');
				if ( $( hash_selector ).length > 0 )
					target = hash;
				else {
					anchor = hash;
					anchor = anchor.replace('#', '');
					target = 'a[name="' + anchor  + '"]';
					if ( $(target).length == 0 )
						target = '';
				}
				
				if ( target ) {
					event.preventDefault();
					$.smoothScroll({
						scrollTarget: target,
						offset: -30
					});
				}
			}
		});
	}
});
