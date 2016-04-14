<?php
// * Render ACF Map fields
add_action( 'wp_head', 'berkeley_map_scripts' );

function berkeley_map_scripts() {
	if ( is_admin() )
		return;
		
	$location = get_field( 'map' );

	if ( empty( $location ) )
		return;
		
	wp_enqueue_script( 'google-maps-api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', '', '1.0.0', true );
	wp_enqueue_script( 'acf-maps', get_stylesheet_directory_uri() . '/js/acf-maps.js', array( 'google-maps-api' ), '1.0.0', true );
}

// This is now part of berkeley_display_custom_field_content() in content-filters.php
//add_action( 'genesis_entry_footer', 'berkeley_render_map' );

function berkeley_render_map() {
	$location = get_field( 'map' );

	if( !empty( $location ) ):
	?>
	<div class="acf-map">
		<div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
	</div>
	<?php endif;
}