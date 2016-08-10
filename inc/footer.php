<?php

// options page
if ( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page( array(
		'page_title' 	=> esc_html__('Footer Contents', 'berkeley-coe-theme'),
		'menu_title'	=> esc_html__('Footer Contents', 'berkeley-coe-theme'),
		'menu_slug' 	=> 'berkeley-footer-settings',
		'parent_slug' 	=> 'genesis',
		'capability'	=> 'edit_pages',
		'redirect'		=> false
	) );
	
}

// Add menu location in footer
function berkeley_register_footer_menu() {
	register_nav_menu( 'footer-menu', esc_html__( 'Footer Navigation Menu', 'berkeley-coe-theme' ) );
}
add_action( 'init', 'berkeley_register_footer_menu' );

// replace footer text
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'berkeley_custom_footer' );

function berkeley_custom_footer() {
	wp_nav_menu( array( 
		'theme_location' => 'footer-menu', 
		'container_class' => 'genesis-nav-menu', 
		'menu_class' => 'footer-menu' 
	) );
	
	/*
	This is the hackiest hack that ever did hack.
	We're repeating the menu so, for mobile, we can display only the non-social icon items from the first copy,
	then display ONLY the social icons for the second copy. 
	Stupid workaround for the lack of line breaks in inline-block lists.
	*shakes fist at CSS*  
	*/
	
	wp_nav_menu( array( 
		'theme_location' => 'footer-menu', 
		'container_class' => 'genesis-nav-menu copy', 
		'menu_class' => 'footer-menu' 
	) );
	
	echo '<div class="footer-content">';
	echo do_shortcode( get_field( 'footer_text', 'option' ) );
	echo '</div><!-- end .footer-content -->';
}

//* Add support for 3 rows (not columns!) of footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Move the footer widgets inside the footer area instead of just above it
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
add_action( 'genesis_footer', 'genesis_footer_widget_areas', 6 );

//* Add count class to footer widgets
// And add a note about 3-widget limit to widget description. 
add_filter( 'dynamic_sidebar_params', 'berkeley_widget_count_params' );
function berkeley_widget_count_params( $params ) {
	if ( is_admin() )
		return $params;
		
	$sidebar_id = $params[0]['id'];
	if ( !in_array( $sidebar_id, array( 'footer-1', 'footer-2', 'footer-3' ) ) )
		return $params;
	
	$widget_id = $params[0]['widget_id'];
	$all_widgets = wp_get_sidebars_widgets();
	$this_widget_area = count( $all_widgets[$sidebar_id] );
	switch ( $this_widget_area ) {
		case '3': $width_class = ' one-third '; break;
		case '2': $width_class = ' one-half '; break;
		default:  $width_class = ' full-width '; break;
	}
	
	$this_area_index = array_search( $widget_id, $all_widgets[$sidebar_id] );
	$this_area_index++;
	
	if ( $sidebar_id == 'footer-1' ) {
		$index_class = ' index-' . $this_area_index;
	}
	
	if ( $sidebar_id == 'footer-2' ) {
		$index = $this_area_index + count( $all_widgets['footer-1'] );
		$index_class = ' index-' . $index;
	}
	
	if ( $sidebar_id == 'footer-3' ) {
		$index = $this_area_index + count( $all_widgets['footer-1'] ) + count( $all_widgets['footer-2'] );
		$index_class = ' index-' . $index;
	}
	
	$params[0]['before_widget'] = str_replace( 'class="widget ', 'class="widget ' . $width_class . $index_class . ' ', $params[0]['before_widget'] );
	
	$params[0]['description'] .= esc_html__(' Limited to three widgets.', 'berkeley-coe-theme');
	return $params;
}

//* Limit footer widgets to 3 items
add_action( 'admin_enqueue_scripts', 'berkeley_enqueue_limit_sidebars_scripts' );
function berkeley_enqueue_limit_sidebars_scripts( $hook_suffix ) {
    if ( 'widgets.php' == $hook_suffix ) {
        wp_enqueue_script( 'berkeley-limit-sidebar-widgets', get_stylesheet_directory_uri() . '/js/limit-sidebar-widgets.js', array(), false, true );
    }
}