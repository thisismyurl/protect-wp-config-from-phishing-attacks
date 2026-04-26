<?php

/**
 *
 * thisismyurl.com common WordPress plugin files
 *
 * This file contains all the logic required for the plugin
 *
 * @package 	thisismyurl.com common WordPress plugin
 * @copyright	Copyright (c) 2014, Chrsitopher Ross
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		thisismyurl.com common WordPress plugin 15.01
 * @version		15.01
 *
 */


/* if the plugin is called directly, die */
if ( ! defined( 'WPINC' ) )
	die;




/**
 * Creates the class required for thisismyurl.com common WordPress plugin files
 *
 * @author	Christopher Ross <info@thisismyurl.com>
 * @version    Release: @15.01@
 * @since	 Class available since Release 14.11
 *
 */
class thisismyurl_Common_PWPC {


	/**
	  * Standard Constructor
	  *
	  * @access public
	  * @static
	  * @uses http://codex.wordpress.org/Function_Reference/add_action
	  * @since Method available since Release 14.11
	  *
	  */
    public function __construct() {

 		/* loaded for both */
 		add_action( 'plugins_loaded', 			array( $this, 'load_textdomain' ) );
		add_action( 'activate_plugin_name', 	array( $this, 'activate_plugin_name' ) );
		add_action( 'deactivate_plugin_name', 	array( $this, 'deactivate_plugin_name' ) );


		/* front end only */
		add_action( 'wp_enqueue_scripts', 		array( $this, 'enqueue_style' ) );


		/* admin only */
		add_action( 'admin_enqueue_scripts', 	array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_menu', 				array( $this, 'admin_menu' ) );
		add_filter( 'plugin_action_links_' . THISISMYURL_PWPC_FILENAME, array( $this, 'add_action_link' ), 10, 2 );


		register_uninstall_hook( 'uninstall.php', FALSE );


    }



	/**
	  * load_textdomain
	  *
	  * @access public
	  * @static
	  * @uses http://codex.wordpress.org/Function_Reference/load_plugin_textdomain
	  * @since Method available since Release 14.11
	  *
	  */
 	function load_textdomain() {

		/* loads the plugin text domain and language files */
		load_plugin_textdomain( THISISMYURL_PWPC_TEXTDOMAIN,
								false,
								THISISMYURL_PWPC_FILEPATH . '/langs'
		);

	}



	/**
	  * activate_plugin_name
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 15.01
	  *
	  */
 	function activate_plugin_name() {

	}



	/**
	  * deactivate_plugin_name
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 15.01
	  *
	  */
 	function deactivate_plugin_name() {

	}



	/**
	  * enqueue_style
	  *
	  * @access public
	  * @static
	  * @uses http://codex.wordpress.org/Function_Reference/wp_enqueue_style
	  * @since Method available since Release 14.11
	  *
	  */
    public function enqueue_style() {
		
		/* enqueue the style if it's found */
		if ( file_exists( dirname( __FILE__ ) . '/css/' . THISISMYURL_PWPC_NAMESPACE . '.css' ) ) {

			wp_enqueue_style( 	THISISMYURL_PWPC_NAMESPACE,
								THISISMYURL_PWPC_FILEPATHURL . 'css/' . THISISMYURL_PWPC_NAMESPACE . '.css',
								false,
								THISISMYURL_PWPC_VERSION
							);
		
		}

		/* enqueue the script if it's found */
		if ( file_exists( THISISMYURL_PWPC_FILEPATH . '/js/' . THISISMYURL_PWPC_NAMESPACE . '.js' ) ) {

			wp_enqueue_style( 	THISISMYURL_PWPC_NAMESPACE,
								THISISMYURL_PWPC_FILEPATHURL . 'js/' . THISISMYURL_PWPC_NAMESPACE . '.js',
								false,
								THISISMYURL_PWPC_VERSION
							);
		
		}


	}



	/**
	  * admin_enqueue_scripts
	  *
	  * @access public
	  * @static
	  * @uses http://codex.wordpress.org/Function_Reference/wp_enqueue_style
	  * @since Method available since Release 14.12
	  *
	  */
	function admin_enqueue_scripts() {


		if ( isset( $_GET['page'] ) ) {

			/* only load this function on the correct admin pages */
			if ( THISISMYURL_PWPC_TEXTDOMAIN . '_settings_page' != $_GET['page'] )
			   return;

			wp_register_style(  'thisismyurl-common',
								THISISMYURL_PWPC_FILEPATHURL . 'css/thisismyurl-common.css',
								false,
								THISISMYURL_PWPC_VERSION
							);
		
		    wp_enqueue_style( 'thisismyurl-common' );

			/* enqueue a special admin style if it's found */
			if ( file_exists( THISISMYURL_PWPC_FILEPATH . 'css/' . THISISMYURL_PWPC_NAMESPACE . '-admin.css' ) ) {

				wp_enqueue_style( 	THISISMYURL_PWPC_NAMESPACE,
									THISISMYURL_PWPC_FILEPATHURL . 'css/' . THISISMYURL_PWPC_NAMESPACE . '-admin.css',
									false,
									THISISMYURL_PWPC_VERSION
								);
			
			}

			/* enqueue a special admin script if it's found */
			if ( file_exists( THISISMYURL_PWPC_FILEPATH . 'js/' . THISISMYURL_PWPC_NAMESPACE . '-admin.js' ) ) {

				wp_enqueue_style( 	THISISMYURL_PWPC_NAMESPACE,
									THISISMYURL_PWPC_FILEPATHURL . 'js/' . THISISMYURL_PWPC_NAMESPACE . '-admin.js',
									false,
									THISISMYURL_PWPC_VERSION
								);
			
			}


		}

	}



	/**
	  * admin_menu
	  *
	  * @access public
	  * @static
	  * @uses http://codex.wordpress.org/Function_Reference/add_options_page
	  * @since Method available since Release 14.12
	  *
	  * @todo change the parent menu if there is no settings
	  *
	  */
	function admin_menu() {

		/* add the page, set capacities etc */
		add_options_page( 	THISISMYURL_PWPC_SHORTNAME,
							THISISMYURL_PWPC_SHORTNAME,
							'manage_options',
							THISISMYURL_PWPC_TEXTDOMAIN . '_settings_page',
							array( $this, 'settings_page' )
						);


		/* remove the menu item from settings */
		remove_submenu_page( 'options-general.php', THISISMYURL_PWPC_TEXTDOMAIN . '_settings_page' );

	}



	/**
	  * plugin_action_links
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 14.12
	  *
	  */
	function add_action_link( $links, $file ) {

		static $this_plugin;

		if( ! $this_plugin )
			$this_plugin = plugin_basename( __FILE__ );

		if( dirname( $file ) == dirname( $this_plugin ) ) {
			$links[] = sprintf( '<a href="options-general.php?page=%s">%s</a>',
								THISISMYURL_PWPC_TEXTDOMAIN . '_settings_page',
								__( 'Settings', THISISMYURL_PWPC_TEXTDOMAIN )
							);
		}

		return $links;

	}



	/**
	  * plugin_action_links
	  *
	  * @access public
	  * @static
	  * @since Method available since Release 14.12
	  *
	  */
	function settings_page() {


	?>
	<div id="thisismyurl-settings" class="wrap">
		<div class="thisismyurl-icon32"><br /></div>
		<h2><?php echo THISISMYURL_PWPC_NAME; ?></h2>



	   <h3><?php _e( 'General settings', THISISMYURL_PWPC_TEXTDOMAIN ); ?></h3>
		<p><?php printf( __( 'The plugin has no settings, once activated it will work automatically. For further details, please view the <a href="%sreadme.txt">readme.txt</a> file included with this release.', THISISMYURL_PWPC_TEXTDOMAIN ), plugin_dir_url( __FILE__ ) ); ?></p>


	</div>

    <div id="donate">

	  <h3><?php _e( 'How to support the software', THISISMYURL_PWPC_TEXTDOMAIN ); ?></h3>

	   <p><?php _e( 'Open source software such as this free WordPress plugin only work through the hard work of community members, volunteering their time or resources to make the software freely available. If you would like to show your support for this software, please consider donating towards the development effort.', THISISMYURL_PWPC_TEXTDOMAIN ); ?></p>
	   <p><?php _e( 'Here is how you can help:', THISISMYURL_PWPC_TEXTDOMAIN ); ?></p>

	   <ul>
		  <li><a href="https://wordpress.org/plugins/<?php echo THISISMYURL_PWPC_NAMESPACE; ?>/"><?php _e( 'Give it a great review on WordPress.org;', THISISMYURL_PWPC_TEXTDOMAIN ); ?></a></li>
		  <li><a href="https://wordpress.org/support/plugin/<?php echo THISISMYURL_PWPC_NAMESPACE; ?>"><?php _e( 'Offer free support in the plugin forums;', THISISMYURL_PWPC_TEXTDOMAIN ); ?></a></li>
		  <li><a href="https://github.com/thisismyurl/<?php echo THISISMYURL_PWPC_NAMESPACE; ?>/issues"><?php _e( 'Report an issue, or suggest feature request;', THISISMYURL_PWPC_TEXTDOMAIN ); ?></a></li>
		  <li><a href="http://codex.wordpress.org/I18n_for_WordPress_Developers"><?php _e( 'Translate the plugin into a local language;', THISISMYURL_PWPC_TEXTDOMAIN ); ?></a></li>
		  <li><a href="http://twitter.com/home?status=<?php printf( __( 'Thanks @thisismyurl for %s!', THISISMYURL_PWPC_TEXTDOMAIN ), THISISMYURL_PWPC_NAME );?>"><?php _e( 'Tell your friends about the plugin on Twitter;', THISISMYURL_PWPC_TEXTDOMAIN ); ?></a></li>
	   </ul>

	   <p><?php _e( 'Any support is greatly appreciated, and I hope you enjoy using this free plugin for WordPress.', THISISMYURL_PWPC_TEXTDOMAIN ); ?></p>

	   <form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypal_form">
	  <p><select name="amount">
	   	<option value="5"><?php _e( 'Donate $5', THISISMYURL_PWPC_TEXTDOMAIN );?></option>
		  <option value="10" selected><?php _e( 'Donate $10', THISISMYURL_PWPC_TEXTDOMAIN );?></option>
		  <option value="20"><?php _e( 'Donate $20', THISISMYURL_PWPC_TEXTDOMAIN );?></option>
	   </select>&nbsp;<input type="submit" value="Donate" class="button" /></p>


	   <input name="cmd" type="hidden" value="_donations" />
	   <input name="business" type="hidden" value="info@thisismyurl.com" />
	   <input name="item_name" type="hidden" value="<?php echo THISISMYURL_PWPC_NAME; ?>" />

	   <input name="currency_code" type="hidden" value="USD" />
	   </form>


	   <p>&#8212;&nbsp;<a href="http://thisismyurl.com/"><?php _e( 'Christopher Ross', THISISMYURL_PWPC_TEXTDOMAIN ); ?></a>&nbsp;(<a href="http://twitter.com/thisismyurl"><?php _e( '@thisismyurl', THISISMYURL_PWPC_TEXTDOMAIN ); ?></a>)</p>


	</div>

    <div class="clear"></div>


    	<?php

	}

}