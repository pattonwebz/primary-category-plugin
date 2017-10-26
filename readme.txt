=== Primary Category Plugin ===
Contributors: williampatton
Tags: category, taxonomy, organizing
Requires at least: 4.4
Tested up to: 4.9
Requires PHP: 5.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to select a category to mark as the primary category for the post.

== Description ==

Use this plugin to select a category for the post as the 'primary' category for it.

It also offers a widget for showing categories containing primary tags and a shortcode to output linked titles to posts for that are tagged with a given primary category.

Shortcode accepts 5 possible parameters: `id`, `slug`, `name`, `post_type` and `limit`. All are option - but you must pass at least 1 of `id`, `slug` or `name`. Preference is given to each in that order respectively. Defaults are for `post` for post_type and limit of `10`.

`[primary_category_query id="1" slug="slug" name="nicename" post_type="post" limit="10"]`
== Installation ==

1. Upload `primary-category-plugin` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.0.0 =
* Release

== Upgrade Notice ==

= 1.0.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.
