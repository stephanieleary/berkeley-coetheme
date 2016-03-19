<?php

remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
remove_action( 'genesis_post_content', 'genesis_do_post_image' );
//add_action( 'genesis_before_post_title', 'genesis_do_post_image' );

genesis();