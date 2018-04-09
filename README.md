It is a plug-in which outputs start log, end log, SQL log and error log to WordPress's debug.log.

## Description


When this plug-in is enabled, logs are output to debug.log in the following cases.
* At the start of the request
* At the end of the request
* When issuing SQL
* When wp_die occurs with 40x error, 50x error

The log format is as follows.
```
[Date and Time] [Request timestamp] [IP Address] [Login ID] Message
```
| Item | Description |
| ----- | ----- |
| Date and time | The log output date and time. |
| Request timestamp | timestamp at the start of the request. Logs output from one request have the same value in all logs. |
| IP address | IP address of the client. |
|Login ID | Login ID of the login. It will be blank until Wordpress authentication process is over. |

You can set the log output timing with *Wp Debug Log Plus* of *Settings*.

| Setting item | Contents |
| ----- | ----- |
| Start log | Whether to output Start log |
| Start log text | Output contents of Start log |
| Get parameters log | Whether to output the parameter when the request is "Get" |
| Post parameters log | Whether to output the parameter when the request is "Post" |
| End log | End Whether to output log |
| End log text | Output contents of End log. "% s" is replaced with the request processing time (seconds). |
| SQL log | Whether to log SQL |
| Backtrace log at error occurrence | Whether to output backtrace to the log when a 40x or 50x error occurs |
| All logs of admin-ajax.php | Whether or not to output logs when requested is admin-ajax.php |

By calling wpdlp_log, you can output logs in the above format whenever you like.
Example)
```
wpdlp_log('message');
```

## Installation

You can install this plugin directly from your WordPress dashboard:

 1. Go to the *Plugins* menu and click *Add New*.
 2. Search for *Wp Debug Log Plus*.
 3. Click *Install Now* next to the *Wp Debug Log Plus* plugin.
 4. Activate the plugin.
 5. Output timing can be set in *Wp Debug Log Plus* in *Setting* menu.

 After installation, edit wp-config.php and enable WP_DEBUG and WP_DEBUG_LOG.
 Example)
 ```
 define('WP_DEBUG', true);
 if ( WP_DEBUG ) {
 		define( 'WP_DEBUG_LOG', true );
 		define( 'WP_DEBUG_DISPLAY', false );
 		@ini_set( 'display_errors',0 );
 }
 ```
