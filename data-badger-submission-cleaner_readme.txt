=== DataBadger Submission Cleaner Plugin ===
Contributors: Nima Hedayati
Tags: gravity forms, data cleaner
Donate link: http://nimahedayati.com/
Requires at least: 4.0
Tested up to: 4.9.8
Requires PHP: 5.6
Stable tag: 1.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An extension for Gravity Forms that allows admin access to delete submitted data.

== Description ==
An extension for Gravity Forms that allows admin access to delete submitted data.
This plugin allows data gathered from specific forms to be deleted after one day, week, month or year.
Developed on a Wamp local server running LAMP stack. IDE used is PHP Storm.

== Installation ==
1. Upload the folder "data-badger-submission-cleaner" and all its contents to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.

== Changelog ==

= 0.1 =
Initial release.
Notes on this release:
The plugin is not yet fully functional and only partially tested.
More time is required to build a working plugin with the help of more contributors.
In parts of the SQL queries contained within the two scripts I have used sprintf notation;
ideally, I would have preferred to use $wpdb->prepare() but for some unknown reason
this was not working on my local server environment. Tested on Apache 2.4.27, PHP 5.6.31, MySQL 5.7.19 and phpMyAdmin 4.7.4.
I think there is the foundation for a good working plugin here,
just need to correctly work out the logic within the delete-entries-script.php file.

= 0.2 =
Re-write of delete-entries-script.php and update AJAX dataType
Notes on this release:
The delete-entries-script.php script now makes use of a single function with two parameters: id and date,
there are two nested functions to deal with deleting data for the two separate inputs (form id and date),
the SQL queries are also rewritten. I have now removed all reference to sprintf and replaced
queries with $wpdb->get_results($wpdb->prepare()), this should prevent any SQL injections and keep the code within the WordPress framework.
There is now an issue retrieving data through this method for the form options within the first input, this was previously working when
using sprintf, there is now a SQL syntax error which I am investigating.
The delete script is now doing something and returning success to AJAX but the correct data is not being deleted from the two tables involved,
I am also investigating this issue.

= 1.0 =
First stable release.
Notes on this release:
Debugged all SQL queries and re-worked logic of functions in delete-entries-script.php; also fixed issues with AJAX call and JS scripts.
Have tested and can't find any major issues.
Still can't get $wpdb->prepare() to work so have resorted to using sprintf(), this should still add security against SQL injections.

= 1.1 =
Bug fix
Notes on this release:
fixed two bugs. The plugin is now fully functional.