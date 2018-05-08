<?php session_start();
/*
 * Plugin Name: Calculator Shortcode
 * Plugin URI: http://aghadiinfotech.com/
 * Description: Calculator Plugin for <a href="http://http://levver.be/">http://levver.be</a> .
 * Version: 1.0
 * Author: Aghadi Infotech
 * Author URI: http://aghadiinfotech.com/
 */
/*	Include plugin function file	*/
if (!defined('CURR_PLUGIN_DIR')) {
	define('CURR_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
if (!defined('CURR_PLUGIN_URL')) {
	define('CURR_PLUGIN_URL', plugins_url('calculator-shortcode/'));
}
if( file_exists( CURR_PLUGIN_DIR.'inc/plugin_functions.php' ) ){
	include_once( CURR_PLUGIN_DIR.'inc/plugin_functions.php' );
}
?>