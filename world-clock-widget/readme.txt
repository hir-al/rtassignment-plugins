=== World Clock Widget ===
Contributors: Xander Tan
Donate Link: http://craeser.wordpress.com/
Tags: clock, world clock, widget, worldclock
Requires at least: 3.2.1
Tested up to: 3.2.1
Stable tag: 1.0.0

Sidebar widget to easy customize and display your date and time of multiple timezones. All settings are available from Sidebar Widget Admin.

== Description ==

A widget that can be easily set to display date and time of multiple timezones.
The javascript function is taken from [Proglogic](http://www.proglogic.com/code/javascript/time/worldclock.php "Visit Proglogic") and is written by Stijn Strickx.
The code is then modified to fit widget format.

== Installation ==
1. Download [`world-clock-widget` plugin](http://wordpress.org/extend/plugins/world-clock-widget/ "Visit Download Page") and unzip it.
2. Upload the folder `world-clock-widget` to your WP plugin folder `/wp-content/plugins/` directory.
3. Go to Plugins > Installed, activate the plugin.
4. Go to Appearance > Widgets, add the widget to a sidebar and save.
5. Check the [Frequently Asked Questions](http://wordpress.org/extend/plugins/world-clock-widget/faq/ "Visit Frequently Asked Questions Page") to learn how to add, edit and remove clocks.

== Changelog ==

= 1.0.0 =
* Major code modification.
* Added a choice of text alignment (left, center or right).
* Added a feature to display the widget horizontally.
* Added option to use server time, instead of client time.

= 0.8.0 =
* Added the date.js from http://code.google.com/p/datejs/.
* Added editable widget title.
* Added editable date and time format.

= 0.7.1 =
* Fixed the negative minute.

= 0.7.0 =
* Edit function fix.
* Added in worldclock.xml.

= 0.6.1 =
* Installation directory fix.
* Added in Changelog page.

= 0.6.0 =
* Edit function.
* 15, 30, 45 minutes difference support.

== Frequently Asked Questions ==

= Do you have any screenshot? =
Yes, the screenshot is in [my personal blog](http://craeser.wordpress.com/world-clock-widget/ "Craeser is Where I Spend My Freetime...").

= What is Daylight Saving Time? =
Daylight saving time is the convention of advancing clocks so that afternoons have more daylight and mornings have less. [Click here for more information](http://en.wikipedia.org/wiki/Daylight_saving_time "Daylight Saving Time")

= Does world clock widget support Daylight Saving Time? =
At the moment world clock widget supports only North America Region, i.e. Alaska, Atlantic time, Central time, Eastern time, Indiana (East), Mexico, Mountain time and Pacific time. More regions will be added in the future.

= Does world clock widget cater the Daylight Saving Time for 2010? =
Daylight Saving Time changes on yearly basis, at the moment world clock widget only supports year of 2009. Support for multiple years will be added in the future.

= I go to the world clock widget control, how do I add a new clock? =
Type your `City` name (for display purpose, name it whatever you want); Select your `Timezone`; (Optional) Tick the `Daylight Saving Time`; Click `Save`.

= How do I remove the existing clock(s)? =
Under the `Existing Clock`, tick `Remove (your_city_name)`; Click `Save`.

= How do I edit the existing clock? =
Under the `Existing Clock`, click on the clock that you want to edit; Click `Save`.
