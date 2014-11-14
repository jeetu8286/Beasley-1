<?php
/*
Plugin Name: jQuery UI Theme
Plugin URI: http://wordpress.org/extend/plugins/jquery-ui-theme/
Description: The jQuery UI theme v1.10.2

Installation:

1) Install WordPress 3.5.2 or higher

2) Download the following file:

http://downloads.wordpress.org/plugin/jquery-ui-theme.1.0.zip

3) Login to WordPress admin, click on Plugins / Add New / Upload, then upload the zip file you just downloaded.

4) Activate the plugin.

Version: 1.0
Author: TheOnlineHero - Tom Skroza
License: GPL2
*/

//call register settings function
if( !function_exists( 'jquery_ui_theme_enqueue_styles' ) ) {
  function jquery_ui_theme_enqueue_styles() {
    wp_register_style("jquery-ui", plugins_url("jquery-ui.min.css", __FILE__));
    wp_register_style("jquery-ui-accordion", plugins_url("jquery.ui.accordion.min.css", __FILE__));
    wp_register_style("jquery-ui-autocomplete", plugins_url("jquery.ui.autocomplete.min.css", __FILE__));
    wp_register_style("jquery-ui-button", plugins_url("jquery.ui.button.min.css", __FILE__));
    wp_register_style("jquery-ui-core", plugins_url("jquery.ui.core.min.css", __FILE__));
    wp_register_style("jquery-ui-datepicker", plugins_url("jquery.ui.datepicker.min.css", __FILE__));
    wp_register_style("jquery-ui-dialog", plugins_url("jquery.ui.dialog.min.css", __FILE__));
    wp_register_style("jquery-ui-menu", plugins_url("jquery.ui.menu.min.css", __FILE__));
    wp_register_style("jquery-ui-progressbar", plugins_url("jquery.ui.progressbar.min.css", __FILE__));
    wp_register_style("jquery-ui-resizable", plugins_url("jquery.ui.resizable.min.css", __FILE__));
    wp_register_style("jquery-ui-selectable", plugins_url("jquery.ui.selectable.min.css", __FILE__));
    wp_register_style("jquery-ui-slider", plugins_url("jquery.ui.slider.min.css", __FILE__));
    wp_register_style("jquery-ui-spinner", plugins_url("jquery.ui.spinner.min.css", __FILE__));
    wp_register_style("jquery-ui-tabs", plugins_url("jquery.ui.tabs.min.css", __FILE__));
    wp_register_style("jquery-ui-theme", plugins_url("jquery.ui.theme.min.css", __FILE__));
    wp_register_style("jquery-ui-tooltip", plugins_url("jquery.ui.tooltip.min.css", __FILE__));
  }

	add_action( 'wp_enqueue_scripts', 'jquery_ui_theme_enqueue_styles' );
}
