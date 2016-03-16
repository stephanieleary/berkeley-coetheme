jQuery(document).ready(function( $ ) {
	// console.log($);
	$('#accordion').find('.accordion-toggle').click(function(){

      //Expand or collapse this panel
	  $(this).toggleClass( "activated" );
      $(this).next().toggleClass( "activated" ).slideToggle('fast');

      //Hide the other panels
	  $(".accordion-toggle").not($(this)).removeClass( "activated" );
      $(".accordion-content").not($(this).next()).removeClass( "activated" ).slideUp('fast');

    });
});