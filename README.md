# Protect WP Config File from Phishing Attacks

[![WordPress](https://img.shields.io/badge/WordPress-6.4%2B-blue)](https://wordpress.org/plugins/protect-wp-config-from-phishing-attacks/) [![License](https://img.shields.io/badge/License-GPL--2.0-blue)](LICENSE)

Returns a `403 Forbidden` to WordPress-routed front-end requests whose resolved path targets a config filename (`wp-config.php` and common backup variants like `wp-config.php.bak` and `wp-config.php.old`). A small, focused hardening layer that pairs with a server or edge rule.

## Why this exists

Server misconfigurations sometimes serve `.bak` or `.old` copies of `wp-config.php` as plain text, which leaks the database password and authentication keys. This plugin blocks the slice of that threat it can actually see — requests routed through the WordPress bootstrap whose path basename is a config filename.

## What this does NOT protect against

This plugin only runs once WordPress has loaded. A direct request for a **static** `wp-config.php.bak` that the webserver serves from disk never reaches PHP, so the plugin cannot block it. Close that gap with a server, `.htaccess`, or CDN/edge rule (examples are in [readme.txt](readme.txt)), and keep config backups out of the web root entirely.

## Current implementation

- Namespaced plugin (`ThisIsMyURL\ProtectWPConfig`) with `declare(strict_types=1)`
- Matches the **basename of the resolved request path** against a config-filename pattern, not a substring of the URL — so legitimate content like `/how-to-edit-wp-config-php/` is never blocked
- Returns a `403 Forbidden` response via `wp_die()`
- Stores no options and creates no database tables

## Requirements

- WordPress 6.4+
- PHP 7.4+

## Status

Maintained and modernised for current WordPress and PHP baselines.

## Changelog

See [releases](../../releases) or [readme.txt](readme.txt).

---

## Support and donations

I build these tools because WordPress sites in the wild keep hitting the same problems, and a small, focused plugin is usually the right fix. They're free to use, with no tracking and no ads.

If one of them saves you time, here are the genuine ways to help:

- **Sponsor the work.** [GitHub Sponsors](https://github.com/sponsors/thisismyurl) is the simplest way, and the Sponsor button at the top of this repo lists it alongside Bitcoin, Dogecoin, PayPal, and Interac e-transfer. Any amount helps, and none of it is expected.
- **Contribute code or ideas.** A pull request, a bug report, or a tested edge case is worth as much as a donation. See [CONTRIBUTING.md](CONTRIBUTING.md) to get started.
- **Share it.** A note on [WordPress.org](https://profiles.wordpress.org/thisismyurl/), [GitHub](https://github.com/thisismyurl), or [LinkedIn](https://linkedin.com/in/thisismyurl) helps other people find work that might save them the same afternoon.

### Report issues and questions

- **Found a bug or want a feature?** Open an issue on the [Issues](../../issues) tab. Include your WordPress and PHP versions and the steps to reproduce it.
- **Have a question?** Start a thread on the [Discussions](../../discussions) tab.

### Contributing code

Code contributions are welcome. The short version:

1. Fork the repository and clone your fork.
2. Create a branch with a clear name, like `feature/short-descriptive-name`.
3. Make your change and test it against the edge cases.
4. Run the coding-standards check before you open the pull request.
5. Open a pull request that explains what changed and why.

The full workflow and standards live in [CONTRIBUTING.md](CONTRIBUTING.md). Contributing is never required, but it is always appreciated.

## About Christopher Ross

This plugin is built and maintained by [Christopher Ross](https://thisismyurl.com/), the WordPress development and technical SEO practice of Christopher Ross. I help teams build WordPress sites that stay secure, fast, and maintainable, and I write small, focused plugins like this one for the problems those sites keep running into.

### My background

- On the web since 1996, and in WordPress since 2007
- WordPress.org plugin developer with 19 plugins published since 2009
- Technical SEO practitioner focused on performance, security, and search visibility
- Lead instructor and curriculum architect at the M.L. Campbell Training Center, the Sherwin-Williams® international training facility for its industrial wood division

### Ways to connect

- **Website:** [thisismyurl.com](https://thisismyurl.com/)
- **WordPress.org:** [profiles.wordpress.org/thisismyurl](https://profiles.wordpress.org/thisismyurl/)
- **GitHub:** [github.com/thisismyurl](https://github.com/thisismyurl)
- **LinkedIn:** [linkedin.com/in/thisismyurl](https://linkedin.com/in/thisismyurl)

## Contributors

- **Christopher Ross** ([@thisismyurl](https://github.com/thisismyurl)) — author and maintainer
- Thanks to everyone who has reported issues, tested edge cases, and contributed code

## License

GPL-2.0-or-later — see [LICENSE](LICENSE) or [gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html).

---
*This project follows the [10 Core Pillars](PILLARS.md). Support quality work [here](https://github.com/sponsors/thisismyurl).*
