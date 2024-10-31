=== Reliable Twitter ===
Contributors: sparkweb
Donate link: http://www.soapboxdave.com/reliabletwitter/
Tags: twitter, widget, google, ajax, caching
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: 2.2.1

Adds a sidebar widget to display Twitter updates using the more-reliable Google Ajax API.

== Description ==

ATTENTION: Twitter has [announced](https://dev.twitter.com/docs/api/1.1/overview#JSON_support_only) that it is dropping support for RSS feeds on March 5, 2013. As such, it is not recommended that you use Reliable Twitter as it will not function properly after Twitter removes RSS feed support.

------------------------------------------------------------------------

Adds a sidebar widget to display Twitter updates using the more-reliable Google Ajax API.

The Problem: On many blogs, I need to add a little twitter badge to a front-page or sidebar. There are many, many great apps out there to do this including Twitter's own JavaScript badge. The problem is that Twitter's feed is very unreliable and only works about 50% of the time. That's completely unacceptable.

The Solution: After much looking around, I've hit upon a simple method for displaying tweets using Google's Ajax API. Google's reliable servers do all the caching for us and life is good again.

I used the excellent [Twitter Widget](http://wordpress.org/extend/plugins/twitter-widget/) app by Sean Spalding as a base and the Google Ajax concept by [Emmett Connolly](http://blog.thoughtwax.com/2007/04/a-more-reliable-twitter-badge).

== Installation ==

Copy the folder to your WordPress 
'*/wp-content/plugins/*' folder.

1. Activate the '*Reliable Twitter*' plugin in your WordPress admin '*Plugins*'
1. Go to '*Appearance / Widgets*' in your WordPress admin area.
1. Drag the '*Reliable Twitter*' widget to your sidebar.
1. Configure the options:
 2. *Account*: Your Twitter account ID, REQUIRED.
 2. *Title*: The heading you want to appear above your Twitters in the sidebar, defaults to 'Twitter Updates'.
 2. *Show*: The number of Tweets shown, defaults to 3.
 2. *Hide Replies*: Check if you don't want to show your replies to others.
 2. *Link Target*: The default link target, defaults to '_blank'.
 2. *Follow Me Text*: Text for a follow-me link after your twitter feed, optional and defaults to nothing.
 2. *Twitter Username*: Only needed if you enter follow me text, used for the link. Defaults to nothing.
 2. *Google API Key*: Your Google API key if you wish to include it, completely optional and not required in any way.
 2. *Loading Image*: Shows an animated icon while the feed is loading.
 
== Frequently Asked Questions ==

= My username isn't working. What's wrong? =

You need to use your Twitter ID # which is different from your username. Use [http://www.idfromuser.com/](http://www.idfromuser.com/) to look up your user ID.

= I'm getting Feed Loading Errors =

See the [Troubleshooting FAQ](http://www.soapboxdave.com/reliabletwitter/#troubleshooting) for some tips on fixing this problem.

= Why is my feed showing the three oldest entries instead of the three most current? =

We've run into this a few times. It seems to have to do with the way that the Google Feedfetcher grabs the feed for the first time. One thing that seems to fix this is to change your show # to 10 or 20, save, load the app, then change it back to the # you want. This somehow seems to refresh Google's caching.

= I don't use widgets. Can I just add this to my template directly? =

Yup! (And I don't blame you, widgets can be hard to deal with). Just put this code in your template:

`<?php
if (function_exists(reliabletwitter)) reliabletwitter(11469962);
?>`

...and replace '11469962' with your Twitter ID #. This will display the last three tweets on your page. There are some optional fields that you can pass in as well. Here are the available parameters:

`<?php
if (function_exists('reliabletwitter')) reliabletwitter($accountid, $show, $title, $target, $googleapikey, $hidereplies, $targetid, $loadingurl);
?>`


1. $accountid: Required
1. $show: Optional - the number of responses to return. Defaults to 3.
1. $title: Optional - will be wrapped in an H4 tag above your tweets. Defaults to nothing.
1. $target: Optional - The target placed in your links. Defaults to _blank.
1. $googleapikey: Optional - Lets you include your [Google Api Key](http://code.google.com/apis/ajaxfeeds/key.html) if you so desire. (I don't know of any benefits to doing this, though.) Defaults to nothing.
1. $hidereplies: Optional - Set to 1 if you want to only show your posts and not your replies to others.
1. $targetid: Optional - If you have multiple calls on the same page, set a unique ID in this field so that the content can be targeted in the right place.
1. $loadingurl: Optional - Enter the url for your "waiting" icon. If empty, the default will be used. If you don't want a waiting icon, enter "none". This loads in an li with class "reliabletwitter_title_loading" if you want to do extra CSS tweaking.


Here are some examples:

`<?php
if (function_exists('reliabletwitter')) reliabletwitter(11469962, 5, 'Twitter Updates', '_self');
?>`

Shows 5 tweets, with a title of "Twitter Updates", and the links target "_self".


`<?php
if (function_exists('reliabletwitter')) reliabletwitter(11469962, 1, 'Latest Twitter Update', '', '1', "none");
?>`

Shows 1 tweet, with a title of "Latest Twitter Update", and the links don't have a target attribute. Won't show replies or a waiting icon.

`<?php
if (function_exists('reliabletwitter')) reliabletwitter(11469962, 4, '', '_blank', '123456789');
?>`

Shows 4 tweets, with no title. The links have a target of "_blank" and an API key of "123456789" will be passed to Google.

== Screenshots ==

1. The options screen.


== Other == 

Plugin URI: http://www.soapboxdave.com/reliabletwitter/<br />
Author: David Hollander<br />
Author URI: http://www.soapboxdave.com/<br />

== Changelog ==

= 2.2.1 (Mar 16, 2012) =
* Removed console.log() entry. Shouldn't have been in production

= 2.2 (Dec 30, 2011) =
* Allows Custom RSS Feeds (for troubleshooting purposes)
* If encountered, show feed retrieval error

= 2.1.2 (Jul 25, 2011) =
* Fixed bug created in 2.1 causing an extra number of tweets to be shown (if you entered 1, 2 were shown).

= 2.1.1 (Jun 12, 2011) =
* Fixed bug created in 2.1 causing all responses to show when "hide replies" was turned on.

= 2.1 (Jun 11, 2011) =
* Improved JavaScript to ensure that we don't get stuck in an endless loop when there are too many hidden replies.

= 2.0 (Feb 21, 2011) =
* Upgraded to widget API so you can now put more than one widget in the sidebars.
* Added "Hide Replies" feature
* Optional loading icon shows while feed is loading
* IMPORTANT NOTE: Because of the structural change to the widget API there are a few things to keep in mind. Upgrading will temporarily break your implementation:
** After upgrading, immediately go to the widgets page and re-insert the Reliable Twitter widget into your sidebar. Your old settings will be pre-loaded, so just click "Save" and the upgrade will be complete.
** All ID-based references have been changed to classes. If your css was references #twitter_div, change it to .twitter_div.

= 1.3 (Apr 6, 2010) =
* Fixed the javascript to provide more accurate time.
* Added a classname to the date link for design options.

= 1.2 (Sept 18, 2009) =
* Added a feature to the widget which allows you to add a "Follow Me" link beneath your tweets. (Thanks to Marko for the excellent suggestion.)

= 1.1 (Sept 17, 2009) =
* Enabled more than four returns from Google.
* Added options for configurable link targets and an optional Google API key.
* Fixed the "x minutes ago" statement to be more accurate.
* Removed the default Google CSS which was adding unneccesary page load.

= 1.01 (Sept 16, 2009) =
* Quick JavaScript bugfix when Google doesn't return all fields.

= 1.0 (Sept 16, 2009) =
* Initial release

== Upgrade Notice ==

= 2.2.1 =
Removing incorrect console.log() call which could cause error if no console installed for end user

