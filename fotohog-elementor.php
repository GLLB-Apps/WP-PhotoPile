<?php
/**
 * Plugin Name: Fotohög till Elementor
 * Description: Adds a photo stack widget to Elementor.
 * Version: 2.0.4
 * Author: David Gullberg
 * Author URI: https://gllb.se 
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Fotohog_Elementor_Plugin {
    const VERSION = '2.0.4';

    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }

    public function init() {
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', array( $this, 'elementor_missing_notice' ) );
            return;
        }

        add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_styles' ) );
        add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_scripts' ) );
        add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
    }

    public function register_styles() {
        $css_file = __DIR__ . '/assets/css/fotohog-widget.css';
        $css_ver  = file_exists( $css_file ) ? (string) filemtime( $css_file ) : self::VERSION;

        wp_register_style(
            'fotohog-widget-v2',
            plugins_url( 'assets/css/fotohog-widget.css', __FILE__ ),
            array(),
            $css_ver
        );
    }

    public function register_scripts() {
        $js_file = __DIR__ . '/assets/js/fotohog-widget.js';
        $js_ver  = file_exists( $js_file ) ? (string) filemtime( $js_file ) : self::VERSION;

        wp_register_script(
            'fotohog-widget-v2',
            plugins_url( 'assets/js/fotohog-widget.js', __FILE__ ),
            array(),
            $js_ver,
            true
        );
    }

    public function register_widgets( $widgets_manager ) {
        require_once __DIR__ . '/includes/class-fotohog-widget.php';
        $widgets_manager->register( new \Fotohog_Widget() );
    }

    public function elementor_missing_notice() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        echo '<div class="notice notice-warning"><p>';
        echo esc_html__( 'Fotohog for Elementor requires Elementor to be installed and activated.', 'fotohog-elementor' );
        echo '</p></div>';
    }
}

new Fotohog_Elementor_Plugin();
