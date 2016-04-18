<?php

// Hide some meta boxes by default

add_filter( 'default_hidden_meta_boxes', 'berkeley_coetheme_hidden_meta_boxes', 10, 2 );

function berkeley_coetheme_hidden_meta_boxes( $hidden, $screen ) {
    return array( 
		'people_typediv', 
		'facility_typediv', 
		'wpe_dify_news_feed',
		'genesis-theme-settings-version', 
		'genesis-theme-settings-feeds',
		'genesis_inpost_scripts_box',
		'i123_widgets_custom_fields',
		'commentstatusdiv',
		'slugdiv',
		'authordiv',
		'postcustom',
		'trackbacksdiv',
		'sharing_meta'
	);
}

// Close some meta boxes by default
// The dynamic portions of the hook name, `$page` and `$id`, refer to the screen and metabox ID, respectively.
// add_filter( "postbox_classes_{$page}_{$id}", 'berkeley_coetheme_closed_meta_boxes' );

add_action( 'admin_init', 'berkeley_close_meta_boxes', 99 );

function berkeley_close_meta_boxes() {
	$post_types = get_post_types( array( 'public' => true ) );
	foreach ( $post_types as $type ) {
		// Close Excerpt on all post types
		add_filter( "postbox_classes_{$type}_postexcerpt", 'berkeley_coetheme_closed_meta_boxes' );
		// Close Per Page Widgets on all post types
		add_filter( "postbox_classes_{$type}_i123_widgets_custom_fields", 'berkeley_coetheme_closed_meta_boxes' );
		// Close Genesis SEO on all post types
		add_filter( "postbox_classes_{$type}_genesis_inpost_seo_box", 'berkeley_coetheme_closed_meta_boxes' );
		// Close Genesis Layout on all post types
		add_filter( "postbox_classes_{$type}_genesis_inpost_layout_box", 'berkeley_coetheme_closed_meta_boxes' );
		
		// Close Genesis Layout on all CPT Archive Settings
		add_filter( "postbox_classes_{$type}_page_genesis-cpt-archive-{$type}_genesis-cpt-archives-seo-settings", 		'berkeley_coetheme_closed_meta_boxes' );
		// Close Genesis SEO on all CPT Archive Settings
		add_filter( "postbox_classes_{$type}_page_genesis-cpt-archive-{$type}_genesis-cpt-archives-layout-settings", 		'berkeley_coetheme_closed_meta_boxes' );
	}
}

function berkeley_coetheme_closed_meta_boxes( $classes ) {
    array_push( $classes, 'closed' );
    return $classes;
}