<?php
/**
 * Uninstall script.
 *
 * Runs when the plugin is deleted through the WordPress admin. This plugin
 * stores no options, tables, or scheduled events, so there is nothing to
 * remove — the guard below simply prevents direct access.
 *
 * @package   ThisIsMyURL\ProtectWPConfig
 * @copyright Copyright (c) 2008, Christopher Ross
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 * @since     15.01
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
