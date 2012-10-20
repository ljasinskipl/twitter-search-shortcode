<?php

add_action( 'wp_enqueue_scripts', 'ttdiary_add_css', 1 );
function ttdiary_add_css( ) {

	//Want to replace the default styling applied to tweets? Just add this CSS file to your stylesheet's directory.
	$theme_style_file = get_stylesheet_directory() . '/ljpl-ttdiary.css';

	//If you've added a user override CSS, then it will be used instead of the styling I have in the plugin.
	if ( file_exists($theme_style_file) ) {
		wp_register_style( 'user-ttdiary-style', $theme_style_file );
		wp_enqueue_style( 'user-ttdiary-style' );
	}
	//If not, you get the default styling. 
	else {
		wp_register_style( 'ttdiary-style', LJPL_TTDIARY_URL . '/assets/css/ttdiary.css' );
		wp_enqueue_style( 'ttdiary-style' );
	}

}


