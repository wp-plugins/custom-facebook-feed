<?php 
/*
Plugin Name: Custom Facebook Feed
Plugin URI: http://smashballoon.com/custom-facebook-feed
Description: Add a completely customizable Facebook feed to your WordPress site
Version: 1.3.3
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
    //Pass in shortcode attrbutes
    $atts = shortcode_atts(
        array(
            'id' => get_option('cff_page_id'),
            'show' => get_option('cff_num_show'),
            'titlelength' => get_option('cff_title_length'),
            'bodylength' => get_option('cff_body_length')
        ), $atts);

    //Assign the Access Token and Page ID variables
    $access_token = get_option('cff_access_token');
    $page_id = $atts['id'];

    //Get show posts attribute. If not set then default to 10.
    $show_posts = $atts['show'];
    if ( $show_posts == 0 || $show_posts == undefined ) $show_posts = 10;

    //Check whether the Access Token is present and valid
    if ($access_token == '') {
        echo 'Please enter a valid Access Token. You can do this in the plugin settings (Settings > Custom Facebook Feed).<br /><br />';
        return false;
    }

    //Check whether a Page ID has been defined
    if ($page_id == '') {
        echo "Please enter the Page ID of the Facebook feed you'd like to display.  You can do this in either the plugin settings (Settings > Custom Facebook Feed) or in the shortcode itself. For example [custom_facebook_feed id=<b>YOUR_PAGE_ID</b>].<br /><br />";
        return false;
    }

    

    //Get JSON object of feed data
    function fetchUrl($url){
        //Can we use cURL?
        if(is_callable('curl_init')){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

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

    //Get the contents of the Facebook page
    $json_object = fetchUrl('https://graph.facebook.com/' . $page_id . '/posts?access_token=' . $access_token);



    //Interpret data with JSON
    $FBdata = json_decode($json_object);

    //Create HTML
    $content = '<div id="cff">';
    //Limit var
    $i = 0;
    foreach ($FBdata->data as $news ) {

        //Explode News and Page ID's into 2 values
        $PostID = explode("_", $news->id);

        //Check whether it's a status (author comment or like)
        if ( ( $news->type == 'status' && !empty($news->message) ) || $news->type !== 'status' ) {
            //If it isn't then create the post

            //Only create posts for the amount of posts specified
            if ( $i == $show_posts ) break;
            $i++;

            //Start the container
            $content .= '<div class="cff-item">';

            //Text/title/description/date
            //Get text limits
            $title_limit = $atts['titlelength'];
            $body_limit = $atts['bodylength'];

            if (!empty($news->story)) { 
                $story_text = $news->story;
                if (isset($title_limit) && $title_limit !== '') {
                    if (strlen($story_text) > $title_limit) $story_text = substr($story_text, 0, $title_limit) . '...';
                }
                $story_text = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" target='_blank'>\\0</a>", $story_text);
                $content .= '<h4>' . $story_text . '</h4>';
            }
            if (!empty($news->message)) {
                $message_text = $news->message;
                if (isset($title_limit) && $title_limit !== '') {
                    if (strlen($message_text) > $title_limit) $message_text = substr($message_text, 0, $title_limit) . '...';
                }
                $message_text = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" target='_blank'>\\0</a>", $message_text);
                $content .= '<h4>' . $message_text . '</h4>';
            }
            if (!empty($news->description)) {
                $description_text = $news->description;
                if (isset($body_limit) && $body_limit !== '') {
                    if (strlen($description_text) > $body_limit) $description_text = substr($description_text, 0, $body_limit) . '...';
                }
                $content .= '<p>' . $description_text . '</p>';
            }


            //Posted on
            $content .= '<p class="cff-date">Posted '. timeSince(strtotime($news->created_time)) . ' ago</p>';


            //Check whether it's a shared link
            if ($news->type == 'link') {

                $story = $news->story;

                //Check whether it's an event
                $created_event = 'created an event.';
                $shared_event = 'shared an event.';

                if ( stripos($story, $created_event) !== false || stripos($story, $shared_event) !== false ){
                    //Get the event object
                    $eventID = $PostID[1];
                    //Get the contents of the event
                    $event_json = fetchUrl('https://graph.facebook.com/'.$eventID.'?access_token=' . $access_token);

                    //Interpret data with JSON
                    $event_object = json_decode($event_json);

                    //Display the event details
                    $content .= '<div class="details">';
                    if (!empty($event_object->name)) $content .= '<h5>' . $event_object->name . '</h5>';
                    if (!empty($event_object->location)) $content .= '<p>Where: ' . $event_object->location . '</p>';
                    if (!empty($event_object->start_time)) $content .= '<p>When: ' . date("F j, Y, g:i a", strtotime($event_object->start_time)) . '</p>';
                    if (!empty($event_object->description)){
                        $description = $event_object->description;
                        if (isset($body_limit) && $body_limit !== '') {
                            if (strlen($description) > $body_limit) $description = substr($description, 0, $body_limit) . '...';
                        }
                        $content .= '<p>' . $description . '</p>';
                    }

                    $content .= '</div><!-- end .details -->';
                }

            }

            //Show link
            if (!empty($news->link)) {
                $link = $news->link;

                //Check whether it links to facebook or somewhere else
                $facebook_str = 'facebook.com';

                if(stripos($link, $facebook_str) !== false) {
                    $link_text = 'View on Facebook';
                } else {
                    $link_text = 'View Link';
                }
                $content .= '<a class="cff-viewpost" href="' . $link . '" title="' . $link_text . '">' . $link_text . '</a>';
            }


            //End item
            $content .= '</div> <!-- end .cff-item -->';

        } //End status check

    } //End the loop


    //Add the Like Box
    $content .= '<div class="cff-likebox"><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/' . $page_id . '" width="200" show_faces="false" stream="false" header="true"></fb:like-box></div>';
    $content .= '</div><div class="clear"></div> <!-- end .Custom Facebook Feed -->';

    //Return our feed HTML to display
    return $content;

}





//Time stamp function

function timeSince($original) {

    // Array of time period
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'minute'),
    );

    // Current time
    $today = time();   
    $since = $today - $original;

    // $j saves performing the count function each time around the loop
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];

        // finding the biggest chunk (if the chunk fits, break)
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";

    if ($i + 1 < $j) {
        // now getting the second item
        $seconds2 = $chunks[$i + 1][0];
        $name2 = $chunks[$i + 1][1];

        // add second item if it's greater than 0
        if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
            $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
        }
    }
    return $print;
}



//Enqueue stylesheet
add_action( 'wp_enqueue_scripts', 'cff_add_my_stylesheet' );
function cff_add_my_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'cff', plugins_url('css/style.css', __FILE__) );
    wp_enqueue_style( 'cff' );
}

//Allows shortcodes in sidebar of theme
add_filter('widget_text', 'do_shortcode'); 

//Uninstall
function cff_uninstall()
{
    if ( ! current_user_can( 'activate_plugins' ) )
        return;

    delete_option( 'cff_access_token' );
    delete_option( 'cff_page_id' );
    delete_option( 'cff_num_show' );
    delete_option( 'cff_title_length' );
    delete_option( 'cff_body_length' );
}
register_uninstall_hook( __FILE__, 'cff_uninstall' );


//Comment out the line below to view errors
error_reporting(0);
 
?>