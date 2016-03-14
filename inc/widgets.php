<?php

//* Add support for after-entry widget area (blog posts only)
add_theme_support( 'genesis-after-entry-widget-area' );
//* Add support for after-entry widget area to pages as well
add_action( 'genesis_entry_footer', 'berkeley_after_entry_widget'  ); 
function berkeley_after_entry_widget() {
	if ( ! is_page() )
		return;

	genesis_widget_area( 'after-entry', array(
		'before' => '<div class="after-entry widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );
}

add_action( 'after_theme_setup', 'berkeley_register_sidebars' );


function berkeley_register_sidebars() {
	// Register widget areas 
	// * Announcement feature
	genesis_register_sidebar( array(
		'id'			=> 'berkeley-announcements',
		'name'			=> __( 'Emergency Announcements' ),
		'description'	=> __( 'Area between the logo and the main navigation.' ),
	) );
	// * Slideshow widget area
	genesis_register_sidebar( array(
		'id'			=> 'berkeley-featured',
		'name'			=> __( 'Featured Content' ),
		'description'	=> __( 'Full-width area below the main navigation.' ),
	) );
	
	
	// CPT-specific sidebar names match CPT names; see berkeley_do_sidebar() below
	genesis_register_sidebar( array(
		'id'			=>	'people',
		'name'			=>	__( 'People' ),
		'description'	=>	__( 'This is the primary sidebar on People pages.' ),
	) );
	genesis_register_sidebar( array(
		'id'			=>	'facility',
		'name'			=>	__( 'Facilities' ),
		'description'	=>	__( 'This is the primary sidebar on Facility pages.' ),
	) );
	genesis_register_sidebar( array(
		'id'			=>	'course',
		'name'			=>	__( 'Courses' ),
		'description'	=>	__( 'This is the primary sidebar on Course pages.' ),
	) );
	genesis_register_sidebar( array(
		'id'			=>	'publication',
		'name'			=>	__( 'Publications' ),
		'description'	=>	__( 'This is the primary sidebar on Publication pages.' ),
	) );
	genesis_register_sidebar( array(
		'id'			=>	'research',
		'name'			=>	__( 'Research' ),
		'description'	=>	__( 'This is the primary sidebar on Research pages.' ),
	) );
}


// Do widget areas on corresponding post types
add_action( 'get_header', 'berkeley_cpt_switch_sidebar' );
function berkeley_cpt_switch_sidebar() {
	if ( is_admin() )
		return;
	
	$type = get_post_type();
	
	if ( isset( $type ) && in_array( $type, array( 'post', 'page', 'attachment' ) ) )
		return;
	
	remove_action( 'genesis_sidebar', 'genesis_do_sidebar' ); 
	add_action( 'genesis_sidebar', 'berkeley_do_sidebar' ); 
}

function berkeley_do_sidebar() {
	$type = get_post_type();
	dynamic_sidebar( $type );
}

// Add background color selector to specific widgets
// cf. http://ednailor.com/2011/01/24/adding-custom-css-classes-to-sidebar-widgets/
function berkeley_editor_widget_class_form_extend( $instance, $widget ) {
	if ( 'wp_editor_widget' !== $widget->id_base )
		return $instance;
	
	if ( !isset( $instance['classes'] ) )
		$instance['classes'] = '';
	
	$myclasses = array( 0 => 'None', 'bold' => 'Bold', 'subtle' => 'Subtle' );
	$fieldname = sprintf( 'widget-%s[%d][classes]', $widget->id_base, $widget->number );
	$field_id = sprintf( 'widget-%s-%d-classes', $widget->id_base, $widget->number );
	
	$row = '<p>';
	$row .= sprintf( '<label for="%s">%s</label>', $field_id, 'Background color:' );
	$row .= sprintf( '<select name="%s" id="%s" class="widefat">', $fieldname, $field_id );

	foreach( $myclasses as $class => $label ) {
		$row .= sprintf( '<option value="%s" %s >%s</option>', $class, selected( $class, $instance['classes'], false ), $label );
	}
	$row .= '</select></p>';
	
	echo $row;
	return $instance;
}

add_filter( 'widget_form_callback', 'berkeley_editor_widget_class_form_extend', 10, 2 );

function berkeley_editor_widget_class_update( $instance, $new_instance ) {
	if ( isset( $new_instance['classes'] ) && in_array( $new_instance['classes'], array( 'bold', 'subtle' ) ) )
		$instance['classes'] = $new_instance['classes'];
	return $instance;
}

add_filter( 'widget_update_callback', 'berkeley_editor_widget_class_update', 10, 2 );

function berkeley_editor_widget_class_params( $params ) {
	global $wp_registered_widgets;
	$widget_id    = $params[0]['widget_id'];
	$widget_obj   = $wp_registered_widgets[$widget_id];
	$widget_opt   = get_option($widget_obj['callback'][0]->option_name);
	$widget_num   = $widget_obj['params'][0]['number'];

	if ( isset($widget_opt[$widget_num]['classes']) && !empty($widget_opt[$widget_num]['classes']) )
		$params[0]['before_widget'] = str_replace( 'class="widget ', 'class="widget ' . $widget_opt[$widget_num]['classes'] . ' ', $params[0]['before_widget'] );

	return $params;
}
add_filter( 'dynamic_sidebar_params', 'berkeley_editor_widget_class_params' );