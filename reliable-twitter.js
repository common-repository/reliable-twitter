google.load("feeds", "1", {"nocss" : true});

var current_reliable_twitter_load = 0;
var reliable_twitter_loader = [];

function initialize() {
	for (var i=0; i<reliable_twitter_loader.length; i++) {
		if (reliable_twitter_loader[i][0] != '') {
			get_reliable_twitter_feed(reliable_twitter_loader[i][0], reliable_twitter_loader[i][1], reliable_twitter_loader[i][2], reliable_twitter_loader[i][3], reliable_twitter_loader[i][4])
		}
	}
}

function get_reliable_twitter_feed(accountid, shownumber, reliable_twitter_hidereplies, reliable_twitter_target, target_id) {
	var feedReturn = "";
	if (accountid.substr(0,7) == "http://" || accountid.substr(0,8) == "https://") {
		var feed = new google.feeds.Feed(accountid);
	} else {
		var feed = new google.feeds.Feed("http://twitter.com/statuses/user_timeline/" + accountid + ".rss");
	}
	feed.setNumEntries(shownumber + 20);
	feed.load(function(result) {
	if (result.error) {
		document.getElementById("twitter_update_list_"+target_id).innerHTML = "<li>" + result.error.message + "</li>";
	}
	if (!result.error) {
		if (reliable_twitter_target) reliable_twitter_target = ' target="' + reliable_twitter_target + '"';
		var username = result.feed.title.substring(10);
		if (shownumber >= result.feed.entries.length) shownumber = result.feed.entries.length;
		var i = 0;
		var j = 1;
		for (i=0;i < result.feed.entries.length;i++) {
			var entry = result.feed.entries[i];
			if (entry != undefined && j <= shownumber) {
				var tweetcontents = entry.title.substring(username.length + 2) + " ";
				var created_at = entry.publishedDate;
				if (reliable_twitter_hidereplies == "" || (reliable_twitter_hidereplies != "" && tweetcontents.substr(0,1) != "@")) {
					tweetcontents = tweetcontents.parseURL(reliable_twitter_target).parseUsername(reliable_twitter_target).parseHashtag(reliable_twitter_target);
					feedReturn += "<li>";
					feedReturn += "<span>" + tweetcontents + "</span>";
					feedReturn += "<a style=\"font-size:85%\" href=\"http://twitter.com/"+username+"\"" + reliable_twitter_target + " class=\"twitterdatelink\">" + relative_time(created_at) + "</a>";
					feedReturn += "</li>\n";
					j++;
				}
			}
		}
		document.getElementById("twitter_update_list_"+target_id).innerHTML = feedReturn;
	}});
}

google.setOnLoadCallback(initialize);

String.prototype.parseURL = function(reliable_twitter_target) {
	return this.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&\?\/.=]+/g, function(url) {
		return '<a href="' + url + '"' + reliable_twitter_target + '>' + url + '</a>';
	});
};
String.prototype.parseUsername = function(reliable_twitter_target) {
	return this.replace(/[@]+[A-Za-z0-9-_]+/g, function(u) {
		var username = u.replace("@","")
		return '<a href="http://twitter.com/' + username + '"' + reliable_twitter_target + '>' + u + '</a>';
	});
};
String.prototype.parseHashtag = function(reliable_twitter_target) {
	return this.replace(/[#]+[A-Za-z0-9-_]+/g, function(t) {
		var tag = t.replace("#","%23")
		return '<a href="http://search.twitter.com/search?q=' + tag + '"' + reliable_twitter_target + '>' + t + '</a>';
	});
};

function relative_time(time_value) {
   var parsed_date = Date.parse(time_value);

   var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
   var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);

   if(delta < 60) {
       return 'less than a minute ago';
   } else if(delta < 120) {
       return 'about a minute ago';
   } else if(delta < (45*60)) {
       return (parseInt(delta / 60)).toString() + ' minutes ago';
   } else if(delta < (90*60)) {
           return 'about an hour ago';
       } else if(delta < (24*60*60)) {
       return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
   } else if(delta < (48*60*60)) {
       return '1 day ago';
   } else {
       return (parseInt(delta / 86400)).toString() + ' days ago';
   }
}