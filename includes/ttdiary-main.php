<?php
$ta_option_array= Array('threefour' => null);
add_shortcode( 'ttdiary', 'twitter_search_archive' );
				
//Doc: http://php.net/manual/en/function.date-default-timezone-set.php

if (!empty($ta_datetime_option)){
	date_default_timezone_set($ta_datetime_option);
} 

//Establish date variable.
$now=getdate();
//$date="2010-02-11T05:59:21Z";
$date=$now["year"];
//$date="2010";

//Establish page variable.
$page=1;

//You may want to limit it by a day, month, or year. In which case we will need this:
$keepGoing = true;
$threefour = $ta_option_array['threefour'];

function twitter_search_archive( $atts ) {
	global $keepGoing;

	$post_id = get_the_ID();

	add_post_meta($post_id, 'twitter_search_archive', '', true);
	$checkcache=get_post_meta($post_id, 'twitter_search_archive', true);
	
		
	//Create object to contain the archive.
	$archive = '<div class="twitter-archival-container ta">';
		
	extract( shortcode_atts( array(
		'for' => 'Chronotope',
		'within' => '',
		'order' => 'reverse',
		'user' => '',	// -- get only tweets of chosen user
		'cache' => 900,  // -- refresh every xx seconds
		'until' => ""   // -- stop refreshing after this date
	), $atts ) );
	
	//Add post meta to track and check the options that the user is feeding in. 
	add_post_meta($post_id, 'twitter_archive_control', '', true);
	add_post_meta($post_id, 'cache_refresh', time() + $cache, 'true');
	
	$refresh_at = get_post_meta($post_id, 'cache_refresh', true);
	
	$controlcheck = $for . "," . $within . "," . $order . "," . $user . "," . $cache . "," . $until;
	$checkcontrol = get_post_meta($post_id, 'twitter_archive_control', true);
	
	// check if shortcode hasn't changed or if it is time for an update
	if (!($controlcheck == $checkcontrol)  || ($refresh_at < time() && strtotime($until) > time() ) ) {
		
		
		$safefor = urlencode(strip_tags($for));
		if($user)
			$safefor .= "%20from:" . $user;
		
		$twitterquery = get_twitter_query($safefor);		
		$queryresults = execute_twitter_query($twitterquery, $within, $order, $keepGoing);
		
		$archive .= $queryresults;	
		$archive .= "</div><!--End of Twitter Archive-->";
		
		$archive = bbg_pb_twitterize($archive); // TODO: change to php function, perhaps do it earlier on $queryresults
		
		update_post_meta($post_id, 'twitter_search_archive', $archive);
		update_post_meta($post_id, 'twitter_archive_control', $controlcheck);
		update_post_meta($post_id, 'cache_refresh', time() + $cache);	
	
	} else { // -- nothing got changed
		$archive = get_post_meta($post_id, 'twitter_search_archive', true);
	}
	
	return $archive;
}

// -- does it really have to be a function
function get_twitter_query($query) {
	$file="http://search.twitter.com/search.atom?q=$query&rpp=100";
	return $file;
}

function execute_twitter_query($file, $datelimit, $ordered, $keepGoing) {

	$execute_archive = '';

	//If you don't want it to be reversed, we need to query the pages in proper order. 
	if ($ordered == 'reverse') {
		for($page=15;$page>=1;$page--) {
			$thefile="$file&page=$page";
			if (fopen ($file, "r")) {
				$xml = simplexml_load_file($thefile);
				if (!(empty($xml->entry))) {					
					$execute_archive .= output_data($xml,$datelimit,$ordered,$keepGoing);				
				}				
			} else {
				$execute_archive .= " Sorry, Twitter doesn't seem to be working right now. Try again later.";
			}
		}
	} else {
		for($page=1;$page<=15;$page++) {
			$thefile="$file&page=$page";
			if (fopen ($file, "r")) {
				$xml = simplexml_load_file($thefile);

				if (!(empty($xml->entry))){	
					$execute_archive .= output_data($xml,$datelimit,$ordered,$keepGoing);
				}	
			} else {
				$execute_archive .= " Sorry, Twitter doesn't seem to be working right now. Try again later.";
			}
		}
	}
	return $execute_archive;
}

function output_data($xml,$datelimit,$ordered,$keepGoing) {
	global $threefour;

	$output_archive = '';
	
	foreach($xml->entry as $entry)	{ //Oddly, that XML we took in is not yet an array. So let's turn it into one.
		$a[]=$entry;
	}
	if (is_array($a)) {		
		if ($ordered == 'reverse') { //I don't know, perhaps you don't want it in reverse chronological order. Let's give the option. 	
			$orderedxml=array_reverse($a);		
		} else { //The only other way I can think of is chronological, so there's no need for an else statement here. Put anything else in that field and you'll get it in chronological order. What, you got a better idea? Well, tell me about it!
			$orderedxml = $a;	
		}
		
		foreach($orderedxml as $entry) {
			$uri=$entry->author->uri;
			$name=$entry->author->name;
			$image=$entry->link[1]['href'];
			//Moving the date conversion up top. 
			$timestamp = $entry->published;
			$link=$entry->link[0]['href'];
			$unixtime = strtotime($timestamp);
			$datetime = date('d.n.Y h:i:s', $unixtime);	// TODO: Make this date a) user selectable format, b) possibility of relative date (ex. 2 days ago)
			
			//Ok, so here's where it gets complex... If you are running WP3.4, there is now an awesome oEmbed function that rendders tweets for us. 
			//See http://codex.wordpress.org/Embeds and http://core.trac.wordpress.org/browser/tags/3.4/wp-includes/media.php#L0 for more info.
			//But if you are running a WP version before that, no-go. 
			//So let's use this new function, but create a fallback. 
			//Remember we got the WordPress version before?
			//Well, to provide forward compatability, we need to convert it to a number.
			//Using this PHP function allows us to turn the string into a decimal number PHP understands. 
			//Let's get the current WordPress version. 
			$wpver = get_bloginfo('version');
			//Should get a string like '3.4' - for more info see http://core.trac.wordpress.org/browser/tags/3.4/wp-includes/version.php#L0						
			$floatWPVer = floatval($wpver);
			
			
			if (!empty($datelimit)) { //If you've designated a date to retrieve from, either a year, month or day, you can restrict Tweets that appear only to that period. 
				
				if(strstr($entry->published,$datelimit)) { //The given date string must be in the 2012-4-24 format If it matches the published time of the tweet, save it to the object. Otherwise, don't.
				
					
					if (($floatWPVer >= 3.4) && ($threefour == "yes")){ //Now, we check if the version of WordPress we are running is equal to or greater than 3.4.					
						$outputlink = (string) $entry->link[0]['href'];
						$output_archive .= wp_oembed_get($outputlink);
					} else {							
						$output_archive .= "<div class=\"ta-twitter_user ta\">
						<ul class=\"ta-ul\">
						<li class=\"ta-image ta\"><img class=\"ta-avatar ta\" src=\"$image\"></li>
						<li class=\"ta-published ta\"><a href=\"$link\">$datetime</a></li>
						<li class=\"ta-user ta\"><a href=\"$uri\" target=\"_blank\">$name</a></li>
						<li class=\"ta-description ta\">$entry->title</li>
						
						</ul>
						</div>";
						$keepGoing = true;
					}
				}
				else { 
					$keepGoing = false; 
				}
			} else { //End of datecheck. 
				//Now, we check if the version of WordPress we are running is equal to or greater than 3.4.
				if (($floatWPVer >= 3.4) && ($threefour == "yes")) {			
					$outputlink = (string) $entry->link[0]['href'];
					$output_archive .= wp_oembed_get($outputlink);
				} else {					
					$output_archive .= "<div class=\"ta-twitter_user ta\">
					
					<ul class=\"ta-ul\">
					<li class=\"ta-image ta\"><img class=\"ta-avatar ta\" src=\"$image\"></li>
					<li class=\"ta-published ta\"><a href=\"$link\">$datetime</a></li>
					<li class=\"ta-user ta\"><a href=\"$uri\" target=\"_blank\">$name</a></li>
					<li class=\"ta-description ta\">$entry->title</li>
					
					</ul>
					</div>";
				}
			} 
			
		} //end foreach.		
	} // end of if (is_array($a))
	return $output_archive;
}

//Function to make usernames and hashtags hot links via Boone Gorges - https://github.com/boonebgorges
// TODO: do it only once - serve-rside
function bbg_pb_twitterize( $content ) {
    // Turn @-mentions into links
    $content = preg_replace("/[@]+([A-Za-z0-9-_]+)/", "<a href=\"http://twitter.com/\\1\" target=\"_blank\">\\0</a>", $content );

    // Turn hashtags into links
    $content = preg_replace("/ [#]+([A-Za-z0-9-_]+)/", " <a href=\"http://twitter.com/search?q=%23\\1\" target=\"_blank\">\\0</a>", $content );
	
	//A little addition of my own for this situation, to turn links into links. -Aram
	$reg_exUrl = "/\040(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	$content = preg_replace($reg_exUrl, " <a href=\"\\0\">\\0</a>", $content );

    return $content;
} 






// Since anything that auto-styles Twitter hashtags and at signs will mess up the workings of the shortcode, here's a built in alternative. No need to run another plugin. 
// Adapted from code at http://stackoverflow.com/questions/4913555/find-twitter-hashtags-using-jquery-and-apply-a-link
function zs_twitter_linked() {
	wp_enqueue_script( 'jquery' );
	?>
		<script type="text/javascript">
		<!--Note here the \s at the begining of the regular expression here. This is to enforce that it will only select from hashtags with a space in front of them. Otherwise it may alter links to anchors.-->
			hashtag_regexp = /\s#([a-zA-Z0-9]+)/g;

			function linkHashtags(text) {
				return text.replace(
					hashtag_regexp,
					' <a class="hashtag" target="_blank" href="http://twitter.com/#search?q=$1">#$1</a>'
				);
			}

			jQuery(document).ready(function(){
				jQuery('p').each(function() {
					jQuery(this).html(linkHashtags(jQuery(this).html()));
				});
			});
			
			jQuery('p').html(linkHashtags(jQuery('p').html()));  

			
		</script>
		<script type="text/javascript">
			
			at_regexp = /\s\u0040([a-zA-Z0-9]+)/g;
			
			function linkAt(text) {
				return text.replace(
					at_regexp,
					' <a class="twitter-user" target="_blank" href="http://twitter.com/$1">@$1</a>'
				);
			}

			jQuery(document).ready(function(){
				jQuery('p').each(function() {
					jQuery(this).html(linkAt(jQuery(this).html()));
				});
			});
			
			jQuery('p').html(linkAt(jQuery('p').html()));  			
			
		</script>
	<?php
}

add_action('wp_head', 'zs_twitter_linked');
