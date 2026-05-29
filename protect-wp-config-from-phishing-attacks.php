<?php
/**
 * Plugin Name:       Protect wp-config.php from Phishing Attacks
 * Plugin URI:        https://thisismyurl.com/plugins/protect-wp-config-from-phishing-attacks/
 * Description:       Returns a 403 Forbidden response to WordPress-routed front-end requests that target a config filename (wp-config.php and common backup variants). Does NOT block static files served directly by the webserver — pair it with a server, .htaccess, or edge rule for that. See readme.
 * Version:           16.6148.2110
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

const VERSION = '16.6147';

/**
 * Pattern matching the basename of a request path that targets a config file.
 *
 * Matches `wp-config.php`, `wp-config.txt`, and the common editor/backup
 * variants (`wp-config.php.bak`, `.old`, `.save`, `.orig`, `~`, etc.). The
 * anchored `^…$` is deliberate: it matches the *basename* of the resolved
 * path, never a substring of the wider URI or query string, so legitimate
 * content like `/how-to-edit-wp-config-php/` or `?s=wp-config` is left alone.
 */
const CONFIG_FILENAME_PATTERN = '#^wp-config(?:\.php)?(?:\.(?:bak|old|save|orig|txt|swp|tmp|backup))?~?$#i';

/**
 * Block WordPress-routed front-end requests whose path targets a config file.
 *
 * IMPORTANT SCOPE NOTE. This hook only fires for requests that boot WordPress.
 * A request for a static `wp-config.php.bak` that the webserver serves from
 * disk never reaches PHP, so this plugin CANNOT intercept the classic
 * static-backup-file leak. Defend against that with a server, .htaccess, or
 * edge rule — see the plugin readme. What this catches is the narrower case:
 * a request that WordPress routes (a pretty-permalink path, a rewritten URL,
 * or a file-inclusion probe routed through the bootstrap) whose resolved
 * basename matches a config filename.
 *
 * Hook: init — runs after the request context is built but before output.
 */
function block_wp_config_request(): void {
	if ( is_admin() ) {
		return;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] )
		? sanitize_text_field( wp_unslash( (string) $_SERVER['REQUEST_URI'] ) )
		: '';

	if ( '' === $request_uri ) {
		return;
	}

	$basename = request_basename( $request_uri );

	if ( '' === $basename || 1 !== preg_match( CONFIG_FILENAME_PATTERN, $basename ) ) {
		return;
	}

	wp_die(
		esc_html__( 'Access to this file is forbidden.', 'protect-wp-config-from-phishing-attacks' ),
		esc_html__( 'Forbidden', 'protect-wp-config-from-phishing-attacks' ),
		array( 'response' => 403 )
	);
}
add_action( 'init', __NAMESPACE__ . '\\block_wp_config_request', 1 );

/**
 * Resolve the trailing path segment (basename) of a request URI.
 *
 * Strips the query string and fragment, then returns the last path segment.
 * Returns an empty string when the path resolves to a directory (trailing
 * slash) so directory requests never match the config-filename pattern.
 *
 * @param string $request_uri The raw, already-sanitised request URI.
 * @return string The decoded basename of the request path, or ''.
 */
function request_basename( string $request_uri ): string {
	$path = (string) wp_parse_url( $request_uri, PHP_URL_PATH );

	if ( '' === $path ) {
		return '';
	}

	// Decode percent-encoded probes (e.g. wp-config%2Ephp) before matching.
	$path     = rawurldecode( $path );
	$basename = basename( $path );

	// basename() echoes the last segment for a directory path; guard against
	// a trailing slash resolving to a parent segment we shouldn't match.
	if ( '/' === substr( $path, -1 ) ) {
		return '';
	}

	return $basename;
}
