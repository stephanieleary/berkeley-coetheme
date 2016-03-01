<?php

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