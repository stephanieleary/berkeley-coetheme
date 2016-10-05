<?php
// Start the engine
include_once( get_template_directory() 	 . '/lib/init.php' );
include_once( get_stylesheet_directory() . '/inc/announcements.php' );
include_once( get_stylesheet_directory() . '/inc/content-filters.php' );
include_once( get_stylesheet_directory() . '/inc/customizer.php' );
include_once( get_stylesheet_directory() . '/inc/editor.php' );
include_once( get_stylesheet_directory() . '/inc/footer.php' );
include_once( get_stylesheet_directory() . '/inc/header.php' );
include_once( get_stylesheet_directory() . '/inc/image-sizes.php' );
include_once( get_stylesheet_directory() . '/inc/loops.php' );
include_once( get_stylesheet_directory() . '/inc/metaboxes.php' );
include_once( get_stylesheet_directory() . '/inc/sidebars.php' );
include_once( get_stylesheet_directory() . '/inc/theme-options.php' );
include_once( get_stylesheet_directory() . '/inc/widgets.php' );

// Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Berkeley CoE Theme' );
define( 'CHILD_THEME_URL', 'http://www.stephanieleary.com/' );
// based on Genesis version:
define( 'CHILD_THEME_VERSION', '2.2.7' ); 

// Content Width
if ( ! isset( $content_width ) )
    $content_width = apply_filters( 'content_width', 900, 600, 900 );

// Add Fonts
function berkeley_theme_fonts_url() {
	$fonts_url = '';
 
	/* Translators: If there are characters in your language that are not
	* supported by Lora, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$lato = _x( 'on', 'Lato font: on or off', 'berkeley-coe-theme' );
 
	/* Translators: If there are characters in your language that are not
	* supported by Open Sans, translate this to 'off'. Do not translate
	* into your own language.
	*/
	$source_serif = _x( 'on', 'Source Serif font: on or off', 'berkeley-coe-theme' );
 
	if ( 'off' !== $lato || 'off' !== $source_serif ) {
		$font_families = array();
 
		if ( 'off' !== $lato ) {
			$font_families[] = 'Lato:400,400italic,700,700italic';
		}
 
		if ( 'off' !== $source_serif ) {
			$font_families[] = 'Source+Serif+Pro:400,700';
		}
 
		$query_args = array(
			'family' => implode( '|', $font_families ),
			'subset' => 'latin,latin-ext',
		);
 
		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}
 
	return esc_url_raw( $fonts_url );
}

// Theme Setup
add_action( 'after_setup_theme', 'berkeley_setup_theme', 5 );

function berkeley_setup_theme() {
	add_image_size( 'berkeley-small', 300, 300 );  // see also inc/image-sizes.php
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
        'default-image'      => '',
        'default-text-color' => $text,
        'width'              => 800,
        'height'             => 240,
        'flex-width'         => true,
        'flex-height'        => true,
		'random-default'	 => false,
		'wp-head-callback'	 => 'berkeley_header_body_classes',
    );
    add_theme_support( 'custom-header', $headers );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
	add_theme_support( 'genesis-responsive-viewport' );
	add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu', 'search-form', 'skip-links', 'rems' ) );
}

// Color schemes

function berkeley_get_colors() {
	return array( 
		'pool'			=> esc_html__( 'Pool', 		  	'berkeley-coe-theme' ), 
		'pool light'	=> esc_html__( 'Pool Light', 	'berkeley-coe-theme' ),
		'punch'			=> esc_html__( 'Punch', 		'berkeley-coe-theme' ), 
		'punch light'	=> esc_html__( 'Punch Light',   'berkeley-coe-theme' ),
		'classic'		=> esc_html__( 'Classic', 	  	'berkeley-coe-theme' ), 
		'classic light'	=> esc_html__( 'Classic Light', 'berkeley-coe-theme' ), 
		'earth'			=> esc_html__( 'Earth', 		'berkeley-coe-theme' ), 
		'earth light'	=> esc_html__( 'Earth Light',   'berkeley-coe-theme' ), 
		'woods'			=> esc_html__( 'Woods', 		'berkeley-coe-theme' ),
		'woods light'	=> esc_html__( 'Woods Light',   'berkeley-coe-theme' ), 
		'pacific'		=> esc_html__( 'Pacific', 	  	'berkeley-coe-theme' ),
		'pacific light'	=> esc_html__( 'Pacific Light', 'berkeley-coe-theme' ),
	);
}

function berkeley_get_color_stylesheet( $color ) {
	if ( !isset( $color ) )
		return;
		
	$color = str_replace( ' light', '', $color );
	if ( 'pool' == $color )
		return;

	return get_stylesheet_directory_uri() . '/css/color-' . $color . '.css';
}

// Add scripts

add_action( 'admin_enqueue_scripts', 'berkeley_settings_admin_styles', 99 );
function berkeley_settings_admin_styles( $hook ) {
	if ( !in_array( $hook, array( 'edit.php', 'post.php', 'post-new.php', 'toplevel_page_genesis', 'widgets.php' ) ) )
		return;
		
    wp_enqueue_style( 'berkeley-admin-css', get_stylesheet_directory_uri() . '/css/admin-style.css' );
}

add_action( 'wp_enqueue_scripts', 'berkeley_enqueue_files', 1 );
function berkeley_enqueue_files() {
	// Enqueue fonts
	wp_enqueue_style( 'berkeley-coetheme-fonts', berkeley_theme_fonts_url(), array(), null );
		
	// Enqueue responsive menu script
	wp_enqueue_script( 'berkeley-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true );
	
	// Enqueue color scheme stylesheet
	$color = genesis_get_option( 'style_selection' );
	$path = berkeley_get_color_stylesheet( $color );
	if ( !empty( $path ) && file_exists( $path ) )
		wp_enqueue_style( 'berkeley-'.$color , $path );
	
	// Enqueue accordion js for Additional Content fields on single templates
	if ( is_singular() )
		wp_enqueue_script( 'berkeley-accordion', get_stylesheet_directory_uri() . '/js/accordion.js', array( 'jquery' ), false, true );
	
	// Enqueue ACF maps scripts on facilities
	if ( is_singular( 'facility' ) && function_exists( 'get_field' ) && get_field( 'map', get_the_ID() ) ) {
		wp_enqueue_script( 'berkeley-google-maps-api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', '', '1.0.0', true );
		wp_enqueue_script( 'berkeley-acf-maps', get_stylesheet_directory_uri() . '/js/acf-maps.js', array( 'google-maps-api' ), '1.0.0', true );
	}
}

// Add menu toggle buttons with specific IDs
add_action( 'genesis_after_header', 'berkeley_menu_buttons', 99 );
function berkeley_menu_buttons() {
	if ( has_nav_menu( 'primary' ) )
		echo '<button id="secondary-toggle" class="menu-toggle" role="button" aria-pressed="false">'.esc_html__( 'Secondary Menu', 'berkeley-coe-theme' ).'</button>';
	if ( has_nav_menu( 'secondary' ) )
		echo '<button id="primary-toggle" class="menu-toggle" role="button" aria-pressed="false">'.esc_html__( 'Menu', 'berkeley-coe-theme' ).'</button>';
}