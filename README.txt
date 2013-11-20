=== Custom Facebook Feed ===
Contributors: smashballoon
Tags: facebook, custom, customizable, feed, events, seo, search engine, responsive, mobile, shortcode, social, status, posts
Requires at least: 3.0
Tested up to: 3.7.1
Stable tag: 1.6.7.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Custom Facebook Feed allows you to display a completely customizable Facebook feed of any public Facebook page or group on your website

== Description ==

Display a **completely customizable**, **responsive** and **search engine crawlable** version of your Facebook feed on your website. Completely match the look and feel of the site with tons of customization options!

*"The perfect plugin with amazing support! What else do you want? Get it!"* - [JoeJeffries](http://wordpress.org/support/topic/you-dont-already-have-this)

*"Loving the Facebook feed plugin from @smashballoon. It's gonna transform my work's website! Great customer service too :)"* - [Grace Snow](https://twitter.com/GraceSnow/statuses/365915197149429760)

*"I tried a few other Facebook plugins but this was by far the simplest and easiest to use. The others were quite confusing or didn't let you change even the simplest things. This plugin lets you change literally every part of it. Didn't have any issues setting it up at all and it's working great. Keep up the good work!"* - [Ben Donald](http://wordpress.org/support/topic/simple-to-set-up-and-looks-great)

= Features =

* **Completely Customizable** - By default the Facebook feed will adopt the style of your website, but can be completely customized to look however you like - with tons of styling and customization options!
* Facebook feed content is **crawlable by search engines** adding SEO value to your site - other Facebook plugins embed the feed using iframes which are not crawlable
* Completely **responsive** and mobile optimized - layout looks great on any screen size and in any container width
* Display **feeds from multiple different Facebook pages/groups** and use the shortcode to embed them into a page, post or widget anywhere on your site
* Show **events** from your Facebook feed with name, date/time, location and description
* Add your own **custom CSS**
* **Caching** means that your Facebook posts load lightning fast. Set your own caching time - check for new posts on Facebook every few seconds, minutes, hours or days. You decide.
* Show and hide certain parts of each Facebook post
* Choose to show the Facebook profile picture and name above each post
* Select whether to display Facebook posts by just the page owner, or everyone who posts on your Facebook page
* Control the width, height, padding and background color of your Facebook feed
* Customize the size, weight and color of text
* Select from a range of date formats or enter your own
* Use your own custom link text in place of the defaults
* Use the shortcode options to style multiple Facebook feeds in completely different ways
* Select the number of Facebook posts to display
* Set a maximum character length for both the text and descriptions of your Facebook posts
* Localization/i18n support to allow every part of the feed to be displayed in your language

To display photos, videos, the number of likes, shares and comments for each Facebook post, multiple layout options, post filtering by type or #hashtag/string and more then [upgrade to the Pro version](http://smashballoon.com/custom-facebook-feed/ "Custom Facebook Feed Pro"). Try out the [Pro demo](http://smashballoon.com/custom-facebook-feed/demo "Custom Facebook Feed Demo").

== Installation ==

1. Install the Custom Facebook Feed either via the WordPress plugin directory, or by uploading the files to your web server (in the `/wp-content/plugins/` directory).
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to the 'Facebook Feed' settings page to configure your feed.
4. Use the shortcode `[custom-facebook-feed]` in your page, post or widget to display your feed.
5. You can display multiple feeds of different Facebook pages by specifying a Page ID directly in the shortcode: `[custom-facebook-feed id=smashballoon num=5]`.

== Frequently Asked Questions ==

For a full list of FAQs and help with troubleshooting please visit the **[FAQ & Troubleshooting](http://smashballoon.com/custom-facebook-feed/faq/)** section of the Smash Balloon website

= How do I find the Page ID of my Facebook page or group? =

If you have a Facebook **page** with a URL like this: `https://www.facebook.com/smashballoon` then the Page ID is just `smashballoon`. If your page URL is structured like this: `https://www.facebook.com/pages/smashballoon/123654123654123` then the Page ID is actually the number at the end, so in this case `123654123654123`.

If you have a Facebook **group** then use [this tool](http://lookup-id.com/ "Look Up my ID") to find your Group ID.

Copy and paste the ID into the [Pro demo](http://smashballoon.com/custom-facebook-feed/demo/) to test it.

= Are there any limitations on which Facebook page or group feeds I can display? =

The Facebook feed you're trying to display has to be from a publicly accessible Facebook page or group. This means that you can't display the feed from your own personal Facebook profile or private Facebook group. This is to do with Facebook's privacy policies. You can't display a non-public Facebook feed publicly.

If your Facebook page has any restrictions on it (age, for example) then it means that people have to be signed into Facebook in order to view your page. This isn't desirable for most Facebook pages as it means that it isn't accessible by people who don't have a Facebook account and that your Facebook page can't be crawled and indexed by search engines.

An easy way to determine whether your Facebook page is set to public is to sign out of your Facebook account and try to visit your page. If Facebook forces you to sign in to view your page then it isn't public. You can change your Facebook page to public in your Facebook page settings simply by removing any age or location restrictions you have on it ([screenshot](http://smashballoon.com/wp-content/uploads/2013/06/facebook-page-restrictions.png)), which will then allow the Custom Facebook Feed plugin to access and display your feed.

= What's an Access Token and why do I need one? =

An Access Token is required by Facebook in order to access their feeds.  Don't worry, it's easy to get one.  Just follow the step-by-step instructions [here](http://smashballoon.com/custom-facebook-feed/access-token/ "Getting an Access Token"). to get yours. Your Access Token will never expire.

= Can I display feeds from multiple Facebook pages or groups? =

You can set your default Facebook Page ID on the Custom Facebook Feed settings page within the WordPress admin, you can then define different page IDs in the shortcodes you use to show multiple feeds from different Facebook pages. Just use the id option in your shortcode like so: [custom-facebook-feed id=another_page_id]. You can use as many shortcodes as you like with as many different IDs as you like.

= Why isn't the feed from my group displaying? =

Firstly, check that your group is public and not a private group.
Secondly, be sure to check the 'Show posts by others on my page' option in the Custom Facebook Feed settings page.

= Can I display the feed from a personal Facebook profile? =

Due to Facebook's privacy policy you're not able to use the plugin to display all of your posts from a personal profile, only from a public page or group, as posts from a personal profile are protected for privacy reasons. You may have limited success in displaying certain posts from a personal profile but most posts are not able to be displayed.

If you're using the profile to represent a business, organization, product, public figure or the like, then we'd advise converting your profile to a page per [Facebook's recommendation](http://www.facebook.com/help/175644189234902/), as there are many advantages to using pages over profiles.

Once you've done so, the plugin will be able to retrieve and display all of your posts.

= Can I show photos and videos in my Custom Facebook feed? =

This free plugin only allows you to display textual updates from your Facebook feed. To display photos and videos in your feed you need to upgrade to the Pro version of the plugin. Try out a demo of the Pro version on the [Custom Facebook Feed website](http://smashballoon.com/custom-facebook-feed/demo "Custom Facebook Feed Demo"), and find out more about the Pro version [here](http://smashballoon.com/custom-facebook-feed/ "Custom Facebook Feed Pro").

= Can I show the comments associated with each Facebook post? =

For this feature please upgrade to the [Pro version of the plugin](http://smashballoon.com/custom-facebook-feed/ "Custom Facebook Feed Pro").

= Is the content of my Custom Facebook Feed crawlable by search engines? =

It sure is. Unlike other Facebook plugins which use iframes to embed your Facebook feed into your page once it's loaded, the Custom Facebook Feed uses PHP to embed your Facebook feed content directly into your page. This adds dynamic, search engine crawlable content to your site.

= How do I embed the Custom Facebook Feed directly into a WordPress page template? =

You can embed your Facebook feed directly into a template file by using the WordPress do_shortcode function: `do_shortcode('[custom-facebook-feed]');`.

== Screenshots ==

1. By default the Facebook feed inherits your theme's default styles and is completely responsive
2. Completely customize the way your Facebook feed looks to perfectly match your site
3. Use custom CSS to customize every part of the Facebook feed
4. Display Facebook events
5. Configuring the Custom Facebook Feed plugin
6. General options - Custom Facebook Feed Layout & Style page
7. Typography options - Custom Facebook Feed Layout & Style page
8. Misc options - Custom Facebook FeedLayout & Style page
9. It's super easy to display your Facebook feed in any page or post

== Changelog ==

= 1.6.7.1 =
* Fix: Fixed a bug in 1.6.7 which was causing an issue displaying the feed in some circumstances

= 1.6.7 =
* New: Your feed can now be completely displayed in any language - added i18n support for date translation
* Tweak: Improved documentation within the plugin
* Tweak: Changed order of methods used to retrieve feed data

= 1.6.6.3 =
* New: Added support for group events

= 1.6.6.1 =
* Fix: Resolved jQuery UI draggable bug which was causing issues with drag and drop

= 1.6.6 =
* New: Now works with groups
* Fix: Fixed an issue with the 'Show posts by others' option not working correctly in the previous version

= 1.6.4 =
* New: Added localization support. Full support for various languages coming soon
* New: Added CSS classes to different post types to allow for different styling based on post type
* New: Option to link statuses to either the status post itself or the directly to the page/timeline
* New: Added option to add thumbnail faces of fans to the Like box and define a width
* Tweak: Added separate classes to 'View on Facebook' and 'View Link' links so that they can be targeted with CSS
* Tweak: Prefixed every CSS class to prevent styling conflicts with theme stylesheets. Please note that if you used custom CSS to style parts of the feed that the CSS classes are now prefixed with 'cff-' to prevent theme conflicts. Eg. '.more' is now '.cff-more'.

= 1.6.3 =
* New: Added support for Facebook 'Offers'
* Fix: Fixed an issue with the 'others' shortcode option not working correctly
* Fix: Prefixed the 'clear' class to prevent conflicts

= 1.6.2 =
* New: Post caching now temporarily stores your Facebook post data in your WordPress database to allow for super quick load times
* New: Define your own caching time. Check for new Facebook posts every few seconds, minutes, hours or days. You decide.
* New: Define your own custom text for the 'See More' and 'See Less' buttons
* New: Add your own CSS class to your Custom Facebook Feeds
* New: Define a post limit which is higher or lower than the default 25
* New: Include the Like box inside or outside of the Facebook feed's container
* New: Customize the Facebook event date independently
* New: Improved layout of admin pages for easier navigation and customization
* Fix: Provided a fix for the Facebook API duplicate post bug
* Fix: Fixed bug which ocurred when multiple Facebook feeds are displayed on the same page with different text lengths defined

= 1.5.2 =
* Fix: Fixed JavaScript error in previous update

= 1.5.1 =
* New: Added a 'See More' link to expand any text which is longer than the character limit defined
* New: Choose to show Facebook posts by other people in your feed
* New: Option to show the post author's Facebook profile picture and name above each post
* New: Added options to customize and format the Facebook post date
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
* Tweak: Added classes to Facebook event date, location and description to allow custom styling
* Tweak: Removed 'Where' and 'When' text from Facebook events and made bold instead

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
* Fix: Set all parts of the Facebook feed to display by default on activation

= 1.4.0 =
* Major Update!
* New: Loads of new customization options for your Custom Facebook Feed
* New: Define Facebook feed width, height, padding and background color
* New: Change the font-size, font-weight and color of the Facebook post text, description, date, links and event details
* New: Choose whether to show or hide certain parts of the Facebook posts
* New: Select whether the Facebook Like box is shown at the top of bottom of the Facebook feed
* New: Choose Facebook Like box background color

= 1.3.6 =
* Minor modifications

= 1.3.5 =
* New: Shared Facebook events now display event details (name, location, date/time, description) directly in the Facebook feed

= 1.3.4 =
* New: Email addresses within the Facebook post text are now hyperlinked
* Fix: Links beginning with 'www' are now also hyperlinked

= 1.3.3 =
* New: Added support for Facebook events - display the Facebook event details (name, location, date/time, description) directly in the Facebook feed
* Fix: Links within the Facebook post text are now hyperlinked
* Tweak: Added additional methods for retrieving Facebook feed data

= 1.3.2 =
* Fix: Now using the built-in WordPress HTTP API to get retrieve the Facebook data

= 1.3.1 =
* Fix: Fixed issue with certain Facebook statuses not displaying correctly

= 1.3.0 =
* Tweak: If 'Number of Posts to show' is not set then default to 10 Facebook posts

= 1.2.9 =
* Fix: Now using cURL instead of file_get_contents to prevent issues with php.ini configuration on some web servers

= 1.2.8 =
* Fix: Fixed bug in specifying the number of Facebook posts to display

= 1.2.7 =
* Tweak: Prevented likes and comments by the page author showing up in the Facebook feed

= 1.2.6 =
* Tweak: Added help link to Custom Facebook Feed settings page

= 1.2.5 =
* Fix: Added clear fix

= 1.2.1 =
* Fix: Minor bug fixes

= 1.2 =
* New: Added the ability to define a maximum length for both the Facebook post text and description

= 1.0 =
* Launch!