=== Custom Facebook Feed ===
Contributors: smashballoon
Tags: facebook, custom, customizable, feed, events, seo, search engine, responsive, mobile, shortcode, social, status
Requires at least: 3.0
Tested up to: 3.6.1
Stable tag: 1.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Custom Facebook Feed allows you to display a completely customizable Facebook feed of any public Facebook page on your website.

== Description ==

Display a **completely customizable**, **responsive** and **search engine crawlable** version of your Facebook feed on your website. Completely match the look and feel of the site!

= Features =

* **Completely Customizable** - By default the Facebook feed will adopt the style of your website, but can be completely customized to look however you like - with tons of styling options and custom CSS!
* Facebook feed content is **crawlable by search engines** adding SEO value to your site - other Facebook plugins embed the feed using iframes which are not crawlable
* Completely **responsive** and mobile optimized - layout looks great on any screen size and in any container width
* Display **feeds from multiple different Facebook pages** and use the shortcode to embed them into a page, post or widget anywhere on your site
* Show **events** from your Facebook feed with name, date/time, location and description
* Add your own **custom CSS**
* Show and hide certain parts of each post
* Choose to show the Facebook profile picture and name above each post
* Select whether to display posts by just the page owner, or everyone who posts on your Facebook page
* Control the width, height, padding and background color of your Facebook feed
* Customize the size, weight and color of text
* Select from a range of date formats or enter your own
* Use your own custom link text in place of the defaults
* Use the shortcode options to style multiple Facebook feeds in completely different ways
* Select the number of Facebook posts to display
* Set a maximum character length for both the post title and body text

To display photos, videos, the number of likes, shares and comments for each Facebook post, multiple layout options and more then [upgrade to the Pro version](http://smashballoon.com/custom-facebook-feed/ "Custom Facebook Feed Pro"). Try out the [Pro demo](http://smashballoon.com/custom-facebook-feed/demo "Custom Facebook Feed Demo").

== Installation ==

1. Install the Custom Facebook Feed either via the WordPress plugin directory, or by uploading the files to your web server (in the `/wp-content/plugins/` directory).
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to the 'Facebook Feed' settings page to configure your feed.
4. Use the shortcode `[custom-facebook-feed]` in your page, post or widget to display your feed.
5. You can display multiple feeds of different Facebook pages by specifying a Page ID directly in the shortcode: `[custom-facebook-feed id=smashballoon num=5]`.

== Frequently Asked Questions ==

= How do I find the Page ID of my Facebook page? =

If you have a Facebook page with a URL like this: `https://www.facebook.com/Your_Page_Name` then the Page ID is just `Your_Page_Name`.  If your Facebook page URL is structured like this: `https://www.facebook.com/pages/Your_Page_Name/123654123654123` then the Page ID is actually the number at the end, so in this case `123654123654123`.

= Are there any limitations on which Facebook page feeds I can display? =

The Facebook feed you're trying to display has to be from a publicly accessible Facebook page. This means that you can't display the feed from your own personal Facebook profile or Facebook group. This is to do with Facebook's privacy policies. You can't display a non-public Facebook feed publicly.

If your Facebook page has any restrictions on it (age, for example) then it means that people have to be signed into Facebook in order to view your page. This isn't desirable for most Facebook pages as it means that it isn't accessible by people who don't have a Facebook account and that your Facebook page can't be crawled and indexed by search engines.

An easy way to determine whether your Facebook page is set to public is to sign out of your Facebook account and try to visit your page. If Facebook forces you to sign in to view your page then it isn't public. You can change your Facebook page to public in your Facebook page settings simply by removing any restrictions you have on it, which will then allow the Custom Facebook Feed plugin to access and display your feed.

= What's an Access Token and why do I need one? =

An Access Token is required by Facebook in order to access their feeds.  Don't worry, it's easy to get one.  Just follow the step-by-step instructions [here](http://smashballoon.com/custom-facebook-feed/access-token/ "Getting an Access Token"). to get yours. Your Access Token will never expire.

= Can I display feeds from multiple Facebook pages? =

Yep. You set your default Facebook page ID in the plugin's settings but then you can define different Facebook page IDs in the shortcodes you use to show multiple feeds from different Facebook pages. Just use the id option in your shortcode like so: id=another_page_id.

= Can I show photos and videos in my Custom Facebook feed? =

This free plugin only allows you to display textual updates from your Facebook feed. To display photos and videos in your feed you need to upgrade to the PRO version of the plugin. Try out a demo of the PRO version on the [Custom Facebook Feed website](http://smashballoon.com/custom-facebook-feed/demo "Custom Facebook Feed Demo"), and find out more about the PRO version [here](http://smashballoon.com/custom-facebook-feed/ "Custom Facebook Feed PRO").

= Can I show the comments associated with each Facebook post? =

For this feature please upgrade to the [PRO version of the plugin](http://smashballoon.com/custom-facebook-feed/ "Custom Facebook Feed PRO").

= Is the content of my feed crawlable by search engines? =

It sure is. Unlike other Facebook plugins which use iframes to embed your feed into your page once it's loaded, the Custom Facebook Feed uses PHP to embed your Facebook feed content directly into your page. This adds dynamic, search engine crawlable content to your site.

= How do I embed the feed directly into a WordPress page template? =

You can embed the feed directly into a template file by using the WordPress do_shortcode function: do_shortcode('[custom-facebook-feed]'');

== Screenshots ==

1. By default the Facebook feed inherits your theme's default styles and is completely responsive
2. Completely customize the way your Facebook feed looks to perfectly match your site
3. Use custom CSS to customize every part of the Facebook feed
4. Display Facebook events
5. Show and hide certain parts of the posts
6. Configuring the plugin
7. Use the styling options to customize your Facebook feed
8. It's super easy to display your Facebook feed in any page or post
9. Add the shortcode to a widget

== Changelog ==

= 1.5.2 =
* Fix: Fixed JavaScript error in previous update

= 1.5.1 =
* New: Added a 'See More' link to expand any text which is longer than the character limit defined
* New: Choose to show Facebook posts by other people in your feed
* New: Option to show the post author's Facebook profile picture and name above each post
* New: Added options to customize and format the post date
* New: Add your own text before and after the date and in place of the 'View on Facebook' and 'View Link' text links
* New: Specify the format of the Facebook Event date
* Tweak: Default date format is less specific and better mimics Facebook's - credit Mark Bebbington
* Tweak: Changed the layout of the Typography section to allow for the additional options
* Fix: When a Facebook photo album is shared it now links to the album itself on Facebook and not just the cover photo
* Fix: Fixed issue with hyperlinks in post text which don't have a space before them not being converted to links

= 1.4.8 =
* Minor fixes

= 1.4.7 =
* Tweak: Added links to statuses which link to the Facebook page
* Tweak: Added classes to event date, location and description to allow custom styling
* Tweak: Removed 'Where' and 'When' text from events and made bold instead

= 1.4.6 =
* Fix: Fixed 'num' option in shortcode

= 1.4.4 =
* New: Added more shortcode options
* Minor tweaks

= 1.4.2 =
* New: Add your own custom CSS to allow for even deeper customization
* New: Optionally link your post text to the Facebook post
* New: Optionally link your event title to the Facebook event page
* Some minor modifications

= 1.4.1 =
* Fix: Set all parts of the feed to display by default on activation

= 1.4.0 =
* Major Update!
* New: Loads of new customization options for your feed
* New: Define feed width, height, padding and background color
* New: Change the font-size, font-weight and color of the post text, description, date, links and event details
* New: Choose whether to show or hide certain parts of the posts
* New: Select whether the Like box is shown at the top of bottom of the feed
* New: Choose Like box background color

= 1.3.6 =
* Minor modifications

= 1.3.5 =
* New: Shared events now display event details (name, location, date/time, description) directly in the feed

= 1.3.4 =
* New: Email addresses within the post text are now hyperlinked
* Fix: Links beginning with 'www' are now also hyperlinked

= 1.3.3 =
* New: Added support for events - display the event details (name, location, date/time, description) directly in the feed
* Fix: Links within the post text are now hyperlinked
* Tweak: Added additional methods for retrieving feed data

= 1.3.2 =
* Fix: Now using the built-in WordPress HTTP API to get retrieve the Facebook data

= 1.3.1 =
* Fix: Fixed issue with certain statuses not displaying correctly

= 1.3.0 =
* Tweak: If 'Number of Posts to show' is not set then default to 10

= 1.2.9 =
* Fix: Now using cURL instead of file_get_contents to prevent issues with php.ini configuration on some web servers

= 1.2.8 =
* Fix: Fixed bug in specifying the number of posts to display

= 1.2.7 =
* Tweak: Prevented likes and comments by the page author showing up in the feed

= 1.2.6 =
* Tweak: Added help link to settings page

= 1.2.5 =
* Fix: Added clear fix

= 1.2.1 =
* Fix: Minor bug fixes

= 1.2 =
* New: Added the ability to define a maximum length for both the post title and body text

= 1.0 =
* Launch!