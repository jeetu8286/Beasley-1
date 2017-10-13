<?php
/**
 * Plugin Name:     GreaterMedia FBIA Extension
 * Plugin URI:      https://10up.com
 * Description:     Extending FBIA for GreaterMedia
 * Author:          10up
 * Author URI:      https://10up.com
 * Text Domain:     greatermedia-facebook-ia-extension
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Facebook_Ia_Extension
 */

define( 'GM_FBIA_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'GM_FBIA_PATH', trailingslashit( dirname( __FILE__ ) ) );

include( GM_FBIA_PATH . 'includes/class-instant-articles-ooyala.php' );

$ooyala = new Instant_Articles_Ooyala();
$ooyala->setup();