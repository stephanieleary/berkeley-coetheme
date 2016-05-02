jQuery(document).ready(function( $ ) {
	// console.log($);
	$('#accordion').find('.accordion-toggle').click(function(){

		//Expand or collapse this panel
		$(this).toggleClass( "activated" );
		$(this).next().toggleClass( "activated" );
		$(this).attr('aria-expanded', function (i, attr) {
		    return attr == 'true' ? 'false' : 'true';
		});

      //Hide the other panels
	  //$(".accordion-toggle").not($(this)).removeClass( "activated" );
      //$(".accordion-content").not($(this).next()).removeClass( "activated" ).slideUp('fast');

    });
});