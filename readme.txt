=== Protect WP Config File from Phishing Attacks ===
Contributors: christopherross
Plugin URI: https://thisismyurl.com/plugins/protect-wp-config-from-phishing-attacks/
Tags: wp-config, security, hardening, phishing, config
Requires at least: 6.4
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 16.6147
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Returns a 403 response when requests target wp-config-style paths.

== Description ==


Protect WP Config File from Phishing Attacks blocks direct HTTP requests that include `wp-config` in the request URI.

This helps reduce exposure from misconfigured servers where config backups (`wp-config.php.bak`, `.old`, etc.) could be requested directly.

Plugin behavior:

* Runs early on `init`
* Sanitizes incoming request URI before checks
* Returns a `403 Forbidden` response for blocked requests
* Leaves `wp-admin` traffic untouched

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate it through **Plugins > Installed Plugins**.
3. No settings are required.

== Frequently Asked Questions ==

= Does this modify wp-config.php? =

No. It only inspects incoming request URIs and blocks suspicious direct access attempts.

= Will this affect admin access? =

No. Requests in admin context are bypassed.

== Changelog ==

= 16.6147 =
* Unified plugin versioning to the x.Yddd calendar-version scheme.
* Confirmed compatibility with WordPress 7.0.


= 16.0.0 =
* Complete modernization with namespace and strict types.
* Removed legacy `thisismyurl-common.php` dependency.
* Added sanitized request handling using `sanitize_text_field( wp_unslash() )`.
* Standardized 403 responses via `wp_die()`.
* Added modern plugin headers and SPDX license metadata.

= 15.01 =
* Legacy maintenance release.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 16.0.0 =
Major internal refactor with no settings migration required.

== Screenshots ==

1. No UI screens. This plugin runs silently in the background.
