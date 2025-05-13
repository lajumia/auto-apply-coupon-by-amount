<?php
/**
 * Plugin Name: Auto Apply Coupon by Cart Total for WooCommerce
 * Plugin URI:  https://yourwebsite.com/
 * Description: Automatically applies a coupon code if the cart total exceeds a specific amount. Simple WooCommerce addon.
 * Version:     1.0.0
 * Author:      Your Name
 * Author URI:  https://yourwebsite.com/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: auto-apply-coupon
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class AACAW_Auto_Apply_Coupon {

    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    public function init() {
        // Add setting tab to WooCommerce > Settings
        add_filter( 'woocommerce_get_settings_pages', [ $this, 'add_settings_page' ] );

        // Hook to cart calculation
        add_action( 'woocommerce_before_cart', [ $this, 'maybe_apply_coupon' ] );
    }

    public function add_settings_page( $settings ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-settings.php';
        $settings[] = new AACAW_Settings();
        return $settings;
    }

    public function maybe_apply_coupon() {
        if ( is_admin() || ! WC()->cart ) {
            return;
        }

        $min_total = get_option( 'aacaw_min_total' );
        $coupon_code = sanitize_text_field( get_option( 'aacaw_coupon_code' ) );

        if ( ! $min_total || ! $coupon_code ) {
            return;
        }

        $cart_total = WC()->cart->get_subtotal();

        if ( $cart_total >= $min_total && ! WC()->cart->has_discount( $coupon_code ) ) {
            WC()->cart->apply_coupon( $coupon_code );
        }
    }
}

new AACAW_Auto_Apply_Coupon();
