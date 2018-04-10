=== Wp Debug Log Plus ===
Contributors: pottiripottiri
Donate link:
Tags: debug, log, sql, error, backtrace, stacktrace
Requires at least: 4.9.4
Tested up to: 4.9.5
Requires PHP: 5.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

It is a plug-in which outputs start log, end log, SQL log and error log to WordPress's debug.log.

== Description ==

When this plug-in is enabled, logs are output to debug.log in the following cases.

* At the start of the request
* At the end of the request
* When issuing SQL
* When wp_die occurs with 40x error, 50x error

The log format is as follows.

`
[Date and Time] [Request timestamp] [IP Address] [Login ID] Message
`

You can set the log output timing with *Wp Debug Log Plus* of *Settings*.

For more information please see <a href="https://en.pottiri.tech/p/wp-debug-log-plus.html">here</a>.

== Installation ==

You can install this plugin directly from your WordPress dashboard:

 1. Go to the *Plugins* menu and click *Add New*.
 2. Search for *Wp Debug Log Plus*.
 3. Click *Install Now* next to the *Wp Debug Log Plus* plugin.
 4. Activate the plugin.
 5. Output timing can be set in *Wp Debug Log Plus* in *Setting* menu.

 `
 define('WP_DEBUG', true);
 if ( WP_DEBUG ) {
 		define( 'WP_DEBUG_LOG', true );
 		define( 'WP_DEBUG_DISPLAY', false );
 		@ini_set( 'display_errors',0 );
 }
 `

== Screenshots ==
1. screenshot-1.png
2. screenshot-2.png

== Changelog ==

= 1.0.0 =
 Initial plugin version

== Upgrade Notice ==
