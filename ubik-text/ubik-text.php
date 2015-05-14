<?php
/**
 * Plugin Name: Ubik Text
 * Plugin URI: http://github.com/synapticism/ubik-text
 * Description: Simple font loading functions for WordPress
 * Author: Alexander Synaptic
 * Author URI: http://alexandersynaptic.com
 * Version: 0.0.7
 */

// Do not call this plugin directly
if ( !defined( 'WPINC' ) )
  die;

// Configuration file loading: first we try to grab user-defined settings
if ( is_readable( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-text-config.php' ) )
  require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-text-config.php' );

// Configuration file loading: now load the defaults
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-text-config-defaults.php' );

// Load plugin core
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-text-replace.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-text-strip.php' );
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ubik-text-truncate.php' );
