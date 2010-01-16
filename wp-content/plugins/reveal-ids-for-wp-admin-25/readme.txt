=== Reveal IDs for WP Admin ===
Contributors: Alphawolf
Donate link: http://www.schloebe.de/donate/
Tags: reveal, id, wp-admin, hidden, category, post, page, media, links, capability, user, restore, comments
Requires at least: 2.5
Tested up to: 2.9.1
Stable tag: trunk

What this plugin does is to reveal most removed IDs on admin pages, as it was in versions prior to 2.5.

== Description ==

With WordPress 2.5 being released, the IDs on all admin pages have been removed as it is. Propably due to the fact that the common user dont need them. However, for advanced WordPress Users/ developers those IDs were quite interesting for some plugins or template tags.

What this plugin does is to reveal most removed entry IDs on admin pages, showing the entry IDs, as it was in versions prior to 2.5

**Features:**

* Following IDs can be revealed: Posts, Pages, Categories, Links, Media, Users, Comments, Link categories, Tags (WP 2.8 and above)
* Each ID can be de-/activated seperately, plus you can **allow/permit user roles to see the IDs**

**Included languages:**

* English
* German (de_DE) (Thanks to me ;-))
* Brazilian Portuguese (pt_BR) (Thanks for contributing brazilian portuguese language goes to [Maurício Samy Silva](http://www.maujor.com))
* Italian (it_IT) (Thanks for contributing italian language goes to Gianluca Urgese)
* Spanish (es_ES) (Thanks for contributing spanish language goes to [Karin Sequen](http://www.es-xchange.com))
* Russian (ru_RU) (Thanks for contributing russian language goes to [Dimitry German](http://grugl.me))
* Belorussian (by_BY) (Thanks for contributing belorussian language goes to [FatCow](http://www.fatcow.com))

**Want update feeds, code documentation and more? Visit [extend.schloebe.de](http://extend.schloebe.de)**

== Frequently Asked Questions ==

None.

== Installation ==

1. Download the plugin and unzip it.
1. Upload the folder reveal-ids-for-wp-admin-25/ to your /wp-content/plugins/ folder.
1. Activate the plugin from your WordPress admin panel.
1. Installation finished.

== Changelog ==

= 1.1.5 =
* Added IDs for tag management page

= 1.1.4 =
* Using new hooks to add ID columns where javascript was used before (due to missing hooks) in WP 2.8 and above
* Fixed an issue with capabilites

= 1.1.3 =
* Support for Fluency Admin Theme plugin
* Support for Changelog readme.txt standard

= 1.1.2 =
* Added IDs for comments page

= 1.1.1 =
* Minor code changes

= 1.1.0 =
* Added IDs for link categories page
* Fixed bug that occured on WP 2.5

= 1.0.6 =
* Link IDs now show up again
* Fixed issue with category and user IDs

= 1.0.5 =
* Improved compatibility with WP 2.7 (UI)

= 1.0.4 =
* Code improvements
* Improved compatibility with WP 2.7

= 1.0.3 =
* Changed include mechanism
* Improved activation process

= 1.0.2 =
* Added italian localization (Thanks to Gianluca Urgese!)
* Fixed incompatibility with Gengo plugin (Thanks to dragunoff!)

= 1.0.1 =
* Some changes to the options page

= 1.0 =
* Added IDs for users management page
* More reliable way of displaying category IDs (removed alpha status)
* Added brazilian portuguese localization (Thanks to Maurício Samy Silva!)
* Some code cleanup

= 0.7.6 =
* Small cosmetic change
* Yeah, really. Nothing more.

= 0.7.5 =
* A lot cleaner code
* Minor language fixes

= 0.7.4 =
* Minor fix in category management

= 0.7.3 =
* Fixed error that occured on older blogs (2.1.*) that have recently been updated to WP 2.5 (Thanks to Lars-Tilo Handke for testing!)

= 0.7.2 =
* Fixed error that occasionally damaged category creation

= 0.7.1 =
* Added hint to use the 'Show category IDs' option at your own risk since this seems to not work properly at the moment

= 0.7 =
* More IDs to reveal
* Rights management (Who's allowed to see IDs...)

= 0.5 =
* Plugin released

== Screenshots ==

1. The added ID column
1. Admin Options Page