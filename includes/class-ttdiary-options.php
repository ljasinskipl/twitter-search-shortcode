<?php

class TwitterDiaryOptions {

	var $useOEmbed;
	var $timezone;
	
	function __construct() {
		$settings = get_option( 'ttdiary-settings' );
		if( $settings['oEmbed'] )
			$this->useOEmbed = true;
			
		$this->timezone = $settings['timezone'];
		
		add_action( 'admin_menu', array( $this, 'addOptionsPage' ) );
		add_action( 'admin_init', array( $this, 'addSettings' ) );
	}
	
	function prepareForm() {
		
		
	}
	
	function addOptionsPage( ) {
		add_options_page ( 'Twitter Diary Options', 'Twitter Diary', 'manage_options', 'ttdiary', array( $this, 'displayOptionsPage' ) );
	}
	
	function displayOptionsPage( ) {
		include_once( LJPL_TTDIARY_DIR . 'includes/dashboard/ttdiary-template.php' );
	}
	
	function addSettings( ) {
		settings_fields('ttdiary');
		register_setting( 'ttdiary-settings' ,'ttdiary-settings', array( $this, 'validateSettings' ) );
		add_settings_section( 'ttdiary', '', array( $this, 'sectionSettingsText' ), 'ttdiary' );
		add_settings_field( 'ttdiary-timezone', 'Set Timezone for the diary', array( $this, 'settingTimezone' ), 'ttdiary', 'ttdiary' );
		add_settings_field( 'ttdiary-oembed', 'Use Twitter\'s oEmbed', array( $this, 'settingOEmbed' ), 'ttdiary', 'ttdiary' );
	}
	
	function sectionSettingsText() {
		echo '<p>Set some default options for your Twitter Diary.</p>';
	}
	
	function settingTimezone() {?>
		<select id="ttdiary-timezone" name="ttdiary-settings[timezone]">
		<?php
			$idents = DateTimeZone::listIdentifiers();
			foreach($idents as $option):
		?>
			<option value="<?php echo $option;?>" <?php selected( $this->timezone, $option );?>><?php echo $option;?></option>
		<?php endforeach;?>
		</select>
		<?php
	}
	
	function settingOEmbed() {
	?>
	<input type="checkbox" id="ttdiary-oembed" name="ttdiary-settings[oEmbed]" value="yes" <?php checked( $this->useOEmbed );?> /> Use WordPress 3.4 oEmbed to show Tweets? (May not work)
	<?php
	}
	
	function validateSettings( $input ) {
	
		$inputjson = json_encode($input);
		set_transient('ttdiary_input', $inputjson, 3600);
		

		$options['timezone'] = trim($input['timezone']);
		//die( preg_match( '!\w!i', $newinput['syndicate'] ) );
		if(!preg_match('/^[-_\w\/]+$/i', $options['timezone'])) {
			$options['timezone'] = '';
		}

		$options['oEmbed'] = trim($input['oEmbed']);
		if(!preg_match('/^[-_\w\/]+$/i', $options['oEmbed'])) {
			$options['oEmbed'] = '';
		}
		
		return $options;
	}
}
