<?php

$ta_option_array = get_option('ta_options');
$ta_datetime_option = $ta_option_array['datetime'];

//Based on code at http://ottopress.com/2009/wordpress-settings-api-tutorial/

// add the admin options page
add_action('admin_menu', 'ttdiary_add_options_page');

function ttdiary_add_options_page() {
	include_once( LJPL_TTDIARY_DIR .  'includes/dashboard/common-topmenu.php' );
	ljpl_admin_menu();
	add_submenu_page('ljpl-admin', 'Twitter Diary', 'Twitter Diary settings', 'manage_options', 'ljpl_twitter_diary', 'ttdiary_options_template');
	// add_options_page('Options for Twitter Archival Shortcode plugin', 'Twitter Diary', 'manage_options', 'ta', 'ta_options_page');
}


// display the admin options page
function ttdiary_options_template() {
	include_once( LJPL_TTDIARY_DIR . 'includes/dashboard/ttdiary-template.php' );
}

// add the admin settings and such
add_action('admin_init', 'ttdiary_add_settings');
function ttdiary_add_settings(){
	register_setting( 'ttdiary_options', 'ttdiary_options', 'ta_options_validate' );
	add_settings_section('ttdiary_main', '', 'ttdiary_section_text', 'twitter_archive');
	add_settings_field('ta_datetime_select', 'Set Twitter Archive Timezone', 'ttdiary_settings_timezone', 'twitter_archive', 'ttdiary_main');
	add_settings_field('ta_threefour_select', 'Set Use of oEmbed', 'ta_settings_threefour', 'twitter_archive', 'ttdiary_main');
}

function ttdiary_section_text() {
	echo '<p>Set available defaults for your Twitter Archival Shortcodes.</p>';
}

function ttdiary_settings_timezone() {

	$options = get_option('ta_options');
	$setvalue = $options['datetime'];
	?>
		<select id="ta_options_datetime" name="ta_options[datetime]">
			<?php
			
				//echo '<option value="' . $setvalue . '">' . $setvalue . '</option>';
			
			?>
			<option value="America/New_York">America/New York</option>
			<option value="America/Los_Angeles">America/Los Angeles</option>
		
	<?php
	
	$idents = DateTimeZone::listIdentifiers();
	
	foreach ($idents as $timezone) {
		if ((!empty($setvalue)) && ($timezone == $setvalue)){	
			echo '<option value="' . $timezone . '" selected="selected">' . $timezone . '</option>';
		} else {
			echo '<option value="' . $timezone . '">' . $timezone . '</option>';
		}
	}
	echo '</select>';
}

function ta_settings_threefour() {
	// moved to class

	$options = get_option('ta_options');
	//pull in previous selection to display. 
	$setvalue = $options['threefour'];
	//If the user has selected yes, make sure it displays.
	?>
	<input type="checkbox" id="ta_threefour_select" name="ta_options[threefour]" value="yes" <?php checked( $setvalue == 'yes' );?> /> Use WordPress 3.4 oEmbed to show Tweets? (May not work)
	<?php
	
}

// validate our options
function ta_options_validate($input) {
	//moved to class
	$options = get_option('ta_options');
	
	$options['datetime'] = trim($input['datetime']);
	//die( preg_match( '!\w!i', $newinput['syndicate'] ) );
	if(!preg_match('/^[-_\w\/]+$/i', $options['datetime'])) {
		$options['datetime'] = '';
	}


	$options['threefour'] = trim($input['threefour']);
	if(!preg_match('/^[-_\w\/]+$/i', $options['threefour'])) {
		$options['threefour'] = '';
	}
	
	return $options;
}

