=== Miro Hristov RSS Reader Widget ===
Contributors: Miro Hristov
Donate link: http://host-ed.net/
Tags: rss reader, rss widget, widget, easy to use rss, rss reader widget
Tested up to: 2.9.2
Requires at least: 2.5
Stable tag: 1.1
Homepage: http://host-ed.net/blog/php-development/rss-reader-widget-our-first-official-wordpress-plugin
Author: Miro Hristov
Copyright (c) 2010 Automattic, Inc.

Miro Hristov RSS Reader Widget Shows The Last Items From Specified Feed

== Description ==
Miro Hristov RSS Reader Widget Shows The Last Items From Specified Feed
You can specify count of displayed items, date format, sponsored links, title, description count chars



== Installation ==
1. Copy mhr_widget_rss_reader to your plugins folder /wp-content/plugins/
2. Activate it through the plugin management screen.
3. Go to Appearance->Widgets and drag and drop the widget to wherever you want to show it.

== Changelog ==
= 1.1 =
* Allow multiple RSS feeds - they should be semi-collon separated(;) - for example http://www.blog1/feed;http://www.blog2/feed;http://www.blog1/feed;... 23 May 2010.


= 1.0 =
* First public release, this plugin has been running on WordPress.com 2.9.2, it is tested on WordPress system version>=2.5

== Frequently Asked Questions ==

= What to do if nothing is shown after activating plugin? =

There are 2 possible solutions. First be sure that feed URL is correct. Just open it and if it could not load then you should
set correct Feed URL. Otherwise most probably you should check php.ini settings. We have tested on hosting providers which 
do not allow loading external links