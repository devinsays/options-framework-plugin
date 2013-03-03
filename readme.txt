=== Options Framework ===

Contributors: Devin Price
Tags: options, theme options
Donate link: http://bit.ly/options-donate-2
Requires at least: 3.3
Tested up to: 3.5
Stable tag: 1.5.2
License: GPLv2

== Description ==

The Options Framework Plugin makes it easy to include an options panel in any WordPress theme.  It was built so developers can concentrate on making the actual theme rather than spending time creating an options panel from scratch.  It's free to use in both commercial and personal projects, just like WordPress itself.

= Options Include =

* text input
* textarea
* checkbox
* select
* radio button
* upload (image uploader)
* images (use images instead of radio buttons)
* background (a set of options to define a background)
* multicheck
* color (a jquery color picker)
* typography (a set of options to define typography)
* editor

= Learn More =

Please visit [http://wptheming.com/options-framework-plugin](http://wptheming.com/options-framework-plugin) for a full description of how to define and use the theme options.

== Installation ==

If your theme already has options enabled, they will show up under the appearance menu.

If your theme doesn't have options, you can define them to options.php of your theme and they will instantly show up.  For more on how to do this, visit [http://wptheming.com/options-framework-plugin](http://wptheming.com/options-framework-plugin).

== Frequently Asked Questions ==

= How do I build options for my own theme? =

Download the development version from GitHub [https://github.com/devinsays/options-framework-plugin](https://github.com/devinsays/options-framework-plugin) and copy the "options-check" folder into your themes directory.

The "Options Check" theme is a blueprint for how to work with options.  It includes an example of every option available in the panel and sample output in the theme.

You can also watch the video screencast I have at [http://wptheming.com/options-framework-plugin](http://wptheming.com/options-framework-plugin).

= What options are available to use? =

* text input
* textarea
* checkbox
* select
* radio button
* upload (image uploader)
* images (use images instead of radio buttons)
* background (a set of options to define a background)
* multicheck
* color (a jquery color picker)
* typography (a set of options to define typography)

== Screenshots ==

1. An example of the "Advanced Options" tag in the "Options Check" theme using this plugin.

== Changelog ==

= 1.5.2 =

* Removed update routine to remove "optionsframework" post type

= 1.5.1 =

* Temporarily removed update routine

= 1.5 =

* Updated width of text input
* New media uploader
* Dropped custom post types for file attachments
* Update routine to remove unused 'optionsframework' post types
* Updated IDs for .tabs and .groups
* Italian translations (andreabersi.com)
* Russian translations (verha.net)
* Swedish translations (kjeell.se)

= 1.4 =

* Add missing sanitization to typography color (@weplantmedia)
* New colorpicker (props @mattweibe for getting this in WordPress core)
* Farsi translations (@vahidd.com)
* Added password option type (props @neojp)
* Allow ids to passed to tabs (props @themeblvd)
* Added optionsframework_after_validate hook (h/t @vanpop and @pryley)

= 1.3 =

* Allow options to save when set by theme customizer
* Save checkbox options to boolean false rather than "0"
* Added optionsframework_after hook
* Hungarian translations by kardiweb.org

= 1.2 =

* Fix for font_size santization
* Added internationalization support (@weslly)
* Portuguese translations (@weslly)
* Spanish translations (@sksmatt)
* Make uploader post type non-public (@samargulies)
* ID can now be passed on info option for styling purposes

= 1.1 =

* Added support for wp_editor
* Updated textarea settings to allow rows parameter
* Updated cursor:default for h3 metabox, props @yurifedorov
* Filtering of typography options (@mattwiebe)
* Updated methods for typography options

= 1.0 =

* Added filters for entire options array (props @inxilpro)
* Added a filter for options.php location (props @mattwiebe)
* Option header (h4) will not display in panel if name !isset (props @alepee)

= 0.9 =

* Load thickbox using site_url() to allow for https (props @samargulies)
* Change santization to use $allowedposttags for textarea and info
* Display notice if theme doesn't support the Options Framework
* Single checkboxes now use labels
* CSS updates for formatting long labels
* Allows dashes in the options id (props @mantone)
* Uses add_theme_page over add_submenu_page (props @enile8)

= 0.8 =

* Saves tab states using local storage
* Style updates WordPress 3.2 release
* Added sanitization filter for info (of_sanitize_allowedtags)

= 0.7 =

* Added filtering for recognized arrays (like Font Face)
* Using isset rather than !empty to return of_get_option
* Significant updates for setting and restoring defaults
* Background option outputs no-repeat rather than none

= 0.6 =

* Introduces validation filters (props @_mfields)
* Better data sanitization and escaping (props @_mfields)
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