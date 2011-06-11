=== Options Framework ===
Contributors: Devin Price
Tags: options, theme options
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=X238BDP4QGTV2
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 0.7
License: GPLv2

== Description ==

The Options Framework Plugin makes it easy to include an options panel in any WordPress theme.  It was built so that theme developers can concentrate on making the actual theme rather than spending a ton of time on creating an options panel from scratch.  It's free to use in both commercial and personal projects, just like WordPress itself.

Please visit [http://wptheming.com/options-framework-plugin](http://wptheming.com/options-framework-plugin) for a full description of how to define and use the theme options.

The code is heavily based on the [WooFramework](http://www.woothemes.com/) and their option styles.

== Installation ==

If your theme already has options enabled, they will show up under the apperance menu.

If your theme doesn't have options, you can define them to options.php of your theme and they will instantly show up.  For more on how to do this, visit [http://wptheming.com/options-framework-plugin](http://wptheming.com/options-framework-plugin).

== Frequently Asked Questions ==

= How do I build options for my own theme? =

Download the development version from GitHub [https://github.com/devinsays/options-framework-plugin](https://github.com/devinsays/options-framework-plugin) and copy the "options-check" folder into your themes directory.

The "Options Check" theme is a blueprint for how to work with options.  It includes an example of every option available in the panel and sample output in the theme.

You can also watch the video screencast I have at [http://wptheming.com/options-framework-plugin](http://wptheming.com/options-framework-plugin).

= What options are available to use? =

* text
* textarea
* checkbox
* select
* radio
* upload (an image uploader)
* images (use images instead of radio buttons)
* background (a set of options to define a background)
* multicheck
* color (a jquery color picker)
* typography (a set of options to define typography)

== Screenshots ==

1. An example of the "Advanced Options" tag in the "Options Check" theme using this plugin.

== Changelog ==

= 0.x =

* The Options Panel now keeps track of tab states

= 0.7 =

* Added filtering for recognized arrays (like Font Face)
* Using isset rather than !empty to return of_get_option
* Significant updates for setting and restoring defaults
* Background option outputs no-repeat rather than none

= 0.6 =

* Introduces validation filters
* Better data sanitization and escaping
* Updates labels in options-interface.php
* Changes how checkboxes saved in database ("0" or "1")
* Stores typography, backgrounds and multichecks directly as arrays
* For full description, see: http://wptheming.com/2011/05/options-framework-0-6/

= 0.5 =

* Fixed errors when more than one multicheck options is used
* Updated optionsframework_setdefaults so defaults actually save on first run
* Require that all options have lowercase alphanumeric ids
* Added link to options from the WordPress admin bar

= 0.4 =

* Updated multicheck option to save keys rather than values
* Unset default array options after each output in optionsframework_setdefaults

= 0.3 =

* White listed options for increased security
* Fixed errors with checkbox and select boxes
* Improved the multicheck option and changed format

= 0.2 =

* Uploaded to the WordPress repository

= 0.1 =

* Initial release