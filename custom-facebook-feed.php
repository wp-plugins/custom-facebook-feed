<?php 
/*
Plugin Name: Custom Facebook Feed
Plugin URI: http://smashballoon.com/custom-facebook-feed
Description: Add a completely customizable Facebook feed to your WordPress site
Version: 1.6.7.1
Author: Smash Balloon
Author URI: http://smashballoon.com/
License: GPLv2 or later
*/
/* 
Copyright 2013  Smash Balloon (email : hey@smashballoon.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//Include admin
include dirname( __FILE__ ) .'/custom-facebook-feed-admin.php';
// Add shortcodes
add_shortcode('custom-facebook-feed', 'display_cff');
function display_cff($atts) {
    
    //Style options
    $options = get_option('cff_style_settings');
    //Create the types string to set as shortcode default
    $include_string = '';
    if($options[ 'cff_show_text' ]) $include_string .= 'text,';
    if($options[ 'cff_show_desc' ]) $include_string .= 'desc,';
    if($options[ 'cff_show_shared_links' ]) $include_string .= 'sharedlinks,';
    if($options[ 'cff_show_date' ]) $include_string .= 'date,';
    if($options[ 'cff_show_media' ]) $include_string .= 'media,';
    if($options[ 'cff_show_event_title' ]) $include_string .= 'eventtitle,';
    if($options[ 'cff_show_event_details' ]) $include_string .= 'eventdetails,';
    if($options[ 'cff_show_meta' ]) $include_string .= 'social,';
    if($options[ 'cff_show_link' ]) $include_string .= 'link,';
    if($options[ 'cff_show_like_box' ]) $include_string .= 'likebox,';
    //Pass in shortcode attrbutes
    $atts = shortcode_atts(
    array(
        'id' => get_option('cff_page_id'),
        'num' => get_option('cff_num_show'),
        'limit' => get_option('cff_post_limit'),
        'others' => get_option('cff_show_others'),
        'cachetime' => get_option('cff_cache_time'),
        'cacheunit' => get_option('cff_cache_time_unit'),
        'locale' => get_option('cff_locale'),
        'width' => $options[ 'cff_feed_width' ],
        'height' => $options[ 'cff_feed_height' ],
        'padding' => $options[ 'cff_feed_padding' ],
        'bgcolor' => $options[ 'cff_bg_color' ],
        'showauthor' => $options[ 'cff_show_author' ],
        'class' => $options[ 'cff_class' ],
        'layout' => $options[ 'cff_preset_layout' ],
        'include' => $include_string,
        //Typography
        'seemoretext' => $options[ 'cff_see_more_text' ],
        'seelesstext' => $options[ 'cff_see_less_text' ],
        'textformat' => $options[ 'cff_title_format' ],
        'textsize' => $options[ 'cff_title_size' ],
        'textweight' => $options[ 'cff_title_weight' ],
        'textcolor' => $options[ 'cff_title_color' ],
        'textlink' => $options[ 'cff_title_link' ],
        'descsize' => $options[ 'cff_body_size' ],
        'descweight' => $options[ 'cff_body_weight' ],
        'desccolor' => $options[ 'cff_body_color' ],
        //Event title
        'eventtitleformat' => $options[ 'cff_event_title_format' ],
        'eventtitlesize' => $options[ 'cff_event_title_size' ],
        'eventtitleweight' => $options[ 'cff_event_title_weight' ],
        'eventtitlecolor' => $options[ 'cff_event_title_color' ],
        'eventtitlelink' => $options[ 'cff_event_title_link' ],
        //Event date
        'eventdatesize' => $options[ 'cff_event_date_size' ],
        'eventdateweight' => $options[ 'cff_event_date_weight' ],
        'eventdatecolor' => $options[ 'cff_event_date_color' ],
        'eventdatepos' => $options[ 'cff_event_date_position' ],
        'eventdateformat' => $options[ 'cff_event_date_formatting' ],
        'eventdatecustom' => $options[ 'cff_event_date_custom' ],
        //Event details
        'eventdetailssize' => $options[ 'cff_event_details_size' ],
        'eventdetailsweight' => $options[ 'cff_event_details_weight' ],
        'eventdetailscolor' => $options[ 'cff_event_details_color' ],
        //Date
        'datepos' => $options[ 'cff_date_position' ],
        'datesize' => $options[ 'cff_date_size' ],
        'dateweight' => $options[ 'cff_date_weight' ],
        'datecolor' => $options[ 'cff_date_color' ],
        'dateformat' => $options[ 'cff_date_formatting' ],
        'datecustom' => $options[ 'cff_date_custom' ],
        'linksize' => $options[ 'cff_link_size' ],
        'linkweight' => $options[ 'cff_link_weight' ],
        'linkcolor' => $options[ 'cff_link_color' ],
        'facebooklinktext' => $options[ 'cff_facebook_link_text' ],
        'viewlinktext' => $options[ 'cff_view_link_text' ],
        'linktotimeline' => $options[ 'cff_link_to_timeline' ],
        //Social
        'iconstyle' => $options[ 'cff_icon_style' ],
        'socialtextcolor' => $options[ 'cff_meta_text_color' ],
        'socialbgcolor' => $options[ 'cff_meta_bg_color' ],
        //Misc
        'textlength' => get_option('cff_title_length'),
        'desclength' => get_option('cff_body_length'),
        'likeboxpos' => $options[ 'cff_like_box_position' ],
        'likeboxoutside' => $options[ 'cff_like_box_outside' ],
        'likeboxcolor' => $options[ 'cff_likebox_bg_color' ],
        'likeboxwidth' => $options[ 'cff_likebox_width' ],
        'likeboxfaces' => $options[ 'cff_like_box_faces' ],

        'videoheight' => $options[ 'cff_video_height' ],
        'videoaction' => $options[ 'cff_video_action' ],
        'sepcolor' => $options[ 'cff_sep_color' ],
        'sepsize' => $options[ 'cff_sep_size' ]
    ), $atts);
    /********** GENERAL **********/
    $cff_feed_width = $atts['width'];
    $cff_feed_height = $atts[ 'height' ];
    $cff_feed_padding = $atts[ 'padding' ];
    $cff_bg_color = $atts[ 'bgcolor' ];
    $cff_show_author = $atts[ 'showauthor' ];
    $cff_cache_time = $atts[ 'cachetime' ];
    $cff_locale = $atts[ 'locale' ];
    if ( empty($cff_locale) || !isset($cff_locale) || $cff_locale == '' ) $cff_locale = 'en_US';
    if (!isset($cff_cache_time)) $cff_cache_time = 0;
    $cff_cache_time_unit = $atts[ 'cacheunit' ];
    $cff_class = $atts['class'];
    //Compile feed styles
    $cff_feed_styles = 'style="';
    if ( !empty($cff_feed_width) ) $cff_feed_styles .= 'width:' . $cff_feed_width . '; ';
    if ( !empty($cff_feed_height) ) $cff_feed_styles .= 'height:' . $cff_feed_height . '; ';
    if ( !empty($cff_feed_padding) ) $cff_feed_styles .= 'padding:' . $cff_feed_padding . '; ';
    if ( !empty($cff_bg_color) ) $cff_feed_styles .= 'background-color:#' . $cff_bg_color . '; ';
    $cff_feed_styles .= '"';
    //Like box
    $cff_like_box_position = $atts[ 'likeboxpos' ];
    $cff_like_box_outside = $atts[ 'likeboxoutside' ];
    //Open links in new window?
    $target = 'target="_blank"';
    /********** POST TYPES **********/
    $cff_show_links_type = true;
    $cff_show_event_type = true;
    $cff_show_video_type = true;
    $cff_show_photos_type = true;
    $cff_show_status_type = true;
    $cff_events_only = false;
    //Are we showing ONLY events?
    if ($cff_show_event_type && !$cff_show_links_type && !$cff_show_video_type && !$cff_show_photos_type && !$cff_show_status_type) $cff_events_only = true;
    /********** LAYOUT **********/
    $cff_includes = $atts[ 'include' ];
    //Look for non-plural version of string in the types string in case user specifies singular in shortcode
    if ( stripos($cff_includes, 'text') !== false ) $cff_show_text = true;
    if ( stripos($cff_includes, 'desc') !== false ) $cff_show_desc = true;
    if ( stripos($cff_includes, 'sharedlink') !== false ) $cff_show_shared_links = true;
    if ( stripos($cff_includes, 'date') !== false ) $cff_show_date = true;
    if ( stripos($cff_includes, 'media') !== false ) $cff_show_media = true;
    if ( stripos($cff_includes, 'eventtitle') !== false ) $cff_show_event_title = true;
    if ( stripos($cff_includes, 'eventdetail') !== false ) $cff_show_event_details = true;
    if ( stripos($cff_includes, 'social') !== false ) $cff_show_meta = true;
    if ( stripos($cff_includes, ',link') !== false ) $cff_show_link = true; //comma used to separate it from 'sharedlinks' - which also contains 'link' string
    if ( stripos($cff_includes, 'like') !== false ) $cff_show_like_box = true;
    $cff_preset_layout = 'thumb';
    
    /********** META **********/
    $cff_icon_style = $atts[ 'iconstyle' ];
    $cff_meta_text_color = $atts[ 'socialtextcolor' ];
    $cff_meta_bg_color = $atts[ 'socialbgcolor' ];
    $cff_meta_styles = 'style="';
    if ( !empty($cff_meta_text_color) ) $cff_meta_styles .= 'color:#' . $cff_meta_text_color . ';';
    if ( !empty($cff_meta_bg_color) ) $cff_meta_styles .= 'background-color:#' . $cff_meta_bg_color . ';';
    $cff_meta_styles .= '"';
    $cff_nocomments_text = $options[ 'cff_nocomments_text' ];
    $cff_hide_comments = $options[ 'cff_hide_comments' ];
    if (!isset($cff_nocomments_text) || empty($cff_nocomments_text)) $cff_hide_comments = true;
    /********** TYPOGRAPHY **********/
    //See More text
    $cff_see_more_text = $atts[ 'seemoretext' ];
    $cff_see_less_text = $atts[ 'seelesstext' ];
    //See Less text
    //Title
    $cff_title_format = $atts[ 'textformat' ];
    if (empty($cff_title_format)) $cff_title_format = 'p';
    $cff_title_size = $atts[ 'textsize' ];
    $cff_title_weight = $atts[ 'textweight' ];
    $cff_title_color = $atts[ 'textcolor' ];
    $cff_title_styles = 'style="';
    if ( !empty($cff_title_size) && $cff_title_size != 'inherit' ) $cff_title_styles .=  'font-size:' . $cff_title_size . 'px; ';
    if ( !empty($cff_title_weight) && $cff_title_weight != 'inherit' ) $cff_title_styles .= 'font-weight:' . $cff_title_weight . '; ';
    if ( !empty($cff_title_color) ) $cff_title_styles .= 'color:#' . $cff_title_color . ';';
    $cff_title_styles .= '"';
    $cff_title_link = $atts[ 'textlink' ];
    //Description
    $cff_body_size = $atts[ 'descsize' ];
    $cff_body_weight = $atts[ 'descweight' ];
    $cff_body_color = $atts[ 'desccolor' ];
    $cff_body_styles = 'style="';
    if ( !empty($cff_body_size) && $cff_body_size != 'inherit' ) $cff_body_styles .=  'font-size:' . $cff_body_size . 'px; ';
    if ( !empty($cff_body_weight) && $cff_body_weight != 'inherit' ) $cff_body_styles .= 'font-weight:' . $cff_body_weight . '; ';
    if ( !empty($cff_body_color) ) $cff_body_styles .= 'color:#' . $cff_body_color . ';';
    $cff_body_styles .= '"';
    //Event Title
    $cff_event_title_format = $atts[ 'eventtitleformat' ];
    if (empty($cff_event_title_format)) $cff_event_title_format = 'p';
    $cff_event_title_size = $atts[ 'eventtitlesize' ];
    $cff_event_title_weight = $atts[ 'eventtitleweight' ];
    $cff_event_title_color = $atts[ 'eventtitlecolor' ];
    $cff_event_title_styles = 'style="';
    if ( !empty($cff_event_title_size) && $cff_event_title_size != 'inherit' ) $cff_event_title_styles .=  'font-size:' . $cff_event_title_size . 'px; ';
    if ( !empty($cff_event_title_weight) && $cff_event_title_weight != 'inherit' ) $cff_event_title_styles .= 'font-weight:' . $cff_event_title_weight . '; ';
    if ( !empty($cff_event_title_color) ) $cff_event_title_styles .= 'color:#' . $cff_event_title_color . ';';
    $cff_event_title_styles .= '"';
    $cff_event_title_link = $atts[ 'eventtitlelink' ];
    //Event Date
    $cff_event_date_size = $atts[ 'eventdatesize' ];
    $cff_event_date_weight = $atts[ 'eventdateweight' ];
    $cff_event_date_color = $atts[ 'eventdatecolor' ];
    $cff_event_date_position = $atts[ 'eventdatepos' ];
    $cff_event_date_formatting = $atts[ 'eventdateformat' ];
    $cff_event_date_custom = $atts[ 'eventdatecustom' ];
    $cff_event_date_styles = 'style="';
    if ( !empty($cff_event_date_size) && $cff_event_date_size != 'inherit' ) $cff_event_date_styles .=  'font-size:' . $cff_event_date_size . 'px; ';
    if ( !empty($cff_event_date_weight) && $cff_event_date_weight != 'inherit' ) $cff_event_date_styles .= 'font-weight:' . $cff_event_date_weight . '; ';
    if ( !empty($cff_event_date_color) ) $cff_event_date_styles .= 'color:#' . $cff_event_date_color . ';';
    $cff_event_date_styles .= '"';
    //Event Details
    $cff_event_details_size = $atts[ 'eventdetailssize' ];
    $cff_event_details_weight = $atts[ 'eventdetailsweight' ];
    $cff_event_details_color = $atts[ 'eventdetailscolor' ];
    $cff_event_details_styles = 'style="';
    if ( !empty($cff_event_details_size) && $cff_event_details_size != 'inherit' ) $cff_event_details_styles .=  'font-size:' . $cff_event_details_size . 'px; ';
    if ( !empty($cff_event_details_weight) && $cff_event_details_weight != 'inherit' ) $cff_event_details_styles .= 'font-weight:' . $cff_event_details_weight . '; ';
    if ( !empty($cff_event_details_color) ) $cff_event_details_styles .= 'color:#' . $cff_event_details_color . ';';
    $cff_event_details_styles .= '"';
    //Date
    $cff_date_position = $atts[ 'datepos' ];
    if (!isset($cff_date_position)) $cff_date_position = 'below';
    $cff_date_size = $atts[ 'datesize' ];
    $cff_date_weight = $atts[ 'dateweight' ];
    $cff_date_color = $atts[ 'datecolor' ];
    $cff_date_styles = 'style="';
    if ( !empty($cff_date_size) && $cff_date_size != 'inherit' ) $cff_date_styles .=  'font-size:' . $cff_date_size . 'px; ';
    if ( !empty($cff_date_weight) && $cff_date_weight != 'inherit' ) $cff_date_styles .= 'font-weight:' . $cff_date_weight . '; ';
    if ( !empty($cff_date_color) ) $cff_date_styles .= 'color:#' . $cff_date_color . ';';
    $cff_date_styles .= '"';
    $cff_date_before = $options[ 'cff_date_before' ];
    $cff_date_after = $options[ 'cff_date_after' ];
    //Link to Facebook
    $cff_link_size = $atts[ 'linksize' ];
    $cff_link_weight = $atts[ 'linkweight' ];
    $cff_link_color = $atts[ 'linkcolor' ];
    $cff_link_styles = 'style="';
    if ( !empty($cff_link_size) && $cff_link_size != 'inherit' ) $cff_link_styles .=  'font-size:' . $cff_link_size . 'px; ';
    if ( !empty($cff_link_weight) && $cff_link_weight != 'inherit' ) $cff_link_styles .= 'font-weight:' . $cff_link_weight . '; ';
    if ( !empty($cff_link_color) ) $cff_link_styles .= 'color:#' . $cff_link_color . ';';
    $cff_link_styles .= '"';
    $cff_facebook_link_text = $atts[ 'facebooklinktext' ];
    $cff_view_link_text = $atts[ 'viewlinktext' ];
    $cff_link_to_timeline = $atts[ 'linktotimeline' ];
    /********** MISC **********/
    //Like Box styles
    $cff_likebox_bg_color = $atts[ 'likeboxcolor' ];
    $cff_likebox_styles = 'style="';
    if ( !empty($cff_likebox_bg_color) ) $cff_likebox_styles .=  'background-color:#' . $cff_likebox_bg_color . '; margin-left: 0; ';
    $cff_likebox_styles .= '"';
    //Video
    //Dimensions
    $cff_video_width = 640;
    $cff_video_height = $atts[ 'videoheight' ];
    
    //Action
    $cff_video_action = $atts[ 'videoaction' ];
    //Separating Line
    $cff_sep_color = $atts[ 'sepcolor' ];
    if (empty($cff_sep_color)) $cff_sep_color = 'ddd';
    $cff_sep_size = $atts[ 'sepsize' ];
    if (empty($cff_sep_size)) $cff_sep_size = 0;
    //CFF item styles
    $cff_item_styles = 'style="';
    $cff_item_styles .= 'border-bottom: ' . $cff_sep_size . 'px solid #' . $cff_sep_color . '; ';
    $cff_item_styles .= '"';
   
    //Text limits
    $title_limit = $atts['textlength'];
    if (!isset($title_limit)) $title_limit = 9999;
    $body_limit = $atts['desclength'];
    //Assign the Access Token and Page ID variables
    $access_token = get_option('cff_access_token');
    $page_id = $atts['id'];
    //Get show posts attribute. If not set then default to 25
    $show_posts = $atts['num'];
    if (empty($show_posts)) $show_posts = 25;
    if ( $show_posts == 0 || $show_posts == 'undefined' ) $show_posts = 25;
    //Check whether the Access Token is present and valid
    if ($access_token == '') {
        echo 'Please enter a valid Access Token. You can do this in the Custom Facebook Feed plugin settings.<br /><br />';
        return false;
    }
    //Check whether a Page ID has been defined
    if ($page_id == '') {
        echo "Please enter the Page ID of the Facebook feed you'd like to display.  You can do this in either the Custom Facebook Feed plugin settings or in the shortcode itself. For example [custom_facebook_feed id=<b>YOUR_PAGE_ID</b>].<br /><br />";
        return false;
    }
    //Use posts? or feed?
    $show_others = $atts['others'];
    $graph_query = 'posts';
    if ($show_others == 'on' || $show_others == 'true' || $show_others == true) $graph_query = 'feed';
    $cff_post_limit = $atts['limit'];
    //Calculate the cache time in seconds
    if($cff_cache_time_unit == 'minutes') $cff_cache_time_unit = 60;
    if($cff_cache_time_unit == 'hours') $cff_cache_time_unit = 60*60;
    if($cff_cache_time_unit == 'days') $cff_cache_time_unit = 60*60*24;
    $cache_seconds = $cff_cache_time * $cff_cache_time_unit;

    //Get like box vars
    $cff_likebox_width = $atts[ 'likeboxwidth' ];
    if ( !isset($cff_likebox_width) || empty($cff_likebox_width) || $cff_likebox_width == '' ) $cff_likebox_width = 300;
    $cff_like_box_faces = $atts[ 'likeboxfaces' ];
    if ( !isset($cff_like_box_faces) || empty($cff_like_box_faces) ) $cff_like_box_faces = 'false';

    //Set like box variable
    $like_box = '<div class="cff-likebox';
    if ($cff_like_box_outside) $like_box .= ' cff-outside';
    $like_box .= ($cff_like_box_position == 'top') ? ' cff-top' : ' cff-bottom';
    $like_box .= '"' . $cff_likebox_styles . '><script src="http://connect.facebook.net/' . $cff_locale . '/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/' . $page_id . '" width="'.$cff_likebox_width.'" show_faces="'.$cff_like_box_faces.'" stream="false" header="false"></fb:like-box></div>';
    //***START FEED***
    $content = '';
    //Add like box to the outside of the top of feed
    if ($cff_like_box_position == 'top' && $cff_show_like_box && $cff_like_box_outside) $content .= $like_box;
    //Create CFF container HTML
    $content .= '<div id="cff" rel="'.$title_limit.'" class="';
    if( !empty($cff_class) ) $content .= $cff_class . ' ';
    if ( !empty($cff_feed_height) ) $content .= 'cff-fixed-height ';
    $content .= '" ' . $cff_feed_styles . '>';
    //Add like box to the inside of the top of feed
    if ($cff_like_box_position == 'top' && $cff_show_like_box && !$cff_like_box_outside) $content .= $like_box;
    //Limit var
    $i = 0;
    
    
    //ALL POSTS
    if (!$cff_events_only){

        $cff_posts_json_url = 'https://graph.facebook.com/' . $page_id . '/' . $graph_query . '?access_token=' . $access_token . '&limit=' . $cff_post_limit . '&locale=' . $cff_locale;

        //Don't use caching if the cache time is set to zero
        if ($cff_cache_time != 0){
            // Get any existing copy of our transient data
            $transient_name = 'cff_'. $graph_query .'_json_' . $page_id;
            if ( false === ( $posts_json = get_transient( $transient_name ) ) || $posts_json === null ) {
                //Get the contents of the Facebook page
                $posts_json = cff_fetchUrl($cff_posts_json_url);
                //Cache the JSON
                set_transient( $transient_name, $posts_json, $cache_seconds );
            } else {
                $posts_json = get_transient( $transient_name );
            }
        } else {
            $posts_json = cff_fetchUrl($cff_posts_json_url);
        }
        
        //Interpret data with JSON
        $FBdata = json_decode($posts_json);
        //***STARTS POSTS LOOP***
        foreach ($FBdata->data as $news )
        {
            //Explode News and Page ID's into 2 values
            $PostID = explode("_", $news->id);
            //Check the post type
            $cff_post_type = $news->type;
            if ($cff_post_type == 'link') {
                isset($news->story) ? $story = $news->story : $story = '';
                //Check whether it's an event
                $event_link_check = "facebook.com/events/";
                $event_link_check = stripos($news->link, $event_link_check);
                if ( $event_link_check ) $cff_post_type = 'event';
            }
            //Should we show this post or not?
            $cff_show_post = false;
            switch ($cff_post_type) {
                case 'link':
                    if ( $cff_show_links_type ) $cff_show_post = true;
                    break;
                case 'event':
                    if ( $cff_show_event_type ) $cff_show_post = true;
                    break;
                case 'video':
                     if ( $cff_show_video_type ) $cff_show_post = true;
                    break;
                case 'swf':
                     if ( $cff_show_video_type ) $cff_show_post = true;
                    break;
                case 'photo':
                     if ( $cff_show_photos_type ) $cff_show_post = true;
                    break;
                case 'offer':
                     $cff_show_post = true;
                    break;
                case 'status':
                    //Check whether it's a status (author comment or like)
                    if ( $cff_show_status_type && !empty($news->message) ) $cff_show_post = true;
                    break;
            }

            //Is it a duplicate post?
            if (!isset($prev_post_message)) $prev_post_message = '';
            if (!isset($prev_post_link)) $prev_post_link = '';
            if (!isset($prev_post_description)) $prev_post_description = '';
            isset($news->message) ? $pm = $news->message : $pm = '';
            isset($news->link) ? $pl = $news->link : $pl = '';
            isset($news->description) ? $pd = $news->description : $pd = '';

            if ( ($prev_post_message == $pm) && ($prev_post_link == $pl) && ($prev_post_description == $pd) ) $cff_show_post = false;

            //Check post type and display post if selected
            if ( $cff_show_post ) {
                //If it isn't then create the post
                //Only create posts for the amount of posts specified
                if ( $i == $show_posts ) break;
                $i++;
                //********************************//
                //***COMPILE SECTION VARIABLES***//
                //********************************//
                //Set the post link
                isset($news->link) ? $link = $news->link : $link = '';
                //Is it a shared album?
                $shared_album_string = 'shared an album:';
                isset($news->story) ? $story = $news->story : $story = '';
                $shared_album = stripos($story, $shared_album_string);
                if ( $shared_album ) {
                    $link = str_replace('photo.php?','media/set/?',$link);
                }

                //If there's no link provided then link to either the Facebook page or the individual status
                if (empty($news->link)) {
                    if ($cff_link_to_timeline == true){
                        //Link to page
                        $link = 'http://facebook.com/' . $page_id;
                    } else {
                        //Link to status
                        $link = "https://www.facebook.com/" . $page_id . "/posts/" . $PostID[1];
                    }
                }

                //POST AUTHOR
                $cff_author = '<a class="cff-author" href="http://facebook.com/' . $news->from->id . '" '.$target.' title="'.$news->from->name.' on Facebook">';
                $cff_author .= '<img src="http://graph.facebook.com/' . $news->from->id . '/picture">';
                $cff_author .= '<p>'.$news->from->name.'</p>';
                $cff_author .= '</a>';
                //POST TEXT
                $cff_post_text = '<' . $cff_title_format . ' class="cff-post-text" ' . $cff_title_styles . '>';
                $cff_post_text .= '<span class="cff-text">';
                if ($cff_title_link) $cff_post_text .= '<a class="cff-post-text-link" href="'.$link.'" '.$target.'>';
                if (!empty($news->story)) $post_text = $news->story;
                if (!empty($news->message)) $post_text = $news->message;
                //Use the name if neither the story or message are available, or if the post type is an offer
                if ( (!empty($news->name) && empty($news->story) && empty($news->message)) || $cff_post_type == 'offer') $post_text = $news->name;
                //If the text is wrapped in a link then don't hyperlink any text within
                if ($cff_title_link) {
                    //Wrap links in a span so we can break the text if it's too long
                    $cff_post_text .= cff_wrap_span($post_text) . ' ';
                } else {
                    $cff_post_text .= cff_make_clickable($post_text) . ' ';
                }
                
                if ($cff_title_link) $cff_post_text .= '</a>';
                $cff_post_text .= '</span>';
                //'See More' link
                $cff_post_text .= '<span class="cff-expand">... <a href="#"><span class="cff-more">' . $cff_see_more_text . '</span><span class="cff-less">' . $cff_see_less_text . '</span></a></span>';
                $cff_post_text .= '</' . $cff_title_format . '>';
                //DESCRIPTION
                $cff_description = '';
                //Use the description if it's available and the post type isn't set to offer (offer description isn't useful)
                if (!empty($news->description) && $cff_post_type != 'offer') {
                    $description_text = $news->description;
                    if (!empty($body_limit)) {
                        if (strlen($description_text) > $body_limit) $description_text = substr($description_text, 0, $body_limit) . '...';
                    }
                    $cff_description .= '<p class="cff-post-desc" '.$cff_body_styles.'><span>' . cff_make_clickable($description_text) . '</span></p>';
                }
                //LINK
                $cff_shared_link = '';
                //Display shared link
                if ($news->type == 'link') {
                    //Display link name and description
                    if (!empty($news->description)) {
                        $cff_shared_link .= '<p class="cff-text-link ';
                        $cff_shared_link .= 'cff-no-image';
                        $cff_shared_link .= '"><a href="'.$link.'" '.$target.'>'. '<b>' . $news->name . '</b></a></p>';
                    }
                }
                //DATE
                $cff_date_formatting = $atts[ 'dateformat' ];
                $cff_date_custom = $atts[ 'datecustom' ];
                $cff_date = '<p class="cff-date" '.$cff_date_styles.'>'. $cff_date_before . ' ' . cff_getdate(strtotime($news->created_time), $cff_date_formatting, $cff_date_custom) . ' ' . $cff_date_after . '</p>';
                //EVENT
                $cff_event = '';
                if ($cff_show_event_title || $cff_show_event_details) {
                    //Check for media
                    if ($cff_post_type == 'event') {
                        
                        //Get the event id from the event URL. eg: http://www.facebook.com/events/123451234512345/
                        $event_url = parse_url($link);
                        $url_parts = explode('/', $event_url['path']);
                        //Get the id from the parts
                        $eventID = $url_parts[count($url_parts)-2];
                        
                        //Get the contents of the event using the WP HTTP API
                        $event_json = cff_fetchUrl('https://graph.facebook.com/'.$eventID.'?access_token=' . $access_token);
                        //Interpret data with JSON
                        $event_object = json_decode($event_json);
                        //Event date
                        $event_time = $event_object->start_time;
                        //If timezone migration is enabled then remove last 5 characters
                        if ( strlen($event_time) == 24 ) $event_time = substr($event_time, 0, -5);
                        if (!empty($event_time)) $cff_event_date = '<p class="cff-date" '.$cff_event_date_styles.'>' . cff_eventdate(strtotime($event_time), $cff_event_date_formatting, $cff_event_date_custom) . '</p>';
                        //EVENT
                        //Display the event details
                        $cff_event .= '<div class="cff-details">';
                        //show event date above title
                        if ($cff_event_date_position == 'above') $cff_event .= $cff_event_date;
                        //Show event title
                        if ($cff_show_event_title && !empty($event_object->name)) {
                            if ($cff_event_title_link) $cff_event .= '<a href="'.$link.'">';
                            $cff_event .= '<' . $cff_event_title_format . ' ' . $cff_event_title_styles . '>' . $event_object->name . '</' . $cff_event_title_format . '>';
                            if ($cff_event_title_link) $cff_event .= '</a>';
                        }
                        //show event date below title
                        if ($cff_event_date_position !== 'above') $cff_event .= $cff_event_date;
                        //Show event details
                        if ($cff_show_event_details){
                            //Location
                            if (!empty($event_object->location)) $cff_event .= '<p class="cff-where" ' . $cff_event_details_styles . '>' . $event_object->location . '</p>';
                            //Description
                            if (!empty($event_object->description)){
                                $description = $event_object->description;
                                if (!empty($body_limit)) {
                                    if (strlen($description) > $body_limit) $description = substr($description, 0, $body_limit) . '...';
                                }
                                $cff_event .= '<p class="cff-info" ' . $cff_event_details_styles . '>' . cff_make_clickable($description) . '</p>';
                            }
                        }
                        $cff_event .= '</div>';
                        
                    }
                }
                
                //Display the link to the Facebook post or external link
                $cff_link = '';
                //Default link
                $cff_viewpost_class = 'cff-viewpost-facebook';
                if ($cff_facebook_link_text == '') $cff_facebook_link_text = 'View on Facebook';
                $link_text = $cff_facebook_link_text;
                if (!empty($news->link)) {
                    //Check whether it links to facebook or somewhere else
                    $facebook_str = 'facebook.com';
                    if(stripos($link, $facebook_str) == false) {
                        if ($cff_view_link_text == '') $cff_view_link_text = 'View Link';
                        $link_text = $cff_view_link_text;
                    }
                    $cff_viewpost_class = 'cff-viewpost-link';
                }
                if ($cff_post_type == 'offer') $link_text = 'View Offer';
                $cff_link = '<a class="' . $cff_viewpost_class . '" href="' . $link . '" title="' . $link_text . '" ' . $target . ' ' . $cff_link_styles . '>' . $link_text . '</a>';
                //**************************//
                //***CREATE THE POST HTML***//
                //**************************//
                //Start the container
                $content .= '<div class="cff-item ';
                if ($cff_post_type == 'link') $content .= 'cff-link-item';
                if ($cff_post_type == 'event') $content .= 'cff-timeline-event';
                if ($cff_post_type == 'photo') $content .= 'cff-photo-post';
                if ($cff_post_type == 'video') $content .= 'cff-video-post';
                if ($cff_post_type == 'swf') $content .= 'cff-swf-post';
                if ($cff_post_type == 'status') $content .= 'cff-status-post';
                if ($cff_post_type == 'offer') $content .= 'cff-offer-post';
                $content .=  '" id="'. $news->id .'" ' . $cff_item_styles . '>';
                
                    //POST AUTHOR
                    if($cff_show_author) $content .= $cff_author;
                    //DATE ABOVE
                    if ($cff_show_date && $cff_date_position == 'above') $content .= $cff_date;
                    //POST TEXT
                    if($cff_show_text) $content .= $cff_post_text;
                    //DESCRIPTION
                    if($cff_show_desc) $content .= $cff_description;
                    //LINK
                    if($cff_show_shared_links) $content .= $cff_shared_link;
                    //DATE BELOW
                    if ($cff_show_date && $cff_date_position == 'below') $content .= $cff_date;
                    //EVENT
                    if($cff_show_event_title || $cff_show_event_details) $content .= $cff_event;
                    //VIEW ON FACEBOOK LINK
                    if($cff_show_link) $content .= $cff_link;
                
                //End the post item
                $content .= '</div><div class="cff-clear"></div>';
            } // End post type check

            if (isset($news->message)) $prev_post_message = $news->message;
            if (isset($news->link))  $prev_post_link = $news->link;
            if (isset($news->description))  $prev_post_description = $news->description;

        } // End the loop
    } // End ALL POSTS
    //Load more posts
    // $content .= '<button class="loadmore">Load More Posts</button>';
    //Add the Like Box inside
    if ($cff_like_box_position == 'bottom' && $cff_show_like_box && !$cff_like_box_outside) $content .= $like_box;
    //End the feed
    $content .= '</div><div class="cff-clear"></div>';
    //Add the Like Box outside
    if ($cff_like_box_position == 'bottom' && $cff_show_like_box && $cff_like_box_outside) $content .= $like_box;
    //Return our feed HTML to display
    return $content;
}
//Get JSON object of feed data
function cff_fetchUrl($url){
    //Can we use cURL?
    if(is_callable('curl_init')){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $feedData = curl_exec($ch);
        curl_close($ch);
    //If not then use file_get_contents
    } elseif ( ini_get('allow_url_fopen') == 1 || ini_get('allow_url_fopen') === TRUE ) {
        $feedData = @file_get_contents($url);
    //Or else use the WP HTTP API
    } else {
        if( !class_exists( 'WP_Http' ) ) include_once( ABSPATH . WPINC. '/class-http.php' );
        $request = new WP_Http;
        $result = $request->request($url);
        $feedData = $result['body'];
    }
    
    return $feedData;
}
//***FUNCTIONS***
//Make links in text clickable
function cff_make_clickable($text) {
    $pattern  = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
    return preg_replace_callback($pattern, 'cff_auto_link_text_callback', $text);
}
function cff_auto_link_text_callback($matches) {
    $max_url_length = 50;
    $max_depth_if_over_length = 2;
    $ellipsis = '&hellip;';
    $target = 'target="_blank"';
    $url_full = $matches[0];
    $url_short = '';
    if (strlen($url_full) > $max_url_length) {
        $parts = parse_url($url_full);
        $url_short = $parts['scheme'] . '://' . preg_replace('/^www\./', '', $parts['host']) . '/';
        $path_components = explode('/', trim($parts['path'], '/'));
        foreach ($path_components as $dir) {
            $url_string_components[] = $dir . '/';
        }
        if (!empty($parts['query'])) {
            $url_string_components[] = '?' . $parts['query'];
        }
        if (!empty($parts['fragment'])) {
            $url_string_components[] = '#' . $parts['fragment'];
        }
        for ($k = 0; $k < count($url_string_components); $k++) {
            $curr_component = $url_string_components[$k];
            if ($k >= $max_depth_if_over_length || strlen($url_short) + strlen($curr_component) > $max_url_length) {
                if ($k == 0 && strlen($url_short) < $max_url_length) {
                    // Always show a portion of first directory
                    $url_short .= substr($curr_component, 0, $max_url_length - strlen($url_short));
                }
                $url_short .= $ellipsis;
                break;
            }
            $url_short .= $curr_component;
        }
    } else {
        $url_short = $url_full;
    }
    return "<a class='cff-break-word' rel=\"nofollow\" href=\"$url_full\">$url_full</a>";
}
//Make links into span instead when the post text is made clickable
function cff_wrap_span($text) {
    $pattern  = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
    return preg_replace_callback($pattern, 'cff_wrap_span_callback', $text);
}
function cff_wrap_span_callback($matches) {
    $max_url_length = 50;
    $max_depth_if_over_length = 2;
    $ellipsis = '&hellip;';
    $target = 'target="_blank"';
    $url_full = $matches[0];
    $url_short = '';
    if (strlen($url_full) > $max_url_length) {
        $parts = parse_url($url_full);
        $url_short = $parts['scheme'] . '://' . preg_replace('/^www\./', '', $parts['host']) . '/';
        $path_components = explode('/', trim($parts['path'], '/'));
        foreach ($path_components as $dir) {
            $url_string_components[] = $dir . '/';
        }
        if (!empty($parts['query'])) {
            $url_string_components[] = '?' . $parts['query'];
        }
        if (!empty($parts['fragment'])) {
            $url_string_components[] = '#' . $parts['fragment'];
        }
        for ($k = 0; $k < count($url_string_components); $k++) {
            $curr_component = $url_string_components[$k];
            if ($k >= $max_depth_if_over_length || strlen($url_short) + strlen($curr_component) > $max_url_length) {
                if ($k == 0 && strlen($url_short) < $max_url_length) {
                    // Always show a portion of first directory
                    $url_short .= substr($curr_component, 0, $max_url_length - strlen($url_short));
                }
                $url_short .= $ellipsis;
                break;
            }
            $url_short .= $curr_component;
        }
    } else {
        $url_short = $url_full;
    }
    return "<span class='cff-break-word'>$url_short</span>";
}
//2013-04-28T21:06:56+0000
//Time stamp function - used for posts
function cff_getdate($original, $date_format, $custom_date) {
    switch ($date_format) {
        
        case '2':
            $print = date_i18n('F jS, g:i a', $original);
            break;
        case '3':
            $print = date_i18n('F jS', $original);
            break;
        case '4':
            $print = date_i18n('D F jS', $original);
            break;
        case '5':
            $print = date_i18n('l F jS', $original);
            break;
        case '6':
            $print = date_i18n('D M jS, Y', $original);
            break;
        case '7':
            $print = date_i18n('l F jS, Y', $original);
            break;
        case '8':
            $print = date_i18n('l F jS, Y - g:i a', $original);
            break;
        case '9':
            $print = date_i18n("l M jS, 'y", $original);
            break;
        case '10':
            $print = date_i18n('m.d.y', $original);
            break;
        case '11':
            $print = date_i18n('m/d/y', $original);
            break;
        case '12':
            $print = date_i18n('d.m.y', $original);
            break;
        case '13':
            $print = date_i18n('d/m/y', $original);
            break;
        default:
            
            $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
            $lengths = array("60","60","24","7","4.35","12","10");
            $now = time();
            
            // is it future date or past date
            if($now > $original) {    
                $difference = $now - $original;
                $tense = "ago";
            } else {
                $difference = $original - $now;
                $tense = "from now";
            }
            for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
                $difference /= $lengths[$j];
            }
            
            $difference = round($difference);
            
            if($difference != 1) {
                $periods[$j].= "s";
            }
            $print = "$difference $periods[$j] {$tense}";
            break;
        
    }
    if ( !empty($custom_date) ){
        $print = date_i18n($custom_date, $original);
    }
    return $print;
}
function cff_eventdate($original, $date_format, $custom_date) {
    switch ($date_format) {
        
        case '2':
            $print = date_i18n('F jS, g:ia', $original);
            break;
        case '3':
            $print = date_i18n('g:ia - F jS', $original);
            break;
        case '4':
            $print = date_i18n('g:ia, F jS', $original);
            break;
        case '5':
            $print = date_i18n('l F jS - g:ia', $original);
            break;
        case '6':
            $print = date_i18n('D M jS, Y, g:iA', $original);
            break;
        case '7':
            $print = date_i18n('l F jS, Y, g:iA', $original);
            break;
        case '8':
            $print = date_i18n('l F jS, Y - g:ia', $original);
            break;
        case '9':
            $print = date_i18n("l M jS, 'y", $original);
            break;
        case '10':
            $print = date_i18n('m.d.y - g:iA', $original);
            break;
        case '11':
            $print = date_i18n('m/d/y, g:ia', $original);
            break;
        case '12':
            $print = date_i18n('d.m.y - g:iA', $original);
            break;
        case '13':
            $print = date_i18n('d/m/y, g:ia', $original);
            break;
        default:
            $print = date_i18n('F j, Y, g:ia', $original);
            break;
    }
    if ( !empty($custom_date) ){
        $print = date_i18n($custom_date, $original);
    }
    return $print;
}
//Time stamp function - used for comments
function cff_timesince($original) {
            
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60","60","24","7","4.35","12","10");
    $now = time();
    
    // is it future date or past date
    if($now > $original) {    
        $difference = $now - $original;
        $tense = "ago";
    } else {
        $difference = $original - $now;
        $tense = "from now";
    }
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);
    
    if($difference != 1) {
        $periods[$j].= "s";
    }
    return "$difference $periods[$j] {$tense}";
            
}
//Use custom stripos function if it's not available (only available in PHP 5+)
if(!is_callable('stripos')){
    function stripos($haystack, $needle){
        return strpos($haystack, stristr( $haystack, $needle ));
    }
}

// remove_filter( 'the_content', 'wpautop' );
// add_filter( 'the_content', 'wpautop', 99 );

//Enqueue stylesheet
add_action( 'wp_enqueue_scripts', 'cff_add_my_stylesheet' );
function cff_add_my_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'cff', plugins_url('css/cff-style.css?3', __FILE__) );
    wp_enqueue_style( 'cff' );
}
//Enqueue scripts
add_action( 'wp_enqueue_scripts', 'cff_scripts_method' );
function cff_scripts_method() {
    wp_enqueue_script(
        'cffscripts',
        plugins_url( '/js/cff-scripts.js?3' , __FILE__ ),
        array( 'jquery' )
    );
}
//Allows shortcodes in theme
add_filter('widget_text', 'do_shortcode');
function cff_activate() {
    $options = get_option('cff_style_settings');
    $options[ 'cff_show_links_type' ] = true;
    $options[ 'cff_show_event_type' ] = true;
    $options[ 'cff_show_video_type' ] = true;
    $options[ 'cff_show_photos_type' ] = true;
    $options[ 'cff_show_status_type' ] = true;
    // Show all parts of the feed by default on activation
    $options[ 'cff_show_text' ] = true;
    $options[ 'cff_show_desc' ] = true;
    $options[ 'cff_show_shared_links' ] = true;
    $options[ 'cff_show_date' ] = true;
    $options[ 'cff_show_media' ] = true;
    $options[ 'cff_show_event_title' ] = true;
    $options[ 'cff_show_event_details' ] = true;
    $options[ 'cff_show_meta' ] = true;
    $options[ 'cff_show_link' ] = true;
    $options[ 'cff_show_like_box' ] = true;
    update_option( 'cff_style_settings', $options );
}
register_activation_hook( __FILE__, 'cff_activate' );
//Uninstall
function cff_uninstall()
{
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
    //Settings
    delete_option( 'cff_access_token' );
    delete_option( 'cff_page_id' );
    delete_option( 'cff_num_show' );
    delete_option( 'cff_post_limit' );
    delete_option( 'cff_show_others' );
    delete_option('cff_cache_time');
    delete_option('cff_cache_time_unit');
    delete_option( 'cff_locale' );
    //Style & Layout
    delete_option( 'cff_title_length' );
    delete_option( 'cff_body_length' );
    delete_option('cff_style_settings');
}
register_uninstall_hook( __FILE__, 'cff_uninstall' );
add_action( 'wp_head', 'cff_custom_css' );
function cff_custom_css() {
    $options = get_option('cff_style_settings');
    $cff_custom_css = $options[ 'cff_custom_css' ];
    echo '<!-- Custom Facebook Feed Custom CSS -->';
    echo "\r\n";
    echo '<style type="text/css">';
    echo "\r\n";
    echo $cff_custom_css;
    echo "\r\n";
    echo '</style>';
    echo "\r\n";
}
add_action( 'wp_footer', 'cff_js' );
function cff_js() {
    // $path = site_url();
    $url = site_url();
    $path = urlencode(ABSPATH);
    echo '<!-- Custom Facebook Feed JS -->';
    echo "\r\n";
    echo '<script type="text/javascript">';
    echo "\r\n";
    echo 'var siteURL = "' . $url . '";';
    echo "\r\n";
    echo 'var rootPath = "' . $path . '";';
    echo "\r\n";
    echo '</script>';
    echo "\r\n";
}

//Comment out the line below to view errors
error_reporting(0);
?>