<?php

// Hide some meta boxes by default

add_filter( 'default_hidden_meta_boxes', 'berkeley_coetheme_hidden_meta_boxes', 10, 2 );

function berkeley_coetheme_hidden_meta_boxes( $hidden, $screen ) {
    return array( 
		'genesis-theme-settings-version', 
		'genesis-theme-settings-feeds',
		'i123_widgets_custom_fields' 
	);
}

// Close some meta boxes by default
// The dynamic portions of the hook name, `$page` and `$id`, refer to the screen and screen ID, respectively.

// add_filter( "postbox_classes_{$page}_{$id}", 'berkeley_coetheme_closed_meta_boxes' );

function berkeley_coetheme_closed_meta_boxes( $classes ) {
	if ( isset( $_POST['my_condition'] ) && 'my_condition' == $_POST['my_condition'] )
		array_push( $classes, 'closed' );

	return $classes;
}

// Stylesheet for Genesis theme settings screen
function berkeley_settings_admin_styles( $hook ) {
    if ( 'genesis' == $hook || 'toplevel_page_genesis' == $hook )
		wp_enqueue_style( 'genesis-theme-options-css', get_stylesheet_directory_uri() . '/css/admin-style.css' );
}
add_action( 'admin_enqueue_scripts', 'berkeley_settings_admin_styles', 99 );

// Register theme settings boxes
function berkeley_register_options_settings_box( $_genesis_theme_settings_pagehook ) {
	add_meta_box( 'berkeley-logo-settings', __('Berkeley Engineering Logo', 'beng'), 'berkeley_logo_settings_box', $_genesis_theme_settings_pagehook, 'main', 'high' );
	add_meta_box( 'berkeley-color-settings', __('Color Scheme', 'beng'), 'berkeley_color_settings_box', $_genesis_theme_settings_pagehook, 'main', 'high' );
	remove_meta_box( 'genesis-theme-settings-style-selector', $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-header', $_genesis_theme_settings_pagehook, 'main' );
}
add_action( 'genesis_theme_settings_metaboxes', 'berkeley_register_options_settings_box' );


/**
 * Logo option
 *
 * Add an option to display the Berkeley logo below the site title and description.
 */

function berkeley_logo_display() {
	$logo = genesis_get_option( 'be_logo' );
	if ( $logo ) {
		$path = get_stylesheet_directory_uri() . '/images/';
		$colors = genesis_get_option( 'style_selection' );
		switch ( $colors ) {
			case 'earth light':
				$path .= 'BE-southhall.png';
				break;
			case 'woods light':
				$path .= 'BE-stonepine.png';
				break;
			case 'pacific light':
				$path .= 'BE-pacific.png';
				break;
			case 'pool light':
			case 'classic light':
				$path .= 'BE-blue.png';
				break;
			case 'punch light':
				$path .= 'BE-foundersrock.png';
				break;
			case 'classic':
			case 'punch':
				$path .= 'BE-gold-blue.png';
				break;
			case 'earth':
				$path .= 'BE-bayfog-southhall.png';
				break;
			case 'woods':
				$path .= 'BE-bayfog-stonepine.png';
				break;
			default: 
				$path .= 'be_logo_white.png';
				break;
		}
		printf( '<div id="berkeley-engineering-logo"><a href="http://engineering.berkeley.edu"><img src="%s" alt="%s"></a></div>', $path, __('Berkeley College of Engineering Logo', 'beng') );
	}
}
add_action( 'genesis_site_title', 'berkeley_logo_display', 1 );

function berkeley_logo_defaults( $defaults ) {
	$defaults['be_logo'] = false;
	return $defaults;
}
add_filter( 'genesis_theme_settings_defaults', 'berkeley_logo_defaults' );

function berkeley_logo_settings_box() {
	
	$logo		= genesis_get_option( 'be_logo' );
	$path 		= get_stylesheet_directory_uri() . '/images/';
	$img_black	= 'be_logo_blue.png';
	$img_white	= 'be_logo_white.png';
	$bg 		= '#fff';
	$blue 		= '#003262';
	$south_hall = '#6c3302';
	$pacific 	= '#46535e';
	$stone_pine = '#584f29';
	$colors = genesis_get_option( 'style_selection' );
	switch ( $colors ) {
		case 'pool':
		case 'punch':
		case 'classic':
			$bg = $blue;
			$path = $path . $img_white;
			break;
		case 'earth':
			$bg = $south_hall;
			$path = $path . $img_white;
			break;
		case 'woods':
			$bg = $stone_pine;
			$path = $path . $img_white;
			break;
		case 'pacific':
			$bg = $pacific;
			$path = $path . $img_white;
			break;
		default: 
			$path = $path . $img_black;
			break;
	}
	?>
	
	<table class="form-table">
			<tbody>

				<tr valign="top">
					<th scope="row"><?php _e( 'Logo Visibility', 'beng' ); ?></th>
					<td>
						<fieldset class="genesis-logo-selector">
							<p><label><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[be_logo]" value="1" <?php checked( $logo, 1 ); ?> /> 
							<?php _e( 'Display Berkeley logo below site title and description', 'beng' );?></label></p>
							<div class="be-logo-preview" style="background: <?php echo esc_attr( $bg ); ?>">
								<?php if ( $logo ) {
									printf( '<img src="%s" alt="Berkeley Engineering Logo">', $path );
								} ?>
							</div>
							</fieldset>
					</td>
				</tr>

			</tbody>
			</table>
	<?php
}


function berkeley_register_logo_sanitization_filters() {
	genesis_add_option_filter( 
		'no_html', 
		GENESIS_SETTINGS_FIELD,
		array(
			'be_logo',
		) 
	);
}
add_action( 'genesis_settings_sanitizer_init', 'berkeley_register_logo_sanitization_filters' );

/**
 * Color Selector
 *
 * Add a visual color scheme selector to Theme Settings, to replace the Color Style dropdown.
 */

function berkeley_color_settings_defaults( $defaults ) {
	$defaults['style_selection'] = 'pool';
	return $defaults;
}
add_filter( 'genesis_theme_settings_defaults', 'berkeley_color_settings_defaults' );

function berkeley_color_settings_box() {
	?>
	
	<table class="form-table">
			<tbody>

				<tr valign="top">
					<th scope="row"><?php _e( 'Select Theme Colors', 'beng' ) ?></th>
					<td>
						<fieldset class="genesis-layout-selector">
							<legend class="screen-reader-text"><?php _e( 'Color Scheme', 'beng' ); ?></legend>

							<?php

							$colors = berkeley_get_colors();

							foreach ( $colors as $color => $label ) {
								$classes = 'box';
								if ( $color == 'earth' )
									$classes .= ' clear';
								if ( genesis_get_option( 'style_selection' ) == $color )
									$classes .= ' selected';
								$path = get_stylesheet_directory_uri() . '/images/color-schemes/' . sanitize_title( $color );
								printf( '<label for="%1$s" class="%2$s">
									<img alt="%3$s" title=""%3$s" src="%4$s.png">
									<input type="radio" class="screen-reader-text" 
										%5$s
										value="%1$s" 
										id="%1$s" 
										name="%6$s[style_selection]">
									<span class="color-scheme-caption">%3$s </span>
									</label>', 
									$color,
									$classes,
									$label,
									$path,
									checked( genesis_get_option( 'style_selection' ), $color, false ),
									GENESIS_SETTINGS_FIELD
								);
							}
							
							?>

							</fieldset>
						
						<br class="clear">
					</td>
				</tr>

			</tbody>
			</table>
	<?php
}