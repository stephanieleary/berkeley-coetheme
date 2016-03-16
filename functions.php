<?php
//* Start the engine
include_once( get_template_directory() 	 . '/lib/init.php' );
include_once( get_stylesheet_directory() . '/inc/announcements.php' );
include_once( get_stylesheet_directory() . '/inc/content-filters.php' );
include_once( get_stylesheet_directory() . '/inc/editor.php' );
include_once( get_stylesheet_directory() . '/inc/footer.php' );
include_once( get_stylesheet_directory() . '/inc/loops.php' );
include_once( get_stylesheet_directory() . '/inc/maps.php' );
include_once( get_stylesheet_directory() . '/inc/sidebars.php' );
include_once( get_stylesheet_directory() . '/inc/theme-options.php' );
include_once( get_stylesheet_directory() . '/inc/widgets.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Berkeley Engineering Theme' );
define( 'CHILD_THEME_URL', 'http://www.stephanieleary.com/' );
// based on Genesis version:
define( 'CHILD_THEME_VERSION', '2.1.2' ); 

//* Add Fonts
add_action( 'wp_head', 'berkeley_fonts' );

function berkeley_fonts() {
	if ( !is_admin() )
		echo "<link href='https://fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Source+Serif+Pro:400,700' rel='stylesheet' type='text/css'>";
}

add_action( 'after_setup_theme', 'berkeley_setup_theme' );

function berkeley_setup_theme() {
	remove_theme_support( 'genesis-custom-header' );
	add_theme_support( 'genesis-style-selector', berkeley_get_colors() );
	$colors = genesis_get_option( 'style_selection' );
	switch ( $colors ) {
		case 'pool':
		case 'pacific':
			$text = 'ffffff';
			break;
		case 'punch':
		case 'classic':
			$text = 'fdb515';	// gold
			break;
		case 'punch light':
			$text = '3b7ea1';  // founders-rock
			break;
		case 'earth':
			$text = 'ddd5c7';	// bay fog
			break;
		case 'woods light':
			$text = '584f29';	// stone pine
			break;
		case 'earth light':
			$text = '6c3302';	// south hall
			break;
		default: 
			$text = '003262';	// blue
			break;
	}
	
	$headers = array(
        'default-image'      => get_stylesheet_directory_uri() . 'images/header.png',
        'default-text-color' => $text,
        'width'              => 800,
        'height'             => 240,
        'flex-width'         => true,
        'flex-height'        => true,
		'random-default'	 => false,
		'wp-head-callback'	 => 'berkeley_header_body_classes',
    );
    add_theme_support( 'custom-header', $headers );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
	add_theme_support( 'genesis-responsive-viewport' );
	add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu', 'search-form', 'skip-links', 'rems' ) );
}

function berkeley_header_body_classes() {
	add_filter( 'body_class', 'berkeley_header_style' );
}

function berkeley_header_style( $classes ) {
     if ( HEADER_TEXTCOLOR == get_header_textcolor() && '' == get_header_image() )
        return $classes;

	if ( 'blank' == get_header_textcolor() )
		$classes[] = 'custom-header-hide-text';
	
	return $classes;
}

add_action( 'genesis_site_title', 'berkeley_header_image', 2 );

function berkeley_header_image() {
	$header_image = get_header_image();
	if ( ! empty( $header_image ) ) : 
		printf( '<a href="%s"><img id="custom-header" src="%s" alt="%s" /></a>', esc_url( get_option( 'home' ) ), esc_url( $header_image ), get_option( 'blogname' ) );
	endif;
}

function berkeley_get_colors() {
	return array( 
		'pool'			=> __( 'Pool' ), 
		'pool light'	=> __( 'Pool Light' ),
		'punch'			=> __( 'Punch' ), 
		'punch light'	=> __( 'Punch Light' ),
		'classic'		=> __( 'Classic' ), 
		'classic light'	=> __( 'Classic Light' ), 
		'earth'			=> __( 'Earth' ), 
		'earth light'	=> __( 'Earth Light' ), 
		'woods'			=> __( 'Woods' ),
		'woods light'	=> __( 'Woods Light' ), 
		'pacific'		=> __( 'Pacific' ),
		'pacific light'	=> __( 'Pacific Light' ),
	);
}

// Color schemes
function berkeley_get_color_stylesheet( $color ) {
	if ( !isset( $color ) )
		return;
		
	$color = str_replace( ' light', '', $color );
	if ( 'pool' == $color )
		return;

	return get_stylesheet_directory_uri() . '/css/color-' . $color . '.css';
}

//* Add scripts
add_action( 'wp_enqueue_scripts', 'berkeley_enqueue_files', 1 );

function berkeley_enqueue_files() {
	if ( is_admin() )
		return;
		
	// Enqueue responsive menu script
	wp_enqueue_script( 'berkeley-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true );
	
	// Enqueue color scheme stylesheet
	$color = genesis_get_option( 'style_selection' );
	$path = berkeley_get_color_stylesheet( $color );
	if ( !empty( $path ) )
		wp_enqueue_style( 'berkeley-'.$color , $path );
	
	// Enqueue accordion js for Additional Content fields on single templates
	if ( is_singular() )
		wp_enqueue_script( 'berkeley-accordion', get_stylesheet_directory_uri() . '/js/accordion.js', array( 'jquery' ), false, true );
}

// Add menu toggle buttons with specific IDs
add_action( 'genesis_after_header', 'berkeley_menu_buttons', 99 );
function berkeley_menu_buttons() {
	echo '<button id="secondary-toggle" class="menu-toggle" role="button" aria-pressed="false"></button>';
	echo '<button id="primary-toggle" class="menu-toggle" role="button" aria-pressed="false"></button>';
}