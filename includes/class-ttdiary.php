<?php
/**
 * TwitterDiary
 *
 */
class TwitterDiary {

	var $keepGoing = true;
	
	var $useOEmbed = false;	// we only default it to false
	var $timezone;

	private function getSettings( ) {
		$settings = get_option( 'ttdiary-settings' );
		if( $settings['threefour'] && $this->checkVersion )
			$this->useOEmbed = true;
			
		$this->timezone = $settings['datetime'];
	}

	/**
	 * checkVersion
	 * Checks, if WordPress version is high enough to use Twitter oEmbed
	 * @return boolean true if oEmbed possible
	 */
	private function checkVersion( ) {
		$installedVersion = get_bloginfo( 'version' );
		$minVersion = "3.4"
		
		return version_compare( $installedVersion, $minVersion, '>=' );
	}
	
	
	private function checkShortcodeChange( $atts ) {
		$pattern = get_post_meta( $post_id ) 'ttdiary_atts', true );
		$current = $for . "," . $within . "," . $order . "," . $user . "," . $cache . "," . $until;
		
		if( $pattern == $current )
			return false;
		else
			return true;
	}	
	
	private function buildQuery( $atts ) {
		$query = urlencode( strip_tags( $atts['for'] ) );
		if( $atts['user'] ) )
			$query .= '%20from:' . $user;
			
		return $query;
	}
	
	/**
	 * executeQuery
	 */
	private function executeQuery( $query ) {
	
		// -- build an URL
		$url = 'http://search.twitter.com/search.atom?q=' . $query . '&rpp=100';
		$result = wp_remote_get( $url, array( 'timeout' => 20 ) );
		
		// -- TODO: proper error handling
		if( is_wp_error( $result ) 
			return false;
		if( $result['response']['code'] != 200 )
			return false;
			
		return $result['body'];
	}

}
