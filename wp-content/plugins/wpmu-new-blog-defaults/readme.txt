=== New Blog Defaults ===
Contributors: DeannaS, kgraeme
Tags: WPMU, Wordpress Mu, Wordpress Multiuser, Blog Defaults, Set Defaults 
Requires at least: 2.8.1
Tested up to: 2.8.5.2
Stable tag: trunk



Allows site administrator to set the defaults for all new blogs created on server.

== Description ==
Included files:

* cets\_blog\_defaults.php

This plugin does the following:

1. Adds a new submenu to the site admin screen called "New Blog Defaults."
1. Allows the site administrator to visual set defaults for each of the major blog sections:

	1. General Settings
	1. Writing Settings
	1. Reading Settings
	1. Discussion Settings
	1. Privacty Settings
	1. Permalinks
	1. Miscellaneous Settings
	1. Theme
	1. Bonus Settings 


== Installation ==

1. Place the cets\_blog\_defaults.php file in the wp-content/mu-plugins folder.
1. Go to site admin -> new blog defaults and configure new blog defaults. Blogs created after settings are saved will use new blog defaults.



== Frequently Asked Questions ==
1. Can I use this to update current blog settings?

No, it's not designed to affect current blogs. Settings will only affect new blogs.

== Changelog ==
1.4 - Added Ability to add users to list of blogs with selected role. Added ability to close comments on the about page and hello world post.

1.3 - Upgrades for 2.8 - removed legacy code for < 2.7, added initial links and categories, added toggle for over-riding privacy settings

1.2.4 - bug fix for users signup page not correctly returning the name/link of the new blog and defaulting to the main blog.

1.2.3 - bug fix for tag and category base

1.2.2 - bug fix for permalinks on sub-domain installs

1.2.1 - updated for 2.7 release with additional features

1.0.2 - fixed bug with theme selector - now shows title of theme instead of name of template, and includes all themes available

1.0.1 - fixed bug with permalink structures 


