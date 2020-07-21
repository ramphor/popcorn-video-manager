<?php
/**
 * Plugin Name: Ramphor Popcorn
 * Plugin URI: https://puleeno.com
 * Author: Ramphor Premium
 * Author URI: https://puleeno.com
 * Version: 1.0.0
 * Description: The video manager for WordPress. Managa, play and share your videos.
 */

define( 'POPCORN_PLUGIN_FILE', __FILE__ );

if ( ! class_exists( 'Ramphor_Popcorn' ) ) {
	require_once dirname( __FILE__ ) . '/includes/class-ramphor-popcorn.php';
}

if ( ! function_exists( 'ramphor_popcorn' ) ) {
	function ramphor_popcorn() {
		return Ramphor_Popcorn::get_instance();
	}
}

$GLOBALS['popcorn'] = ramphor_popcorn();
