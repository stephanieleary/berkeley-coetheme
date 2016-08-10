<?php

// Register theme settings boxes
function berkeley_register_options_settings_box( $_genesis_theme_settings_pagehook ) {
	add_meta_box( 'berkeley-logo-settings', esc_html__( 'Logo and Front Page Title', 'berkeley-coe-theme' ), 'berkeley_logo_settings_box', $_genesis_theme_settings_pagehook, 'main', 'high' );
	add_meta_box( 'berkeley-color-settings', esc_html__( 'Color Scheme', 'berkeley-coe-theme' ), 'berkeley_color_settings_box', $_genesis_theme_settings_pagehook, 'main', 'high' );
	add_meta_box( 'berkeley-postmeta-settings', esc_html__( 'Blog Post Metadata', 'berkeley-coe-theme' ), 'berkeley_postmeta_settings_box', $_genesis_theme_settings_pagehook, 'main', 'low' );
	remove_meta_box( 'genesis-theme-settings-style-selector', $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-header', $_genesis_theme_settings_pagehook, 'main' );
}
add_action( 'genesis_theme_settings_metaboxes', 'berkeley_register_options_settings_box' );


function berkeley_theme_defaults( $defaults = array() ) {
	$defaults['be_logo'] = false;
	$defaults['hide_home_title'] = false;
	$defaults['style_selection'] = 'pool';
	$defaults['post_meta'] = '[post_categories] [post_tags]';
	$defaults['post_info'] = 'Posted on [post_date] by [post_author]';
	return $defaults;
}
add_filter( 'genesis_theme_settings_defaults', 'berkeley_theme_defaults' );


function berkeley_theme_option_sanitization() {
	// we don't need to sanitize style_selection because it's a built in option & already sanitized
	
	genesis_add_option_filter( 
		'no_html', 
		GENESIS_SETTINGS_FIELD,
		array(
			'be_logo',
			'hide_home_title'
		) 
	);

	genesis_add_option_filter( 
		'safe_html', 
		GENESIS_SETTINGS_FIELD,
		array(
			'post_info',
			'post_meta'
		) 
	);
}
add_action( 'genesis_settings_sanitizer_init', 'berkeley_theme_option_sanitization' );

/**
 * Logo and Home Page Title options
 *
 * Add an option to display the Berkeley logo below the site title and description, 
 * and to hide the title on the home page.
 */

function berkeley_logo_settings_box() {
	$options = array(
		'hide_title' => genesis_get_option( 'hide_home_title' ),
		'logo' 		 => genesis_get_option( 'be_logo' ),
		'colors'  	 => genesis_get_option( 'style_selection' ),
	);
	$options = wp_parse_args( $options, berkeley_theme_defaults() );
	
	$bg 		= '#fff';
	$blue 		= '#003262';
	$south_hall = '#6c3302';
	$pacific 	= '#46535e';
	$stone_pine = '#584f29';
	
	switch ( $options['colors'] ) {
		case 'pool':
		case 'punch':
		case 'classic':
			$bg = $blue;
			break;
		case 'earth':
			$bg = $south_hall;
			break;
		case 'woods':
			$bg = $stone_pine;
			break;
		case 'pacific':
			$bg = $pacific;
			break;
		default: 
			break;
	}
	?>
	
	<table class="form-table">
	<tbody>

		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Front Page Title', 'berkeley-coe-theme' ); ?></th>
			<td>
				<fieldset>
					<p><label><input type="checkbox" 
						name="<?php echo GENESIS_SETTINGS_FIELD; ?>[hide_home_title]" 
						value="1" <?php checked( $options['hide_title'], 1 ); ?> /> 
					<?php esc_html_e( 'Hide page title on front page', 'berkeley-coe-theme' );?></label></p>
					</fieldset>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Logo Visibility', 'berkeley-coe-theme' ); ?></th>
			<td>
				<fieldset class="genesis-logo-selector">
					<p><label><input type="checkbox" 
						name="<?php echo GENESIS_SETTINGS_FIELD; ?>[be_logo]" 
						value="1" <?php checked( $options['logo'], 1 ); ?> /> 
					<?php esc_html_e( 'Display Berkeley logo above site title and description', 'berkeley-coe-theme' );?></label></p>
					<div class="be-logo-preview" style="background-color: <?php echo $bg; ?>">
						<?php berkeley_logo_display(); ?>
					</div>
					</fieldset>
			</td>
		</tr>

	</tbody>
	</table>
	<?php
}


/**
 * Color Selector
 *
 * Add a visual color scheme selector to Theme Settings, to replace the Color Style dropdown.
 */

function berkeley_color_settings_box() {
	$colors = berkeley_get_colors();
	$options = array(
		'colors' => genesis_get_option( 'style_selection' ),
	);
	$options = wp_parse_args( $options, berkeley_theme_defaults() );
	?>
	
	<table class="form-table">
	<tbody>

		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Select Theme Colors', 'berkeley-coe-theme' ) ?></th>
			<td>
				<fieldset class="genesis-layout-selector">
					<legend class="screen-reader-text"><?php esc_html_e( 'Color Scheme', 'berkeley-coe-theme' ); ?></legend>

					<?php
					foreach ( $colors as $color => $label ) {
						$classes = 'box';
						if ( $color == 'earth' )
							$classes .= ' clear';
						if ( $options['colors'] == $color )
							$classes .= ' selected';
						$path = get_stylesheet_directory_uri() . '/images/color-schemes/' . sanitize_title( $color );
						printf( '<label for="%1$s" class="%2$s">
							<img alt="%3$s" title="%3$s" src="%4$s.png">
							<input type="radio" class="screen-reader-text" 
								%5$s
								value="%1$s" 
								id="%1$s" 
								name="%6$s[style_selection]">
							<span class="color-scheme-caption">%3$s </span>
							</label>', 
							esc_attr( $color ),
							esc_attr( $classes ),
							esc_attr( $label ),
							esc_url( $path ),
							checked( $options['colors'], $color, false ),
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

/**
 * Post Meta Options
 *
 */

function berkeley_postmeta_settings_box() {	
	$options = array(
		'post_meta' => genesis_get_option( 'post_meta' ),
		'post_info' => genesis_get_option( 'post_info' ),
	);
	$options = wp_parse_args( $options, berkeley_theme_defaults() );
	?>

	<table class="form-table">
	<tbody>

		<tr valign="top">
			<th scope="row">
				<label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[post_info]"><?php esc_html_e( 'Display below title:', 'berkeley-coe-theme' );?></label>
			</th>
			<td>
				<p>
					<input type="text" 
					class="regular-text" 
					id="<?php echo GENESIS_SETTINGS_FIELD; ?>[post_info]" 
					name="<?php echo GENESIS_SETTINGS_FIELD; ?>[post_info]" 
					value="<?php echo esc_attr( $options['post_info'] ); ?>" /> 
				</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row">
				<label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[post_meta]"><?php esc_html_e( 'Display below content:', 'berkeley-coe-theme' );?></label>
			</th>
			<td>
				<p>
					<input type="text" 
					class="regular-text" 
					id="<?php echo GENESIS_SETTINGS_FIELD; ?>[post_meta]" 
					name="<?php echo GENESIS_SETTINGS_FIELD; ?>[post_meta]" 
					value="<?php echo esc_attr( $options['post_meta'] ); ?>" /> 
				</p>
				
				<p class="description">
				<?php _e('See <a href="admin.php?page=wp-help-documents" target="_blank">the documentation</a> for all available shortcodes', 'berkeley-coe-theme' ); ?>
				</p>
			</td>
		</tr>

	</tbody>
	</table>
	<?php
}