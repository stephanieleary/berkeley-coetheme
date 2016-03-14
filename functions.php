<?php
//* Start the engine
include_once( get_template_directory() 	 . '/lib/init.php' );
include_once( get_stylesheet_directory() . '/inc/announcements.php' );
include_once( get_stylesheet_directory() . '/inc/content-filters.php' );
include_once( get_stylesheet_directory() . '/inc/editor.php' );
include_once( get_stylesheet_directory() . '/inc/footer.php' );
include_once( get_stylesheet_directory() . '/inc/loops.php' );
include_once( get_stylesheet_directory() . '/inc/maps.php' );
include_once( get_stylesheet_directory() . '/inc/shortcodes.php' );
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

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for WordPress header image feature; remove Genesis's proprietary header feature
add_theme_support( 'custom-header' );
remove_theme_support( 'genesis-custom-header' );

//* Add support for after-entry widget area (blog posts only)
// add_theme_support( 'genesis-after-entry-widget-area' );
//* Add support for after-entry widget area to posts pages
add_action( 'genesis_entry_footer', 'berkeley_after_entry_widget'  ); 
function berkeley_after_entry_widget() {
	if ( ! is_single() && ! is_page() )
		return;

	genesis_widget_area( 'after-entry', array(
		'before' => '<div class="after-entry widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );
}

//* Accessibility features
add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu', 'search-form', 'skip-links', 'rems' ) );
//add_filter( 'genesis_skip_links_output', 'prefix_skip_links' );

//* Create color style options
add_theme_support( 'genesis-style-selector', berkeley_get_colors() );

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

// Color schemes
function berkeley_get_color_stylesheet( $color ) {
	if ( !isset( $color ) )
		return;
		
	$color = str_replace( ' light', '', $color );
	if ( 'pool' == $color )
		return;

	return get_stylesheet_directory_uri() . '/css/color-' . $color . '.css';
}

//* Editor CSS
add_action( 'admin_init', 'berkeley_editor_styles' );

function berkeley_editor_styles() {
	// add base editor stylesheet
	add_editor_style();
	
	// add color scheme stylesheet
	$path = berkeley_get_color_stylesheet( genesis_get_option( 'style_selection' ) );
	if ( !empty( $path ) )
		add_editor_style( $path );
}

// Add color scheme classes to rich text editor
function berkeley_tiny_mce_before_init( $init_array ) {
    $init_array['body_class'] = genesis_get_option( 'style_selection' );
	$template = get_post_meta( get_the_ID(), '_wp_page_template', true );
	if ( isset( $template ) && 'page_whitepaper.php' == $template )
		$init_array['body_class'] .= ' whitepaper';
    return $init_array;
}
add_filter('tiny_mce_before_init', 'berkeley_tiny_mce_before_init');