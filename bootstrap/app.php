<?php
/**
 * Global Scope File
 * It will be executed first thing before the plugins loaded into global scope
 * 
 * @package PlutuFormidable
 */

defined( 'ABSPATH' )or exit;

// Create a simple autoloader for the plugin
$autoload = [
    PLUTU_FORMIDABLE_INCLUDES_TRAITS => true, 
    PLUTU_FORMIDABLE_INCLUDES_MODELS => false,
    PLUTU_FORMIDABLE_INCLUDES_API => true,
    PLUTU_FORMIDABLE_INCLUDES_ABSTRACTS => false, 
    PLUTU_FORMIDABLE_INCLUDES_CONTROLLERS => true, 
    PLUTU_FORMIDABLE_INCLUDES_AJAX => true, 
];
foreach( $autoload as $folder => $declare ){
    if( is_dir( $folder ) ) {
        foreach( glob( $folder . "/*.php" ) as $file ) {
            if( file_exists( $file ) ) {
                include realpath( $file );
                $class = ucwords( str_replace( '-', '_', substr( basename( $file, '.php' ), 6) ), '_' );
                if( $declare && class_exists( $class ) ) {
                    new $class;
                }
            }
        }
    }
}
// Loads a plugin's translated strings.
load_plugin_textdomain( 'plutu-formidable', false, PLUTU_FORMIDABLE_PLUGIN_LANGUAGE );