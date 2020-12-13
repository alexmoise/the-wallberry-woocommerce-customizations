<?php
/**
 * Plugin Name: The Wallberry Woocommerce customizations

 * Plugin URI: https://github.com/alexmoise/the-wallberry-woocommerce-customizations
 * GitHub Plugin URI: https://github.com/alexmoise/the-wallberry-woocommerce-customizations
 * Description: A custom plugin to add required customizations to The Wallberry Woocommerce shop and to style the front end as required. For details/troubleshooting please contact me at <a href="https://moise.pro/contact/">https://moise.pro/contact/</a>
 * Version: 1.0.0
 * Author: Alex Moise
 * Author URI: https://moise.pro
 * WC requires at least: 4.0.0
 * WC tested up to: 4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {	exit(0);}

// Increase image quality a bit, so all the straight lines appears smooth
add_filter('jpeg_quality', function($arg){return 92;});

// Load our own JS
add_action( 'wp_enqueue_scripts', 'motwbr_adding_scripts', 9999999 );
function motwbr_adding_scripts() {
	wp_register_script('twbrwc-script', plugins_url('twbrwc.js', __FILE__), array('jquery'), '', true);
	wp_enqueue_script('twbrwc-script');
}
// Load our own CSS
add_action( 'wp_enqueue_scripts', 'motwbr_adding_styles', 9999999 );
function motwbr_adding_styles() {
	wp_register_style('twbrwc-styles', plugins_url('twbrwc.css', __FILE__));
	wp_enqueue_style('twbrwc-styles');
}

?>
