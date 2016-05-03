<?php

add_action( 'customize_register', 'berkeley_customizer_register' );

function berkeley_customizer_register() {

	global $wp_customize;

	$image = apply_filters( 'berkeley_header_bg_image_default', '' );

	$wp_customize->add_section( 'berkeley-settings', array(
		'description' => __( 'You may upload a header background image here.<br /><br />The default image is <strong>1200 pixels wide and 235 pixels tall</strong>.<br /><br />To upload an image to replace your site title, see the Header Image section of the Customizer.', 'beng' ),
		'title'    => __( 'Header Background Image', 'beng' ),
		'priority' => 35,
	) );

	$wp_customize->add_setting( 'berkeley-header-bg', array(
		'default'  => $image,
		'type'     => 'theme_mod',
	) );

	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'berkeley-header-bg', array(
		'label' => __( 'Header Background Image', 'beng' ),
		'section' => 'berkeley-settings',
		'mime_type' => 'image',
	) ) );
}