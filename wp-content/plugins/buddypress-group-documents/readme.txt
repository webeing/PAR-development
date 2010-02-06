=== Group Documents ===
Contributors: Peter Anselmo
Tags: wpmu, buddypress, group, document, plugin, file, media, storage, upload, widget
Requires at least: WPMU 2.8, BuddyPress 1.0
Tested up to: 2.9/1.2
Stable tag: 0.2.4

This allows members of BuddyPress groups to upload and store documents that are relevant to the group.

== Description ==

Group Documents creates a page within each BuddyPress group to upload and store documents.  Documents can be edited and deleted either by the document owner or by the group administrator.  Document activity is logged in the main activity stream, and is also tied to the user and group activity streams.  The site administrator can set filters on file extensions, and users and moderators can receive email notifications at their option.  There is also a "Recent Uploads" widget than can be used to show any number of recently uploaded documents.

== Installation ==

Make sure WPMU and BuddyPress are installed and active.

Copy the plugin folder buddypress-group-documents/ into /wp-content/plugins/

IMPORTANT:
Be sure that the sub folder "buddypress-group-documents/documents" can be written to by the web server.  This is where all uploaded files are stored. If you are unsure of how to set directory permissions, please google, ask around, or shoot me an email.

Browse to the plugin administration screen and activate the plugin.

There will now be a "Group Documents" menu item under the "BuddyPress" menu.  Here you will find a list of all file extensions allowed for uploaded files along with other settings.

Please don't hesitate to contact me, especially if you run into trouble.  I will respond promptly.  peter@studio66design.com

== Screenshots ==

1. Main view from the website
2. The Site Admin can filter uploads by extension
3. Ties into the site activity stream
4. Allows options for email notifications
5. Includes Recent Uploads Widget
6. Admin view of the Widget

==Changelog==

Aplogies for the frequent updates, this plugin is under active development!

= 0.2.4 =
* Fixed a bug where errors were thrown on group deletion

= 0.2.3 =
* Added BuddyPress 1.2 Compatibility
* Added additional callbacks for extensibility
* Fixed bug where newlines were dropping from description

= 0.2.2 =
* Fixed bug where documents in private groups were visible to everyone
* Additional strings added for i18n
* Added French Translation (Courtesy of Daniel Halstenbach)

= 0.2.1 =
* Cleaned up some loose ends with i18n & translation

= 0.2 =
* Added paging for long lists of documents
* Added option for email notifications
* Added option for file size display
* Fixed a bug where unneccesary slashes were added to file name
* Significant refactoring of code

= 0.1.3 =
* Fixed a bug with the site admin menu in WPMU 2.9
* Reorganized several files & folders to reduce redundancy

= 0.1.2 =
* Fixed a bug where the period was dropping from the file extension

= 0.1.1 =
* Fixed a folder naming discrepancy betweeen 'bp-group-documents' and 'buddypress-group-documents'

= 0.1 =
* Initial Realease

== Notes ==

Roadmap.txt - contains ideas proposed and the (approximate) order of implementation

History.txt - contains all the changes since version .1

License.txt - contains the licensing details for this component.

== Feedback ==

Please email me with any bugs, improvements, or comments. I will respond promptly :-)

peter@studio66design.com.
