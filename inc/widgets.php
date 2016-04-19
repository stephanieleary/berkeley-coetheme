<?php

// Remove the WP Engine widget
unregister_widget( 'wpe_widget_powered_by' );

// Filter the Recent Posts widget to specify the post type when possible
add_filter( 'widget_posts_args', 'berkeley_recent_post_widget_args' );

function berkeley_recent_post_widget_args( $args ) {
	if ( function_exists( 'berkeley_find_post_type' ) )
		$type = berkeley_find_post_type();
	else
		$type = get_query_var( 'post_type' );
	
	if ( isset( $type ) && !empty( $type ) && !in_array( $type, array( 'any', 'post', 'page', 'attachment' ) ) )
		$args['post_type'] = $type;
	
	return $args;
}

// Add background color selector to specific widgets
// cf. http://ednailor.com/2011/01/24/adding-custom-css-classes-to-sidebar-widgets/
function berkeley_editor_widget_class_form_extend( $instance, $widget ) {
	// apply only to the rich text widget for now
	if ( 'wp_editor_widget' !== $widget->id_base )
		return $instance;
	
	if ( !isset( $instance['classes'] ) )
		$instance['classes'] = '';
	
	$myclasses = array( 0 => 'None', 'bold' => 'Bold', 'subtle' => 'Subtle' );
	
	// Do not change the format of the <select> markup below
	$row = '<p>';
	$row .= "<label for='widget-{$widget->id_base}-{$widget->number}-classes'>Background color:</label>";
	$row .= "<select name='widget-{$widget->id_base}[{$widget->number}][classes]' id='widget-{$widget->id_base}-{$widget->number}-classes' class='widefat'>";

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
	if ( is_admin() )
		return $params;
	
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