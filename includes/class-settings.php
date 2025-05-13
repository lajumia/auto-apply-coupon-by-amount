<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AACAW_Settings extends WC_Settings_Page {

    public function __construct() {
        $this->id    = 'aacaw';
        $this->label = __( 'Auto Apply Coupon', 'auto-apply-coupon' );

        parent::__construct();

        add_filter( 'woocommerce_settings_tabs_array', [ $this, 'add_settings_page' ], 20 );
        add_action( 'woocommerce_settings_' . $this->id, [ $this, 'output' ] );
        add_action( 'woocommerce_settings_save_' . $this->id, [ $this, 'save' ] );
    }

    public function get_settings() {
        return [
            [
                'title' => __( 'Auto Apply Coupon Settings', 'auto-apply-coupon' ),
                'type'  => 'title',
                'id'    => 'aacaw_settings',
            ],
            [
                'title'    => __( 'Coupon Code', 'auto-apply-coupon' ),
                'desc'     => __( 'Enter the coupon code to apply automatically.', 'auto-apply-coupon' ),
                'id'       => 'aacaw_coupon_code',
                'type'     => 'text',
                'default'  => '',
                'desc_tip' => true,
            ],
            [
                'title'    => __( 'Minimum Cart Total ($)', 'auto-apply-coupon' ),
                'desc'     => __( 'Cart subtotal must be greater than or equal to this amount to apply the coupon.', 'auto-apply-coupon' ),
                'id'       => 'aacaw_min_total',
                'type'     => 'number',
                'default'  => '',
                'desc_tip' => true,
                'custom_attributes' => [
                    'min' => '0',
                    'step' => '0.01'
                ]
            ],
            [
                'type' => 'sectionend',
                'id'   => 'aacaw_settings',
            ],
        ];
    }
}
