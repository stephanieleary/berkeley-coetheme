<?php

add_filter( 'the_content', 'berkeley_people_content_filter' );

function berkeley_people_content_filter( $content ) {
	return $content;
}

genesis();