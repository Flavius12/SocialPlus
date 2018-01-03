<?php

/**
 * @package SocialPlus
 * @version 1.0
 */
 
/*
 * Plugin Name: SocialPlus
 * Plugin URI: http://wordpress.org/plugins/socialplus/
 * Author: Flavius12
 * Author URI: https://www.flavius12.net
 * Version: 1.0
 * Description: Add custom share buttons to your blog. With SocialPlus you can add customizable share buttons on your blog pages, posts and homepage. You can also add the SocialPlus widget with your favourite social network buttons on your blog.
 * Domain Path: /languages/
 */

/*
	Copyright (C) 2018 Flavius12
	
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

defined('ABSPATH') or die('This script can\'t be called directly!');

define('SOCIALPLUS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SOCIALPLUS_PLUGIN_URL', plugin_dir_url(__FILE__));
add_action('plugins_loaded', 'socialplus_load_textdomain');
require_once(SOCIALPLUS_PLUGIN_PATH . 'socialplus-class.php');
register_activation_hook(__FILE__, array('SocialPlus', 'install'));
add_action('init', array('SocialPlus', 'init'));
require_once(SOCIALPLUS_PLUGIN_PATH . 'socialplus-themes.php');
require_once(SOCIALPLUS_PLUGIN_PATH . 'socialplus-wrapper.php');
require_once(SOCIALPLUS_PLUGIN_PATH . 'socialplus-widget.php');
if(is_admin()){
	require_once(SOCIALPLUS_PLUGIN_PATH . 'socialplus-admin.php');
	add_action('init', array('SocialPlusAdmin', 'init'));
}

function socialplus_load_textdomain(){
	load_plugin_textdomain('socialplus', false, basename(SOCIALPLUS_PLUGIN_PATH) . '/languages/');
}
?>