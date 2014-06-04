<?php
/*
Plugin Name: Simple Slideshow Manager
Plugin URI: http://www.acurax.com
Description: A Simple 2 Use Slideshow Plugin Which Help You To Create Multiple Image or Video Slideshows That You Can Display On Your Theme, Page, Post and Sidebar
Author: Acurax
Version: 1.2.2
Author URI: http://www.acurax.com
License: GPLv2 or later
*/
/*
Copyright 2008-current  Acurax International  ( website : www.acurax.com )
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
?>
<?php

// *************** Admin function ***************

function rtp_slideshow_admin()
{
	include ('rtp_slideshow_option.php');

}

function rtp_slideshow_admin_manage_gallery()
{
	include ('rtp_slideshow_managegallery.php');

}

function rtp_slideshow_generate_shortcode()
{
	include ('rtp_slideshow_generate_shortcode.php');

}

function rtp_slideshow_admin_actions()
{
	$rtp_slideshow_misc_user_level = get_option('rtp_slideshow_misc_user_level');
	if ($rtp_slideshow_misc_user_level == "") {
		$rtp_slideshow_misc_user_level = "manage_options";
	}

	add_menu_page('Acurax Slideshow Configuration', __('Slideshow', 'simple-slideshow-manager') , $rtp_slideshow_misc_user_level, 'Acurax-Slideshow-Settings', 'rtp_slideshow_admin', '');
	add_submenu_page('', 'Manage Gallery', 'Add Images', $rtp_slideshow_misc_user_level, 'Acurax-Slideshow-Add-Images', 'rtp_slideshow_admin_manage_gallery');
	add_submenu_page('Acurax-Slideshow-Settings', __('Generate Shortcode', 'simple-slideshow-manager') , __('Generate Shortcode', 'simple-slideshow-manager') , $rtp_slideshow_misc_user_level, 'Acurax-Slideshow-Generate-Shortcode', 'rtp_slideshow_generate_shortcode');
}

if (is_admin()) {
	add_action('admin_menu', 'rtp_slideshow_admin_actions');
}

include_once ('functions.php');

// Adding WUM Starts Here

function rtp_slideshow_update($plugin_data, $r)
{

	// Get Current Plugin Data () Starts Here

	function current_plugin_info($value)
	{
		if (!function_exists('get_plugins')) require_once (ABSPATH . 'wp-admin/includes/plugin.php');

		$plugin_folder = get_plugins('/' . plugin_basename(dirname(__FILE__)));
		$plugin_file = basename((__FILE__));
		return $plugin_folder[$plugin_file][$value];
	} // Get Current Plugin Data () Starts Here
	$curr_ver = current_plugin_info('Version');
	define('CURRENT_VERSION', $curr_ver);
	$folder = basename(dirname(__FILE__));

	// readme contents

	$data = file_get_contents('http://plugins.trac.wordpress.org/browser/' . $folder . '/trunk/readme.txt?format=txt');
	if ($data) {
		$matches = null;
		$regexp = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote(CURRENT_VERSION) . '\s*=|$)~Uis';
		if (preg_match($regexp, $data, $matches)) {
			$changelog = (array)preg_split('~[\r\n]+~', trim($matches[1]));
			$ret = '<div style="color: #c00;font-size: small; margin-top:8px;margin-bottom:8px">The Simple Slideshow Manager Plugin has been updated. Here is a change list, so you can see what\'s been changed or fixed:</div>';
			$ret.= '<div style="font-weight: normal;">';
			$ret.= '<p style="margin: 5px 0; font-weight:bold; font-size:small">= Latest Version =</p>';
			$ul = false;
			$first = false;
			foreach($changelog as $index => $line) {
				if (preg_match('~^\s*\*\s*~', $line)) {
					if (!$ul) {
						$ret.= '<ul style="list-style: disc; margin-left: 20px;">';
						$ul = true;
						$first = true;
					}

					$line = preg_replace('~^\s*\*\s*~', '', $line);
					if ($first) {
						$ret.= '<li style="list-style-type:none;margin-left: -1.5em; font-weight:bold">Release Date:' . $line . '</li>';
						$first = false;
					}
					else {
						$ret.= '<li>' . $line . '</li>';
					}
				}
				else {
					if ($ul) {
						$ret.= '</ul><div style="clear: left;"></div>';
						$ul = false;
					}

					$ret.= '<p style="margin: 5px 0; font-weight:bold; font-size:small">' . $line . '</p>';
				}
			}

			if ($ul) {
				$ret.= '</ul>';
			}

			$ret.= '</div>';
		}
	}

	echo $ret;
}

/**
 * Add update messages that can be attached to the CURRENT release (not
 * this one), but only for 2.8+
 */
global $wp_version;

if (version_compare('2.8', $wp_version, '<=')) {
	global $pagenow;
	if ('plugins.php' === $pagenow) {

		// Better update message

		$file = basename(__FILE__);
		$folder = basename(dirname(__FILE__));
		$rtp_add_slideshow_u_notice = "in_plugin_update_message-{$folder}/{$file}";
		add_action($rtp_add_slideshow_u_notice, 'rtp_slideshow_update', 20, 2);
	}
}

// Adding WUM Ends Here

?>