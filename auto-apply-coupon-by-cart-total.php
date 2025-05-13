<?php
/**
 * Plugin Name: Auto Apply Coupon by Cart Total for WooCommerce
 * Plugin URI:  https://github.com/lajumia/auto-apply-coupon-by-cart-total
 * Description: Automatically applies a coupon code if the cart total exceeds a specific amount. Simple WooCommerce addon.
 * Version:     1.0.0
 * Author:      Md Laju Miah
 * Author URI:  https://www.upwork.com/freelancers/~0149190c8d83bae2e2
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: aacbct
 * Requires Plugins:  woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class AACBCT_Auto_Apply_Coupon {

    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    public function init() {
        // Add setting tab to WooCommerce > Settings
        add_filter( 'woocommerce_get_settings_pages', [ $this, 'aacbct_add_settings_page' ] );

        // Hook to cart calculation
        add_action( 'woocommerce_before_cart', [ $this, 'aacbct_apply_coupon' ] );
    }

    public function aacbct_add_settings_page( $settings ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-settings.php';
        $settings[] = new AACBCT_Settings();
        return $settings;
    }

    public function aacbct_apply_coupon() {
        $min_total   = get_option( 'aacbct_min_total' );
        $coupon_code = sanitize_text_field( get_option( 'aacbct_coupon_code' ) );
    
        if ( ! $min_total || ! $coupon_code ) {
            return;
        }
    
        $cart_total = WC()->cart->get_subtotal();
    
        if ( $cart_total >= $min_total ) {
            // Apply coupon if it's not already applied
            if ( ! WC()->cart->has_discount( $coupon_code ) ) {
                WC()->cart->apply_coupon( $coupon_code );
            }
        } else {
            // Remove coupon if it was applied and cart total is less than minimum
            if ( WC()->cart->has_discount( $coupon_code ) ) {
                WC()->cart->remove_coupon( $coupon_code );
            }
        }
    }
    
}

new AACBCT_Auto_Apply_Coupon();
