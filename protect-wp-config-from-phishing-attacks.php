<?php
/*
Plugin Name: Protect wp-config.php from Phishing Attacks
Plugin URI: http://thisismyurl.com/downloads/protect-wp-config-from-phishing-attacks/
Description: Returns a blank white page if people try to load the wp-config file (or backups of it) in a web browser. 
Author: Christopher Ross
Author URI: http://thisismyurl.com/
Version: 15.01
*/


/**
 * Protect wp-config.php from Phishing Attacks core file
 *
 * This file contains all the logic required for the plugin
 *
 * @link		http://wordpress.org/extend/plugins/protect-wp-config-from-phishing-attacks/
 *
 * @package 	Protect wp-config.php from Phishing Attacks
 * @copyright	Copyright (c) 2008, Chrsitopher Ross
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		Protect wp-config.php from Phishing Attacks 1.0
 */




/* if the plugin is called directly, die */
if ( ! defined( 'WPINC' ) )
	die;
	
	
define( 'THISISMYURL_PWPC_NAME', 'Protect wp-config.php from Phishing Attacks ' );
define( 'THISISMYURL_PWPC_SHORTNAME', 'Protect wp-config' );

define( 'THISISMYURL_PWPC_FILENAME', plugin_basename( __FILE__ ) );
define( 'THISISMYURL_PWPC_FILEPATH', dirname( plugin_basename( __FILE__ ) ) );
define( 'THISISMYURL_PWPC_FILEPATHURL', plugin_dir_url( __FILE__ ) );

define( 'THISISMYURL_PWPC_NAMESPACE', basename( THISISMYURL_PWPC_FILENAME, '.php' ) );
define( 'THISISMYURL_PWPC_TEXTDOMAIN', str_replace( '-', '_', THISISMYURL_PWPC_NAMESPACE ) );

define( 'THISISMYURL_PWPC_VERSION', '15.01' );

include_once( 'thisismyurl-common.php' );



/**
 * Creates the class required for ProtectWPConfig
 *
 * @author     Christopher Ross <info@thisismyurl.com>
 * @version    Release: @15.01@
 * @see        wp_enqueue_scripts()
 * @since      Class available since Release 15.01
 *
 */
if( ! class_exists( 'thissimyurl_ProtectWPConfig' ) ) {
class thissimyurl_ProtectWPConfig extends thisismyurl_Common_PWPC {

	/**
	  * Standard Constructor
	  *
	  * @access public
	  * @static
	  * @uses http://codex.wordpress.org/Function_Reference/add_action
	  * @since Method available since Release 15.01
	  *
	  */
	public function run() {
		add_filter( 'init', array( $this, 'wp_config_request' ) );
	}
	
	
	/**
	  * wp_config_request
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 15.01
	  *
	  * @todo figure out how to call this dynamically from within an enqueue function
	  *
	  */
	function wp_config_request() {
  
	  $url = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];	
		
	  if ( substr_count( $url , 'wp-config' ) > 0 && substr( $url, -1 ) != '/' && ! is_admin() )
		die();
		
	}
	
}
}

$thissimyurl_ProtectWPConfig = new thissimyurl_ProtectWPConfig;

$thissimyurl_ProtectWPConfig->run();