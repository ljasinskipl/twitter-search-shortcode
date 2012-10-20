<div class="wrap">
	<h2>Twitter Diary</h2>
	<p>By <em>Studio Multimedialne ljasinski.pl</em></p>
	<hr />

	<div style="width:300px; float:right; margin-left: -300px;" class="metabox-holder"> 
		<div class="meta-box-sortables">
			<div class='postbox'>
				<h3 class='hndle'>
					<span>Donate</span>
				</h3>
				<div class='inside'>
					
					<p>If you like the plugin and want to keep it's development running, please consider a 
					small donation.</p>
					<div style="margin:10px auto; text-align:center;">
						<form name="paypal-trakttv" action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_donations">
							<input type="hidden" name="business" value="3KYX5TTQD5NWU">
							<input type="hidden" name="lc" value="US">
							<input type="hidden" name="item_name" value="Twitter Diary plugin">
							<input type="hidden" name="item_number" value="plugin settings">
							<input type="hidden" name="currency_code" value="USD">
							<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted">
							<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" onclick="document.paypal-trakttv.submit();" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" width="1" height="1">	
						</form>
					</div>			
				</div>
			</div>
		</div>
	</div>


	<div style="width:300px; float:right; margin-left: -300px; clear:right;" class="metabox-holder"> 
		<div class="meta-box-sortables">
			<div class='postbox'>
				<h3 class='hndle'>
					<span>Disclaimer</span>
				</h3>
				<div class='inside'>
					<p>This plugin is based on</p>
					<ul style="list-style-type:disc; margin-left:20px;">
						<li>Twitter Archival Shortcode by <a target="_blank" href="http://aramzs.me/twitterarchival">Aram Zucker-scharff</a>)</li>
						<li>some script from <a target="_blank" href="http://twitter.com/SLODeveloper">Daniel Thorogood</a> from #wjchat</li>
					</ul>
					<p>This plugin may or may not (personaly I think it is not, but it's not my opinion that matters here) violate Twitter's Terms of Service. Use at your own risk.</p>
					<p>I am not in any way liable for how you use or deploy this plugin.</p>
				</div>
			</div>
		</div>
	</div>	
	
	
	<div style="margin-right: 320px; width: auto; overflow:hidden;" class="metabox-holder">
		<div class="meta-box-sortables">
			<div class="postbox">
				<h3 class="hndle">
					<span>Settings</span>
				</h3>
				<div class="inside">
					<form action="options.php" method="post">
					
					<?php settings_fields('ttdiary-settings'); ?>
					<?php do_settings_sections('ttdiary'); ?>
					<br />
					<input class="button-primary" name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
					</form>
				</div>
			</div>
		</div>
	</div>	


	<div style="margin-right: 320px; width: auto;" class="metabox-holder">
		<div class="meta-box-sortables">
			<div class="postbox">
				<h3 class="hndle">
					<span>Documentation</span>
				</h3>
				<div class="inside">

					<h4>Shortcode syntax</h4>
					<code>[ttdiary for="term or hashtag" order="normal" title="Twitter Archive for" user="ljasinskipl" cache=900 until=""]</code>
					<dl>
						<dt>for</dt>
						<dd><code>for</code> option defines twitter search term or hashtag you want to archive in the post.</dd>

						<dt>order</dt>
						<dd><code>normal</code> means newest go first - just like normally in twitter. If you want your post show chronoligicaly (oldest first), use <code>order="reversed"</code></dd>

						<dt>user</dt>
						<dd>Use it, if you only want to display one user's tweets ex. your own.</dd>

						<dt>cache</dt>
						<dd>Number of seconds between refreshing the post. Longer values are better for site speed.</dd>

						<dt>until</dt>
						<dd>Specify a date (in format <code>YYYY-MM-DD</code>), after which your post will stop refreshing. Remember, that after more than 7 days your tweets may become unaccessible by search.</dd>
					</dl>
					<p>You don't need to specify all the options displayed above. All of them have their default values set (with the exception of <code>for</code>:</p>
					<ul>
						<li><code>order="normal"</code></li>
						<li><code>user=""</code></li>
						<li><code>cache=900</code></li>
						<li><code>until</code> - date of the post publishing (no refreshing at all)</li>
					</ul>
					<p>If you want to override the styling, just insert a file called <code>ljpl-ttdiary.css</code> into your theme directory. Note: this will completly disable built-in styling so be sure to include all the necessary styling</p>
				</div>
			</div>
		</div>
	</div>	
	
	<div class="clear"></div>
</div>
	


