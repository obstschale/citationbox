=== Citation Box ===
Contributors: obstschale
Tags: citation, reference, link, box, list,
Donate link: http://bit.ly/hhbdonation
Requires at least: 3.5
Tested up to: 3.8.1
Stable tag: 0.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Citation Box grabs all links in a post or page and lists them at the end of each post   / page. The text contains only reference numbers to the links.

== Description ==
This plugin uses `the_content` filter and goes through the DOM Tree to look for `<a>` tags (exceptions are: images and WordPress more-link). The link is extracted form the content and a number is added after the former link. The link itself will be added to a list at the end of each site.

In the settings you can decide where this box is displayed. The first option will add a box at each post if the single post is viewed. The second is for pages and `Home` is referring to the main page where you can browser your articles. In addition, it is possible to choose own colors to fit perfectly to your theme.

BTW: If no links are found in a post / page also no Citation Box is shown.

== Installation ==
1. Upload `citation-box` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the \'Plugins\' menu in WordPress

== Screenshots ==
1. https://github.com/obstschale/citationbox/raw/master/screenshots/Citationbox.png
2. https://github.com/obstschale/citationbox/raw/master/screenshots/Settings.png

== Changelog ==
= 0.1.1 - 21. March 2014 =

* [FIX] Load Farbtastic and JS-Script only on CB Settings page otherwise an `Uncaught TypeError` is thrown because `farbtastic` is undefined

= 0.1 - 20. March 2014 =

* [FEATURE] Citation Box for posts, pages, and home page
* [FEATURE] Settings: choose where Citation Box should be displayed and choose own colors

== Upgrade Notice ==
= 0.1.1 - 21. March 2014 =
Important bug fix. Update immediately because otherwise some wired problems could occur. E.g. you can not submit a form in WordPress. 

= 0.1 - 20. March 2014 =
Initial release of this plugin. You should give it a try :)