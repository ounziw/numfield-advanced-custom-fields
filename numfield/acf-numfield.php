<?php
/*
Plugin Name: Num Field
Plugin URI: 
Description: *PHP5.3 Required* This plugin is an addon for Advanced Custom Fields. This plugin allows you to create a number field for custom fields. You can set the minimum/maximum boundaries for the custom fields. You can select a number, by dragging slider.
Version: 1.0
Author: Fumito MIZUNO
Author URI: http://wp.php-web.net/
License: GPL
*/

function acf_numfield_init(){
load_plugin_textdomain('acf-numfield', false, dirname(plugin_basename(__FILE__)).'/languages/' );
}
//add_action('plugins_loaded', 'acf_numfield_init');

if (function_exists('register_field')){
register_field('Num_field', WP_PLUGIN_DIR . '/numfield/numclass.php');
}	
