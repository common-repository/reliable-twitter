<?php /*

**************************************************************************
Plugin Name: Reliable Twitter
Plugin URI: http://www.soapboxdave.com/reliabletwitter/
Description: Adds a sidebar widget to display Twitter updates and uses the more-reliable Google AJAX API.
Version: 2.2.1
Author: David Hollander
Author URI: http://www.soapboxdave.com/

**************************************************************************

Copyright (C) 2011 SparkWeb Interactive, Inc.

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

**************************************************************************

Thanks you for using this plugin. Please see www.soapboxdave.com/reliable-twitter/
for installation instructions.

**************************************************************************/


define('RELIABLE_TWITTER_DIR',WP_PLUGIN_URL."/reliable-twitter");


function reliabletwitter($accountid, $show = 3, $title = '', $target = '_blank', $googleapikey = '', $hidereplies = '', $targetid = "", $loadingurl = "") {
		global $reliable_twitter_script_loaded;

		echo '<div class="twitter_div">'."\n";
		if ($title) echo "<h4 class=\"reliabletwitter_title\">".__($title)."</h4>\n";
		echo '<ul class="twitter_update_list" id="twitter_update_list_' . $targetid . '">';
		if ($loadingurl != "none") echo '<li class="reliabletwitter_title_loading"><img src="' . ($loadingurl ? htmlspecialchars($loadingurl) : RELIABLE_TWITTER_DIR."/wait.gif") . '" alt="" /></li>';
		echo '</ul>'."\n";
		echo "</div>\n";
		if (!isset($reliable_twitter_script_loaded)) {
			echo '<script type="text/javascript" src="http://www.google.com/jsapi'.($googleapikey ? "?key=".$googleapikey : "").'"></script>'."\n";
			echo '<script type="text/javascript" src="'. RELIABLE_TWITTER_DIR.'/reliable-twitter.js"></script>'."\n";
			$reliable_twitter_script_loaded = true;
		}
		echo '<script type="text/javascript">'."\n";
		echo "reliable_twitter_temp_loader = [];\n";
		echo 'reliable_twitter_temp_loader[0] = "' . htmlspecialchars($accountid) . "\";\n";
		echo 'reliable_twitter_temp_loader[1] = "' . htmlspecialchars($show) . "\";\n";
		echo 'reliable_twitter_temp_loader[2] = "' . htmlspecialchars($hidereplies) . "\";\n";
		echo 'reliable_twitter_temp_loader[3] = "' . htmlspecialchars($target) . "\";\n";
		echo 'reliable_twitter_temp_loader[4] = "' . htmlspecialchars($targetid) . "\";\n";
		echo 'reliable_twitter_loader[current_reliable_twitter_load] = [];'."\n";
		echo 'reliable_twitter_loader[current_reliable_twitter_load] = reliable_twitter_temp_loader;'."\n";
		echo 'current_reliable_twitter_load++;'."\n";
		echo '</script>';
}



add_action('widgets_init', 'reliable_twitter_load_widgets');

function reliable_twitter_load_widgets() {
	register_widget('Reliable_Twitter');
}


class Reliable_Twitter extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Reliable_Twitter() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'widget_reliabletwitter', 'description' => __('Adds a sidebar widget to display Twitter updates and uses the more-reliable Google AJAX API.', 'widget_reliabletwitter') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 325, 'height' => 400, 'id_base' => 'widget_reliabletwitter' );

		/* Create the widget. */
		$this->WP_Widget( 'widget_reliabletwitter', __('Reliable Twitter', 'widget_reliabletwitter'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget($args, $instance) {
		extract($args);
		

		// "$args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys." - These are set up by the theme
		extract($args);

		// These are our own options
		$accountid = $instance['accountid'];			// Your Twitter account id
		$title = $instance['title'];					// Title in sidebar for widget
		$show = $instance['show'];					// # of Updates to show
		$hidereplies = $instance['hidereplies'];		// Filter Out Replies
		$target = $instance['target'];				// Target of Links
		$googleapikey = $instance['googleapikey'];		// Google API Key
		$twitterusername = $instance['twitterusername'];	// Twitter Username for Follow Me Link
		$followmetext = $instance['followmetext'];		// Follow Me Text
		$loadingurl = $instance['loadingurl'];			// Loading Icon URL
		$rss = $instance['rss'];						// Loading Icon URL

		if ($rss) $accountid = $rss;

		// Write the Widget
		echo $before_widget;
		echo '<div class="twitter_div">'.$before_title.__($title).$after_title;
		echo '<ul class="twitter_update_list" id="twitter_update_list_' . $args['widget_id'] . '">'."\n";
		if ($loadingurl != "none") echo '<li class="reliabletwitter_title_loading"><img src="' . ($loadingurl ? htmlspecialchars($loadingurl) : RELIABLE_TWITTER_DIR."/wait.gif") . '" alt="" /></li>';
		echo "</ul>\n";
		if ($followmetext && $twitterusername) echo "<div class=\"twitter_followme\"><a href=\"http://twitter.com/" . htmlspecialchars($twitterusername) . "\" target=\"" . htmlspecialchars($target) . "\">" . htmlspecialchars($followmetext) . "</a></div>";
		echo '</div>';
		echo $after_widget;

		// Write the Javascript Includes
		global $reliable_twitter_script_loaded;
		if (!isset($reliable_twitter_script_loaded)) {
			echo '<script type="text/javascript" src="http://www.google.com/jsapi'.($googleapikey ? "?key=".htmlspecialchars($googleapikey) : "").'"></script>';
			echo '<script type="text/javascript" src="'. RELIABLE_TWITTER_DIR.'/reliable-twitter.js"></script>';
			$reliable_twitter_script_loaded = true;
		}

		echo '<script type="text/javascript">'."\n";
		echo "reliable_twitter_temp_loader = [];\n";
		echo 'reliable_twitter_temp_loader[0] = "' . htmlspecialchars($accountid) . "\";\n";
		echo 'reliable_twitter_temp_loader[1] = "' . htmlspecialchars($show) . "\";\n";
		echo 'reliable_twitter_temp_loader[2] = "' . htmlspecialchars($hidereplies) . "\";\n";
		echo 'reliable_twitter_temp_loader[3] = "' . htmlspecialchars($target) . "\";\n";
		echo 'reliable_twitter_temp_loader[4] = "' . htmlspecialchars($args['widget_id']) . "\";\n";
		echo 'reliable_twitter_loader[current_reliable_twitter_load] = [];'."\n";
		echo 'reliable_twitter_loader[current_reliable_twitter_load] = reliable_twitter_temp_loader;'."\n";
		echo 'current_reliable_twitter_load++;';
		echo '</script>';


	}

	/**
	 * Update the widget settings.
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['accountid'] = strip_tags( $new_instance['accountid'] );
		$instance['show'] = (int)strip_tags( $new_instance['show'] );
		$instance['hidereplies'] = strip_tags( $new_instance['hidereplies'] );
		$instance['target'] = strip_tags( $new_instance['target'] );
		$instance['googleapikey'] = strip_tags( $new_instance['googleapikey'] );
		$instance['twitterusername'] = strip_tags( $new_instance['twitterusername'] );
		$instance['followmetext'] = strip_tags( $new_instance['followmetext'] );
		$instance['loadingurl'] = strip_tags( $new_instance['loadingurl'] );
		$instance['rss'] = strip_tags( $new_instance['rss'] );
		
		//For Backwards Compatability
		delete_option('widget_reliabletwitter');

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => "Twitter Updates",
			'accountid' => "",
			'show' => "3",
			'hidereplies' => "",
			'target' => "_blank",
			'googleapikey' => "",
			'twitterusername' => "",
			'followmetext' => "",
			'loadingurl' => "",
			'rss' => ""
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		//Backwards Compatability
		if (is_array(get_option('widget_reliabletwitter'))) {
			$instance = wp_parse_args((array)get_option('widget_reliabletwitter'), $defaults);
		}

		
		// The form fields
		echo '<p style="text-align:right;">
				<label for="' . $this->get_field_id('accountid') . '"><span style="padding-bottom: 2px; cursor: help; border-bottom: 1px dotted darkgray;" title="Note that this is your ID #, not your username.">' . __('Account ID#') . '</span>
				<input style="width: 146px;" id="' . $this->get_field_id('accountid') . '" name="' . $this->get_field_name('accountid') . '" type="text" value="'.$instance['accountid'].'" />
				<span style="font-size: 10px;">(<a href="http://www.idfromuser.com/" target="_blank">Lookup</a>)</span>
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="' . $this->get_field_id('title') . '">' . __('Title:') . '
				<input style="width: 200px;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="'.$instance['title'].'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="' . $this->get_field_id('show') . '" style="float: left; margin: 0 0 0 85px;">' . __('Show:') . '
				<input style="width: 30px;" id="' . $this->get_field_id('show') . '" name="' . $this->get_field_name('show') . '" type="text" value="'.$instance['show'].'" />
				</label>
				<input style="margin: 5px 0 15px 15px; float: left;" id="' . $this->get_field_id('hidereplies') . '" name="' . $this->get_field_name('hidereplies') . '" type="checkbox"';
				checked($instance['hidereplies'], 'on');
				echo ' />
				<label for="' . $this->get_field_id('hidereplies') . '" style="float: left; margin: 3px 0 0 4px;">' . __('Hide Replies') . '</label>
				</p>';
		echo '<p style="text-align:right; clear: both;">
				<label for="' . $this->get_field_id('target') . '">' . __('Link Target:') . '
				<input style="width: 200px;" id="' . $this->get_field_id('target') . '" name="' . $this->get_field_name('target') . '" type="text" value="'.$instance['target'].'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="' . $this->get_field_id('followmetext') . '">' . __('Follow Me Text:') . '
				<input style="width: 141px;" id="' . $this->get_field_id('followmetext') . '" name="' . $this->get_field_name('followmetext') . '" type="text" value="'.$instance['followmetext'].'" />
				<span style="font-size: 10px;">(optional)</span>
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="' . $this->get_field_id('twitterusername') . '"><span style="padding-bottom: 2px; cursor: help; border-bottom: 1px dotted darkgray;" title="Only used if Follow Me text is entered.">' . __('Twitter Username:') . '</span>
				<input style="width: 147px;" id="' . $this->get_field_id('twitterusername') . '" name="' . $this->get_field_name('twitterusername') . '" type="text" value="'.$instance['twitterusername'].'" />
				<span style="font-size: 10px;">(for link)</span>
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="' . $this->get_field_id('googleapikey') . '">' . __('Google API Key:') . '
				<input style="width: 141px;" id="' . $this->get_field_id('accountid') . '" name="' . $this->get_field_name('googleapikey'). '" type="text" value="'.$instance['googleapikey'].'" />
				<span style="font-size: 10px;">(<a href="http://code.google.com/apis/loader/signup.html" target="_blank">optional</a>)</span>
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="' . $this->get_field_id('rss') . '"><span style="padding-bottom: 2px; cursor: help; border-bottom: 1px dotted darkgray;" title="Your Own RSS Feed">' . __('Custom RSS:') . '</span>
				<input style="width: 210px;" id="' . $this->get_field_id('twitterusername') . '" name="' . $this->get_field_name('rss') . '" type="text" value="'.$instance['rss'].'" />
				<span style="font-size: 10px;">(<a href="http://www.soapboxdave.com/reliabletwitter/#troubleshooting" target="_blank">details</a>)</span>
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="' . $this->get_field_id('loadingurl') . '">' . __('Loading Image:') . '
				<input style="width: 141px;" id="' . $this->get_field_id('loadingurl') . '" name="' . $this->get_field_name('loadingurl'). '" type="text" value="'.$instance['loadingurl'].'" />
				<span style="font-size: 10px;">(optional)</span></label>
				<div class="small">If you don\'t enter anything, the default icon will be used. You can specify your own url (<a href="http://ajaxload.info/" target="_blank">nice images here</a>). If you don\'t want to use this feature, enter "none".</div>
				</p>';
		echo '<p>Having trouble or getting feed errors? <a href="http://www.soapboxdave.com/reliabletwitter/#troubleshooting" target="_blank">Click here for some suggestions.</a>';
	}
}


?>