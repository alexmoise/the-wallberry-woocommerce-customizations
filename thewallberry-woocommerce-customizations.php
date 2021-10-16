<?php
/**
 * Plugin Name: The Wallberry Woocommerce customizations

 * Plugin URI: https://github.com/alexmoise/the-wallberry-woocommerce-customizations
 * GitHub Plugin URI: https://github.com/alexmoise/the-wallberry-woocommerce-customizations
 * Description: A custom plugin to add required customizations to The Wallberry Woocommerce shop and to style the front end as required. For details/troubleshooting please contact me at <a href="https://moise.pro/contact/">https://moise.pro/contact/</a>
 * Version: 1.0.7
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

// Add Pinterest verification
add_action( 'wp_head', 'motwbr_pinterest_verification', 0 );
function motwbr_pinterest_verification() {
	echo '<meta name="p:domain_verify" content="c1eb63844d1db4285e094b8ea59b8343"/>';
}

// Remove the product price
// add_filter( 'woocommerce_get_price_html', 'motwbr_return_false', 10 );
// add_filter( 'woocommerce_variable_price_html', 'motwbr_return_false', 10 );
add_filter( 'woocommerce_grouped_price_html', 'motwbr_return_false', 10 );
add_filter( 'woocommerce_variable_sale_price_html', 'motwbr_return_false', 10 );
function motwbr_return_false($price) { return false; }

// Change price range to show "From" instead
add_filter( 'woocommerce_format_price_range', 'motwbr_from_price_range', 10, 3 );
function motwbr_from_price_range( $price, $from, $to ) {
    return sprintf( '%s: %s', 'From', wc_price( $from ) );
} 

// Add title to Blog archive page 
add_action( astra_primary_content_top, motwbr_add_blog_title );
function motwbr_add_blog_title() {
	if ( is_home() ) {
		echo '<H1 class="blog-title">'.single_post_title( '', false ).'</H1>';
	}
}

// Output the Category Pre-Footer in 'product_cat' pages and 'room' pages. 
// !! Field is added with ACF plugin
add_action( 'astra_content_after', 'motwbr_category_pre_footer_output' );
function motwbr_category_pre_footer_output() {
	if ( is_tax('product_cat') || is_tax('room') ) {
		$product_cat_object = get_queried_object();
		if(get_field( 'category_pre_footer', 'product_cat_'.$product_cat_object->term_id)) {
			echo '<div class="category_pre_footer">';
			the_field( 'category_pre_footer', 'product_cat_'.$product_cat_object->term_id);
			echo '</div>';
		}
		
	}
}

// Output the SHOP Page Pre-Footer 
// !! Field is added with ACF plugin
add_action( 'astra_content_after', 'motwbr_shop_pre_footer_output' );
function motwbr_shop_pre_footer_output() {
	if ( is_shop() ) {
		$postid = get_option( 'woocommerce_shop_page_id' ); 
		if( get_field('shop_pre_footer', $postid) ) {
			echo '<div class="category_pre_footer">';
			the_field( 'shop_pre_footer', $postid );
			echo '</div>';
		}
	}
}

// Hide all except *some* shipping methods when Free Shipping is available
// Since the labels are hardcoded below these have to stay unchanged for this to work (maybe add a config page/option later?)
add_filter( 'woocommerce_package_rates', 'motwbr_manage_shipping_methods', 10, 2 );
function motwbr_manage_shipping_methods( $rates, $package ) {
	$new_rates = array();
	foreach ( $rates as $rate_id => $rate ) {
		// Only modify rates if free_shipping is present.
		if ( 'Free standard shipping' === $rate->label ) {
			$new_rates[ $rate_id ] = $rate;
			break;
		}
	}
	if ( ! empty( $new_rates ) ) {
		//Save local pickup if it's present.
		foreach ( $rates as $rate_id => $rate ) {
			if ('Express Shipping' === $rate->label ) {
				$new_rates[ $rate_id ] = $rate;
				break;
			}
		}
		return $new_rates;
	}
	return $rates;
}

?>
