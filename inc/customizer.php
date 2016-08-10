<?php

add_action( 'customize_register', 'berkeley_customizer_register' );

function berkeley_customizer_register( $wp_customize ) {
	
	$image = apply_filters( 'berkeley_header_bg_image_default', '' );

	$allowed_html = array( 'br' => array() ); 
	$wp_customize->add_section( 'berkeley-settings', array( 
		'description' => wp_kses( __( 'You may upload a header background image here.<br /><br />The default image is <strong>1200 pixels wide and 235 pixels tall</strong>.<br /><br />To upload an image to replace your site title instead, see the Header Image section of the Customizer.', 'berkeley-coe-theme' ), $allowed_html ), 
		'title' => esc_html__( 'Header Background Image', 'berkeley-coe-theme' ), 
		'priority' => 35, 
	) );

	$wp_customize->add_setting( 'berkeley-header-bg', array(
		'default'  => $image,
		'type'     => 'theme_mod',
		'sanitize_callback' => 'esc_url_raw'
	) );

	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'berkeley-header-bg', array(
		'label' => esc_html__( 'Header Background Image', 'berkeley-coe-theme' ),
		'section' => 'berkeley-settings',
		'mime_type' => 'image',
		'priority'	=> 1,
	) ) );
}