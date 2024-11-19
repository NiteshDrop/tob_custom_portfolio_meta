<?php
function tob_plugin_style() {
	wp_enqueue_style( 'custom_plugin_style', plugin_dir_url( __FILE__ ).'../assets/tob-style.css' );
}
add_action( 'wp_enqueue_scripts', 'tob_plugin_style' );