=== Wp debug log plus ===
Contributors: Satoshi Kaneyasu
Donate link:
Tags: debug, log
Requires at least: 4.9.4
Tested up to: 4.9.5
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
* When a mail transmission error occurs

The log format is as follows.
```
[Date and Time] [Request timestamp] [IP Address] [Login ID] Message
```
| Item | Description |
| ----- | ----- |
| Date and time | The log output date and time. |
| Request timestamp | timestamp at the start of the request. Logs output from one request have the same value in all logs. |
| IP address | IP address of the client. |
Login ID | Login ID of the login. It will be blank until Wordpress authentication process is over. |

You can set the log output timing with * Wp debug log plus * of * Settings *.
| Setting item | Contents |
| - | - |
| Start log | Whether to output Start log |
| Start log text | Output contents of Start log |
| Get parameters log | Whether to output the parameter when the request is "Get" |
| Post parameters log | Whether to output the parameter when the request is "Post" |
| End log | End Whether to output log |
| End log text | Output contents of End log. "% s" is replaced with the request processing time (seconds). |
| SQL log | Whether to log SQL |
| Backtrace log at error occurrence | Whether to output backtrace to the log when a 40x or 50x error occurs |
| Log of mail transmission error | When a mail transmission error occurs Log whether to output |
| All logs of admin-ajax.php | Whether or not to output logs when requested is admin-ajax.php |

== Installation ==

You can install this plugin directly from your WordPress dashboard:

 1. Go to the *Plugins* menu and click *Add New*.
 2. Search for *Wp debug log plus*.
 3. Click *Install Now* next to the *Wp debug log plus* plugin.
 4. Activate the plugin.
 5. Output timing can be set in *Wp debug log plus* in *Setting* menu.
