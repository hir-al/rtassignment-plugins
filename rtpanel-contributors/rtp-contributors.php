<?php
/*
Plugin Name: rtPanel Contributors Meta Box
Description: This plugin adds list of authors metabox to post
Version: 2.5
Author: rtcamp
Author URI: https://rtcamp.com
Contributors: rtCampers ( https://rtcamp.com/about/rtcampers/ )
License: GNU General Public License, v2 (or newer)
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// List Authors

add_action("admin_init", "users_meta_init");

function users_meta_init()
{
	add_meta_box("users-meta", "Contributors", "users", "post", "normal", "high");
}

// function to display list of authors in select box in post

function users()
{
	global $post;
	$user_args = array(
		'role' => 'Author',
		'orderby' => 'display_name'
	);
	$wp_user_query = new WP_User_Query($user_args);
	$authors = $wp_user_query->get_results();
	$output = '';
	if (!empty($authors)) {
		$output.= '<ul class="categorychecklist form-no-clear">';
		foreach($authors as $author) {
			$author_info = get_userdata($author->ID);
			$authors_array = explode(",", get_post_meta($post->ID, 'contributor', true));
			if (in_array($author_info->ID, $authors_array)) {
				$author_selected = 'checked';
			}
			else {
				$author_selected = '';
			}

			$output.= '<li>';
			$output.= '<label class="selectit">';
			$output.= '<input type="checkbox" name="contributor[]" value="' . $author_info->ID . '" ' . $author_selected . '>' . $author_info->first_name . ' ' . $author_info->last_name;
			$output.= '</label></li>';
		}

		$output.= '</ul>';
	}
	else {
		$output.= _x('No Contributor found.', 'rtPanel');
	}

	echo $output;
}

// Save Meta Details

add_action('save_post', 'save_userlist');

function save_userlist()
{
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	}

	if (isset($_POST["contributor"]) && !empty($_POST["contributor"])) update_post_meta($post->ID, "contributor", implode(",", $_POST["contributor"]));
}
?>