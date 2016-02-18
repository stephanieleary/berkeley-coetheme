( function( window, $, undefined ) {
'use strict';
 
// Add specific toggles to specific elements:
$( ".nav-primary" ).before( $( '#primary-toggle' ) );
$( 'nav .sub-menu' ).before( '<button class="sub-menu-toggle" role="button" aria-pressed="false"></button>' ); 
$( '.header-widget-area' ).after( '<button class="search-toggle" id="search-toggle" role="button" aria-pressed="false"></button>' ); 


// Show/hide the navigation
$( '.menu-toggle, .sub-menu-toggle, .search-toggle' ).on( 'click', function() {
	var $this = $( this );
	$this.attr( 'aria-pressed', function( index, value ) {
		return 'false' === value ? 'true' : 'false';
	});
 
	$this.toggleClass( 'activated' );
	
	if ( $this.attr("id") == "search-toggle" ) {
 		$this.prev( '.header-widget-area' ).slideToggle( { direction: "up" }, 'fast' );
	}
	else {
		console.log( $this.attr("id") );
		$this.next( 'nav, .sub-menu' ).slideToggle( 'fast' );
	}
	
	});
 
})( this, jQuery );