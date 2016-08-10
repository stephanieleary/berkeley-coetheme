<?php

//* TinyMCE CSS
add_action( 'after_setup_theme', 'berkeley_editor_styles' );

function berkeley_editor_styles() {
	// add base editor stylesheet and fonts
	add_editor_style( array( 'editor-style.css', berkeley_theme_fonts_url() ) );
	
	// add color scheme stylesheet
	$path = berkeley_get_color_stylesheet( genesis_get_option( 'style_selection' ) );
	if ( !empty( $path ) )
		add_editor_style( $path );
}

// Add color scheme classes to TinyMCE styles
function berkeley_tiny_mce_before_init( $init_array ) {
    $init_array['body_class'] = genesis_get_option( 'style_selection' );

	$template = get_post_meta( get_the_ID(), '_wp_page_template', true );
	if ( isset( $template ) && 'page_whitepaper.php' == $template )
		$init_array['body_class'] .= ' whitepaper';
		
    return $init_array;
}
add_filter( 'tiny_mce_before_init', 'berkeley_tiny_mce_before_init' );

// Callback function to insert 'styleselect' (Formats) into the $buttons array
function berkeley_mce_buttons( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}

add_filter( 'mce_buttons_2', 'berkeley_mce_buttons' );

// Callback function to filter the MCE settings
function berkeley_mce_style_options( $init_array ) {  
    // Define the style_formats array
    $style_formats = array(  
        // Each array child is a format with its own settings
        array(  
            'title' => 'Button Link',  
            'selector' => 'a',  
            'classes' => 'button'             
        )
    );  
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );  

    return $init_array;  

} 

add_filter( 'tiny_mce_before_init', 'berkeley_mce_style_options' );


// New TinyMCE button for blockquotes with cite


function berkeley_pullquote_mce_button() {
	// check if WYSIWYG is enabled
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'berkeley_pullquote_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'berkeley_pullquote_register_mce_button' );
	}
}
add_action('admin_head', 'berkeley_pullquote_mce_button');

// Declare script for new button
function berkeley_pullquote_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['blockquote_cite'] = get_stylesheet_directory_uri() .'/js/mce-buttons.js';
	return $plugin_array;
}

// Register new button in the editor
function berkeley_pullquote_register_mce_button( $buttons ) {
	$first = array_slice( $buttons, 0, 6 );
	array_push( $first, 'blockquote_cite' );
	$buttons = array_splice( $buttons, 6, count( $buttons ) );
	return array_merge( $first, $buttons );
}
