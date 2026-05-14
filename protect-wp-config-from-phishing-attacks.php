<?php
/**
 * Plugin Name:       Protect wp-config.php from Phishing Attacks
 * Plugin URI:        https://thisismyurl.com/plugins/protect-wp-config-from-phishing-attacks/
 * Description:       Returns a 403 Forbidden response if the request URI contains "wp-config" outside the admin context. Protects wp-config.php and its backup variants from direct HTTP requests.
 * Version:           16.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Christopher Ross
 * Author URI:        https://thisismyurl.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       protect-wp-config-from-phishing-attacks
 *
 * @package ThisIsMyURL\ProtectWPConfig
 */

declare( strict_types=1 );

namespace ThisIsMyURL\ProtectWPConfig;

defined( 'ABSPATH' ) || exit;

const VERSION = '16.0.0';

/**
 * Intercept any request whose URI contains "wp-config" outside the admin.
 *
 * WordPress is already loaded when plugins run, so wp-config.php is never
 * served directly by PHP in a healthy install. This hook is a belt-and-
 * suspenders guard against misconfigured servers or file-inclusion attacks
 * that somehow route a request through the WP bootstrap.
 *
 * Hook: init — runs early enough to abort before any output is sent but
 * after WordPress has set up the request context.
 */
function block_wp_config_request(): void {
	$request_uri = isset( $_SERVER['REQUEST_URI'] )
		? sanitize_text_field( wp_unslash( (string) $_SERVER['REQUEST_URI'] ) )
		: '';

	if ( '' === $request_uri ) {
		return;
	}

	// Only act on requests that contain "wp-config" in the path.
	if ( false === strpos( $request_uri, 'wp-config' ) ) {
		return;
	}

	// Never interfere with wp-admin requests — admins legitimately access
	// paths that contain "wp-config" in some security/file-manager plugins.
	if ( is_admin() ) {
		return;
	}

	wp_die(
		esc_html__( 'Access to this file is forbidden.', 'protect-wp-config-from-phishing-attacks' ),
		esc_html__( 'Forbidden', 'protect-wp-config-from-phishing-attacks' ),
		array( 'response' => 403 )
	);
}
add_action( 'init', __NAMESPACE__ . '\\block_wp_config_request', 1 );
