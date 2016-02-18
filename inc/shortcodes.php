<?php
add_action( 'init', 'berkeley_shortcodes_register' );

function berkeley_shortcodes_register() {
	add_shortcode( 'site-name', 'berkeley_sitename_shortcode' );
}

function berkeley_sitename_shortcode() {
	return get_option( 'blogname' );
}