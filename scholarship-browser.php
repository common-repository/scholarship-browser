<?php
/*
Plugin Name: Scholarship Browser
Plugin URI: http://www.ausgetauscht.de/wp-plugin-scholarship-browser.htm
Description: Providing a Widget to show and interactively browse scholarships for German exchange students.
Author: pfauenauge
Version: 1.3
Author URI: http://www.ausgetauscht.de/wp-plugin-scholarship-browser.htm

	License: GPLv2 or later

	Copyright 2013 Stefan Pfau (stefan@ausgetauscht.de)

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


require_once plugin_dir_path(__FILE__).'functions.php';
require_once plugin_dir_path(__FILE__).'widget.php';



/**
 * Initing languages load
 * @return Array
 *
 * @version 1.0
 * @since 1.0
 */
function sb_init_lang() {
 load_plugin_textdomain( 'scholarship-browser', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
}
add_action('plugins_loaded', 'sb_init_lang'); // initing languages for frontend
add_action( 'admin_init', 'sb_init_lang' ); // initing languages for backend


add_action( 'wp_enqueue_scripts', 'sb_enque_head' );

/**
 * Enqueueing scripts
 * @return Array
 *
 * @version 1.0
 * @since 1.0
 */
function sb_enque_head() {
		
		wp_register_style( 'flags', plugins_url('flags.css', __FILE__) );
		wp_enqueue_style( 'flags' );		
		
		wp_enqueue_script(
			'jquery',
			'/wp-includes/js/jquery/jquery.js'
		);		
		
		wp_enqueue_script(
			'loader',
			plugins_url( 'assets/js/loader.js' , __FILE__ )
		);	
}



?>