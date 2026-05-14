# Protect WP Config File from Phishing Attacks

Returns a blank page if anyone tries to load `wp-config.php` (or common backup names like `wp-config.php.bak`, `wp-config.php.old`) directly in a web browser. A small but commonly-needed hardening layer.

[![WordPress.org](https://img.shields.io/wordpress/plugin/installs/protect-wp-config-from-phishing-attacks.svg)](https://wordpress.org/plugins/protect-wp-config-from-phishing-attacks/)

## Why this exists
Server misconfigurations sometimes serve `.bak`/`.old` copies of `wp-config.php` as plain text — leaking the database password and authentication keys. This plugin defensively returns a blank response for any direct-load attempt against config-file paths.

## Current implementation
- Namespaced plugin (`ThisIsMyURL\\ProtectWPConfig`) with `declare(strict_types=1)`
- Sanitized `REQUEST_URI` checks using WordPress sanitization helpers
- Returns a `403 Forbidden` response via `wp_die()`
- Legacy `thisismyurl-common.php` scaffold removed

## Status
Maintained and modernized for current WordPress/PHP baselines.

## License
GPL-2.0-or-later.


---
*This project follows the [10 Core Pillars](PILLARS.md). Support quality work [here](https://github.com/sponsors/thisismyurl).*

