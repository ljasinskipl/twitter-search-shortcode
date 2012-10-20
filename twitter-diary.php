<?php
/*
Plugin Name: Twitter Diary
Plugin URI: http://www.ljasinski.pl/portfolio/wordpress-plugins/twitter-diary/
Version 0.9
Description: Insert live updating diary of tweets into your post. Useful for social events, conferences etc.
Author: Łukasz Jasiński
Author URI: http://www.ljasinski.pl
Licence: GPL2
*/

/*

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

/******************************************************************************\

This plugin is based on

- Twitter Archival Shortcode by Aram Zucker-scharff (http://aramzs.me/twitterarchival)
- some script from Daniel Thorogood (http://twitter.com/SLODeveloper) from #wjchat
- styling: Kim Bui (http://twitter.com/kimbui)

********************************************************************************

This plugin may or may not (personaly I think it is not, but it's not my opinion
that matters here) violate Twitter's Terms of Service. Use at your own risk.

I am not in any way liable for how you use or deploy this plugin.

\******************************************************************************/


// -- path definitions
define('LJPL_TTDIARY_DIR', plugin_dir_path(__FILE__));
define('LJPL_TTDIARY_URL', plugin_dir_url(__FILE__));

// -- activation, deactivation and uninstall

register_activation_hook(__FILE__, 'ttdiary_activation');
register_deactivation_hook(__FILE__, 'ttdiary_deactivation');

function ttdiary_activation( ) {
	// -- register uninstaller
	register_uninstall_hook( __FILE__, 'ttdiary_uninstall' );
}

function ttdiary_deactivation( ) {
	return 1;
}

function ttdiary_uninstall( ) {
	//TODO: Clean all unused metadata;
	//TODO: embed diaries into post for good (not filter before show)
	return 1;
}


// -- dashboard options
//include_once( LJPL_TTDIARY_DIR . 'includes/dashboard/ttdiary-func.php' );
include_once( LJPL_TTDIARY_DIR . 'includes/class-ttdiary-options.php' );
$ttdiaryOptions = new TwitterDiaryOptions( );

// -- main function file
include_once( LJPL_TTDIARY_DIR . 'includes/ttdiary-main.php' );
include_once( LJPL_TTDIARY_DIR . 'includes/ttdiary-styling.php' );



