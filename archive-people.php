<?php

remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
remove_action( 'genesis_post_content', 'genesis_do_post_image' );
add_action( 'genesis_entry_header', 'genesis_do_post_image', 1 );

// add Stickies loop above the main loop
add_action( 'genesis_loop', 'berkeley_sticky_post_loop', 1 );

genesis();