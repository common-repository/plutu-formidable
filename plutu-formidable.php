<?php
/**
 * Plugin Name: Plutu Formidable
 * Plugin URI: https://plutu.ly/
 * Description: Plutu Payment Gateways for Formidable Forms.
 * Author: Plutu
 * Author URI: https://plutu.ly
 * Version: 1.0.0
 * Text Domain: plutu-formidable
 * Domain Path: /languages
 * Requires at least: 5.7
 * Requires PHP: 7.0
 * 
 * @package PlutuFormidable
 * @version 1.0.0
 * @link https://plutu.ly (Plutu)
 * @author Mohamed Endisha
 * @copyright Copyright (c) 2022 LibyanSpider.
 */

defined( 'ABSPATH' )or exit;

// Define constants
if ( ! defined( 'PLUTU_FORMIDABLE_DIR' ) ) {

    define( 'PLUTU_FORMIDABLE_DIR', __DIR__ );
    define( 'PLUTU_FORMIDABLE_FILE', __FILE__ );
    define( 'PLUTU_FORMIDABLE_INCLUDES_ABSTRACTS', __DIR__ . '/includes/abstracts' );
    define( 'PLUTU_FORMIDABLE_INCLUDES_API', __DIR__ . '/includes/api' );
    define( 'PLUTU_FORMIDABLE_INCLUDES_AJAX', __DIR__ . '/includes/ajax' );
    define( 'PLUTU_FORMIDABLE_INCLUDES_CONTROLLERS', __DIR__ . '/includes/controllers' );
    define( 'PLUTU_FORMIDABLE_INCLUDES_MODELS', __DIR__ . '/includes/models' );
    define( 'PLUTU_FORMIDABLE_INCLUDES_TRAITS', __DIR__ . '/includes/traits' );
    define( 'PLUTU_FORMIDABLE_PLUGIN_RESOURCES', __DIR__ . '/includes/views' );
    define( 'PLUTU_FORMIDABLE_PLUGIN_LANGUAGE', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    define( 'PLUTU_FROMIDABLE_PLUGIN_ASSETS_JS_URL', plugin_dir_url( __FILE__ ) . 'assets/js' );
    define( 'PLUTU_FROMIDABLE_PLUGIN_ASSETS_JS_FILE', __DIR__ . '/assets/js' );
    define( 'PLUTU_FROMIDABLE_PLUGIN_ASSETS_IMG_URL', plugin_dir_url( __FILE__ ) . 'assets/img' );
    define( 'PLUTU_FROMIDABLE_PLUGIN_ASSETS_IMG_FILE', __DIR__ . '/assets/img' );

}

/**
 * Begins execution
 * Global scope file.
 */
if( function_exists( 'load_formidable_forms' ) ){
    include __DIR__ . '/bootstrap/app.php';
}
