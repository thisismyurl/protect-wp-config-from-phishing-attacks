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

Returns a 403 to WordPress-routed front-end requests that target a config filename.

== Description ==

Protect WP Config File from Phishing Attacks returns a `403 Forbidden`
response to front-end requests that WordPress routes through its bootstrap
when the resolved request path targets a config filename — `wp-config.php`,
`wp-config.txt`, and common editor/backup variants such as
`wp-config.php.bak`, `.old`, `.save`, and `.orig`.

The match is on the **basename of the resolved request path**, not on a
substring of the URL. A blog post at `/how-to-edit-wp-config-php/` or a
search for `?s=wp-config` is left alone; only a request whose final path
segment is an actual config filename is blocked.

Plugin behavior:

* Runs early on `init`
* Sanitizes the incoming request URI, then matches the path basename against a config-filename pattern
* Returns a `403 Forbidden` response for matched requests
* Leaves `wp-admin` traffic untouched
* Stores no options and creates no database tables

== What this does NOT protect against ==

This plugin can only see requests that boot WordPress. A direct request for
a **static** file the webserver serves from disk — the classic
`wp-config.php.bak` plain-text leak — never reaches PHP, so the `init` hook
never fires and this plugin **cannot** block it.

To close the static-file threat, add a server-level rule. Examples:

Apache (`.htaccess` in the site root):

`<FilesMatch "^wp-config\.(php|txt)(\.(bak|old|save|orig|backup))?$">`
`    Require all denied`
`</FilesMatch>`

Nginx (server block):

`location ~* ^/wp-config\.(php|txt)(\.(bak|old|save|orig|backup))?$ { deny all; }`

A CDN/edge (Cloudflare, etc.) WAF rule blocking the same paths works too,
and is the strongest layer because it stops the request before it reaches
the origin at all.

The most reliable fix of all is to never leave config backups in the web
root: store backups outside the document root, or above the WordPress
install directory.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate it through **Plugins > Installed Plugins**.
3. No settings are required.
4. For full protection, add the server or edge rule from the "What this does NOT protect against" section above.

== Frequently Asked Questions ==

= Does this modify wp-config.php? =

No. It only inspects incoming requests and blocks WordPress-routed requests whose path basename is a config filename.

= Does this block static `wp-config.php.bak` files served by my webserver? =

No. Static files served directly by Apache or Nginx never load WordPress, so this plugin cannot see those requests. Add the server or edge rule documented above to cover that case.

= Will this affect admin access? =

No. Requests in admin context are bypassed.

== Changelog ==

= 16.6148 =
* Narrowed the match so only requests whose resolved path basename is a config filename are blocked — fixes false 403s on legitimate content like `/how-to-edit-wp-config-php/` and `?s=wp-config`.
* Corrected the description and readme to honestly state what the plugin blocks (WordPress-routed requests targeting config filenames), and added a "What this does NOT protect against" section recommending a server/.htaccess/edge rule for the static-backup-file leak the plugin structurally cannot intercept.
* Fixed the `uninstall.php` docblock (wrong package name and misspelled author).

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
