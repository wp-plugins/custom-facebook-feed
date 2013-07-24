<?php 
function cff_menu() {
    add_menu_page(
        '',
        'Facebook Feed',
        'manage_options',
        'cff-top',
        'cff_settings_page'
    );
    add_submenu_page(
        'cff-top',
        'Settings',
        'Settings',
        'manage_options',
        'cff-top',
        'cff_settings_page'
    );
}
add_action('admin_menu', 'cff_menu');
//Add styling page
function cff_styling_menu() {
    add_submenu_page(
        'cff-top',
        'Layout & Style',
        'Layout & Style',
        'manage_options',
        'cff-style',
        'cff_style_page'
    );
}
add_action('admin_menu', 'cff_styling_menu');

//Add system info page
function cff_system_menu() {
    add_submenu_page(
        'cff-top',
        'System Info',
        'System Info',
        'manage_options',
        'cff-system',
        'cff_system_info'
    );
}
add_action('admin_menu', 'cff_system_menu');

//Create Settings page
function cff_system_info() { ?>
 
    <div class="wrap">
        <h2><?php _e('System Info'); ?></h2>

        <p>PHP Version:                    <b><?php echo PHP_VERSION . "\n"; ?></b></p>
        <p>Web Server Info:                <b><?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?></b></p>

        <p>PHP allow_url_fopen:            <b><?php echo ini_get( 'allow_url_fopen' ) ? "<span style='color: green;'>Yes</span>" : "<span style='color: red;'>No</span>"; ?></b></p>
        <p>PHP cURL:                       <b><?php echo is_callable('curl_init') ? "<span style='color: green;'>Yes</span>" : "<span style='color: red;'>No</span>" ?></b></p>
        <p>JSON:                       <b><?php echo function_exists("json_decode") ? "<span style='color: green;'>Yes</span>" : "<span style='color: red;'>No</span>" ?></b></p>
    </div>

<?php 
} //End cff_system_info 

// function cff_register_option() {
//     // creates our settings in the options table
//     register_setting('cff_license', 'cff_license_key', 'cff_sanitize_license' );
// }
// add_action('admin_init', 'cff_register_option');

//Create Settings page
function cff_settings_page() {
    //Declare variables for fields
    $hidden_field_name  = 'cff_submit_hidden';
    $access_token       = 'cff_access_token';
    $page_id            = 'cff_page_id';
    $num_show           = 'cff_num_show';
    // Read in existing option value from database
    $access_token_val = get_option( $access_token );
    $page_id_val = get_option( $page_id );
    $num_show_val = get_option( $num_show );
    // See if the user has posted us some information. If they did, this hidden field will be set to 'Y'.
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $access_token_val = $_POST[ $access_token ];
        $page_id_val = $_POST[ $page_id ];
        $num_show_val = $_POST[ $num_show ];
        // Save the posted value in the database
        update_option( $access_token, $access_token_val );
        update_option( $page_id, $page_id_val );
        update_option( $num_show, $num_show_val );
        // Put an settings updated message on the screen 
    ?>
    <div class="updated"><p><strong><?php _e('Settings saved.', 'custom-facebook-feed' ); ?></strong></p></div>
    <?php } ?> 
 
    <div class="wrap">
        <h2><?php _e('Custom Facebook Feed Settings'); ?></h2>
        <form name="form1" method="post" action="">
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
            <h3><?php _e('Configuration'); ?></h3>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e('Access Token'); ?></th>
                        <td>
                            <input name="cff_access_token" type="text" value="<?php esc_attr_e( $access_token_val ); ?>" size="60" />
                            <a href="http://smashballoon.com/custom-facebook-feed/access-token/" target="_blank">How to get an Access Token</a>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Page ID'); ?></th>
                        <td>
                            <input name="cff_page_id" type="text" value="<?php esc_attr_e( $page_id_val ); ?>" size="60" />
                            <a href="http://smashballoon.com/custom-facebook-feed/faq/" target="_blank">What's my Page ID?</a>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Number of posts to display'); ?></th>
                        <td>
                            <input name="cff_num_show" type="text" value="<?php esc_attr_e( $num_show_val ); ?>" size="4" />
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php submit_button(); ?>
        </form>
        <hr />
        <h4>Displaying your Feed</h4>
        <p>Copy and paste this shortcode directly into the page, post or widget where you'd like the feed to show up:</p>
        <input type="text" value="[custom-facebook-feed]" size="23" />
        <p>You can override the settings above directly in the shortcode like so:</p>
        <p>[custom-facebook-feed <b>id=Your_Page_ID show=3 titlelength=100 bodylength=150</b>]</p>
        <br />
        <hr />
        <br />
        <p><a href="http://smashballoon.com/custom-facebook-feed/support" target="_blank">Plugin Support</a> - Smash Balloon is committed to making this plugin better. Please let us know if you have had any issues when using this plugin so that we can continue to improve it!</p>
        <br /><br />
        <a href="http://smashballoon.com/custom-facebook-feed/demo" target="_blank"><img src="<?php echo plugins_url( 'img/pro.png' , __FILE__ ) ?>" /></a>
<?php 
} //End Settings_Page 
//Create Style page
function cff_style_page() {
    //Declare variables for fields
    $style_hidden_field_name    = 'cff_style_submit_hidden';
    $defaults = array(
        //Include
        'cff_show_text'             => true,
        'cff_show_desc'             => true,
        'cff_show_date'             => true,
        'cff_show_event_title'      => true,
        'cff_show_event_details'    => true,
        'cff_show_link'             => true,
        'cff_show_like_box'         => true,
        //Typography
        'cff_title_format'          => 'p',
        'cff_title_size'            => 'inherit',
        'cff_title_weight'          => 'inherit',
        'cff_title_color'           => '',
        'cff_body_size'             => 'inherit',
        'cff_body_weight'           => 'inherit',
        'cff_body_color'            => '',
        'cff_event_title_format'    => 'p',
        'cff_event_title_size'      => 'inherit',
        'cff_event_title_weight'    => 'inherit',
        'cff_event_title_color'     => '',
        'cff_event_details_size'    => 'inherit',
        'cff_event_details_weight'  => 'inherit',
        'cff_event_details_color'   => '',
        'cff_date_size'             => 'inherit',
        'cff_date_weight'           => 'inherit',
        'cff_date_color'            => '',
        'cff_link_size'             => 'inherit',
        'cff_link_weight'           => 'inherit',
        'cff_link_color'            => '',
        //Misc
        'cff_feed_width'            => '',
        'cff_feed_height'           => '',
        'cff_feed_padding'           => '',
        'cff_like_box_position'     => 'bottom',
        'cff_bg_color'              => '',
        'cff_likebox_bg_color'      => '',

        //New
        'cff_custom_css'            => '',
        'cff_title_link'            => false,
        'cff_event_title_link'      => false,
        'cff_sep_color'             => '',
        'cff_sep_size'              => '1'
    );
    //Save layout options in an array
    add_option( 'cff_style_settings', $options );
    $options = wp_parse_args(get_option('cff_style_settings'), $defaults);
    //Set the page variables
    //Include
    $cff_show_text = $options[ 'cff_show_text' ];
    $cff_show_desc = $options[ 'cff_show_desc' ];
    $cff_show_date = $options[ 'cff_show_date' ];
    $cff_show_event_title = $options[ 'cff_show_event_title' ];
    $cff_show_event_details = $options[ 'cff_show_event_details' ];
    $cff_show_link = $options[ 'cff_show_link' ];
    $cff_show_like_box = $options[ 'cff_show_like_box' ];
    //Typography
    $cff_title_format = $options[ 'cff_title_format' ];
    $cff_title_size = $options[ 'cff_title_size' ];
    $cff_title_weight = $options[ 'cff_title_weight' ];
    $cff_title_color = $options[ 'cff_title_color' ];
    $cff_body_size = $options[ 'cff_body_size' ];
    $cff_body_weight = $options[ 'cff_body_weight' ];
    $cff_body_color = $options[ 'cff_body_color' ];
    $cff_event_title_format = $options[ 'cff_event_title_format' ];
    $cff_event_title_size = $options[ 'cff_event_title_size' ];
    $cff_event_title_weight = $options[ 'cff_event_title_weight' ];
    $cff_event_title_color = $options[ 'cff_event_title_color' ];
    $cff_event_details_size = $options[ 'cff_event_details_size' ];
    $cff_event_details_weight = $options[ 'cff_event_details_weight' ];
    $cff_event_details_color = $options[ 'cff_event_details_color' ];
    $cff_date_size = $options[ 'cff_date_size' ];
    $cff_date_weight = $options[ 'cff_date_weight' ];
    $cff_date_color = $options[ 'cff_date_color' ];
    $cff_link_size = $options[ 'cff_link_size' ];
    $cff_link_weight = $options[ 'cff_link_weight' ];
    $cff_link_color = $options[ 'cff_link_color' ];
    //Misc
    $cff_feed_width = $options[ 'cff_feed_width' ];
    $cff_feed_height = $options[ 'cff_feed_height' ];
    $cff_feed_padding = $options[ 'cff_feed_padding' ];
    $cff_like_box_position = $options[ 'cff_like_box_position' ];
    $cff_open_links = $options[ 'cff_open_links' ];
    $cff_bg_color = $options[ 'cff_bg_color' ];
    $cff_likebox_bg_color = $options[ 'cff_likebox_bg_color' ];

    //New
    $cff_custom_css = $options[ 'cff_custom_css' ];
    $cff_title_link = $options[ 'cff_title_link' ];
    $cff_event_title_link = $options[ 'cff_event_title_link' ];
    $cff_sep_color = $options[ 'cff_sep_color' ];
    $cff_sep_size = $options[ 'cff_sep_size' ];
    
    // Texts lengths
    $cff_title_length   = 'cff_title_length';
    $cff_body_length    = 'cff_body_length';
    // Read in existing option value from database
    $cff_title_length_val = get_option( $cff_title_length );
    $cff_body_length_val = get_option( $cff_body_length );
    // See if the user has posted us some information. If they did, this hidden field will be set to 'Y'.
    if( isset($_POST[ $style_hidden_field_name ]) && $_POST[ $style_hidden_field_name ] == 'Y' ) {
    
        // Read their posted value
        $cff_title_length_val = $_POST[ $cff_title_length ];
        $cff_body_length_val = $_POST[ $cff_body_length ];
        // Save the posted value in the database
        update_option( $cff_title_length, $cff_title_length_val );
        update_option( $cff_body_length, $cff_body_length_val );
    
        //Update the page variable
        //Include
        $cff_show_text = $_POST[ 'cff_show_text' ];
        $cff_show_desc = $_POST[ 'cff_show_desc' ];
        $cff_show_date = $_POST[ 'cff_show_date' ];
        $cff_show_event_title = $_POST[ 'cff_show_event_title' ];
        $cff_show_event_details = $_POST[ 'cff_show_event_details' ];
        $cff_show_link = $_POST[ 'cff_show_link' ];
        $cff_show_like_box = $_POST[ 'cff_show_like_box' ];
        //Typography
        $cff_title_format = $_POST[ 'cff_title_format' ];
        $cff_title_size = $_POST[ 'cff_title_size' ];
        $cff_title_weight = $_POST[ 'cff_title_weight' ];
        $cff_title_color = $_POST[ 'cff_title_color' ];
        $cff_body_size = $_POST[ 'cff_body_size' ];
        $cff_body_weight = $_POST[ 'cff_body_weight' ];
        $cff_body_color = $_POST[ 'cff_body_color' ];
        $cff_event_title_format = $_POST[ 'cff_event_title_format' ];
        $cff_event_title_size = $_POST[ 'cff_event_title_size' ];
        $cff_event_title_weight = $_POST[ 'cff_event_title_weight' ];
        $cff_event_title_color = $_POST[ 'cff_event_title_color' ];
        $cff_event_details_size = $_POST[ 'cff_event_details_size' ];
        $cff_event_details_weight = $_POST[ 'cff_event_details_weight' ];
        $cff_event_details_color = $_POST[ 'cff_event_details_color' ];
        $cff_date_size = $_POST[ 'cff_date_size' ];
        $cff_date_weight = $_POST[ 'cff_date_weight' ];
        $cff_date_color = $_POST[ 'cff_date_color' ];
        $cff_link_size = $_POST[ 'cff_link_size' ];
        $cff_link_weight = $_POST[ 'cff_link_weight' ];
        $cff_link_color = $_POST[ 'cff_link_color' ];
        //Misc
        $cff_feed_width = $_POST[ 'cff_feed_width' ];
        $cff_feed_height = $_POST[ 'cff_feed_height' ];
        $cff_feed_padding = $_POST[ 'cff_feed_padding' ];
        $cff_like_box_position = $_POST[ 'cff_like_box_position' ];
        $cff_open_links = $_POST[ 'cff_open_links' ];
        $cff_bg_color = $_POST[ 'cff_bg_color' ];
        $cff_likebox_bg_color = $_POST[ 'cff_likebox_bg_color' ];
        //New
        $cff_custom_css = $_POST[ 'cff_custom_css' ];
        $cff_title_link = $_POST[ 'cff_title_link' ];
        $cff_event_title_link = $_POST[ 'cff_event_title_link' ];
        $cff_sep_color = $_POST[ 'cff_sep_color' ];
        $cff_sep_size = $_POST[ 'cff_sep_size' ];

        //Update the options in the array in the database
        //Include
        $options[ 'cff_show_text' ] = $cff_show_text;
        $options[ 'cff_show_desc' ] = $cff_show_desc;
        $options[ 'cff_show_date' ] = $cff_show_date;
        $options[ 'cff_show_event_title' ] = $cff_show_event_title;
        $options[ 'cff_show_event_details' ] = $cff_show_event_details;
        $options[ 'cff_show_link' ] = $cff_show_link;
        $options[ 'cff_show_like_box' ] = $cff_show_like_box;
        //Typography
        $options[ 'cff_title_format' ] = $cff_title_format;
        $options[ 'cff_title_size' ] = $cff_title_size;
        $options[ 'cff_title_weight' ] = $cff_title_weight;
        $options[ 'cff_title_color' ] = $cff_title_color;
        $options[ 'cff_body_size' ] = $cff_body_size;
        $options[ 'cff_body_weight' ] = $cff_body_weight;
        $options[ 'cff_body_color' ] = $cff_body_color;
        $options[ 'cff_event_title_format' ] = $cff_event_title_format;
        $options[ 'cff_event_title_size' ] = $cff_event_title_size;
        $options[ 'cff_event_title_weight' ] = $cff_event_title_weight;
        $options[ 'cff_event_title_color' ] = $cff_event_title_color;
        $options[ 'cff_event_details_size' ] = $cff_event_details_size;
        $options[ 'cff_event_details_weight' ] = $cff_event_details_weight;
        $options[ 'cff_event_details_color' ] = $cff_event_details_color;
        $options[ 'cff_date_size' ] = $cff_date_size;
        $options[ 'cff_date_weight' ] = $cff_date_weight;
        $options[ 'cff_date_color' ] = $cff_date_color;
        $options[ 'cff_link_size' ] = $cff_link_size;
        $options[ 'cff_link_weight' ] = $cff_link_weight;
        $options[ 'cff_link_color' ] = $cff_link_color;
        //Misc
        $options[ 'cff_feed_width' ] = $cff_feed_width;
        $options[ 'cff_feed_height' ] = $cff_feed_height;
        $options[ 'cff_feed_padding' ] = $cff_feed_padding;
        $options[ 'cff_like_box_position' ] = $cff_like_box_position;
        $options[ 'cff_open_links' ] = $cff_open_links;
        $options[ 'cff_bg_color' ] = $cff_bg_color;
        $options[ 'cff_likebox_bg_color' ] = $cff_likebox_bg_color;
        //New
        $options[ 'cff_custom_css' ] = $cff_custom_css;
        $options[ 'cff_title_link' ] = $cff_title_link;
        $options[ 'cff_event_title_link' ] = $cff_event_title_link;
        $options[ 'cff_sep_color' ] = $cff_sep_color;
        $options[ 'cff_sep_size' ] = $cff_sep_size;

        //Update the array
        update_option( 'cff_style_settings', $options );
        // Put an settings updated message on the screen 
    ?>
    <div class="updated"><p><strong><?php _e('Settings saved.', 'custom-facebook-feed' ); ?></strong></p></div>
    <?php } ?> 
 
    <div class="wrap">
        <h2><?php _e('Custom Facebook Feed - Layout and Style'); ?></h2>
        <form name="form1" method="post" action="">
            <input type="hidden" name="<?php echo $style_hidden_field_name; ?>" value="Y">
            <br />
            <hr />
            <table class="form-table">
                <tbody>
                    <h3><?php _e('General'); ?></h3>
                    <tr valign="top">
                        <th scope="row"><?php _e('Feed Width'); ?></th>
                        <td>
                            <input name="cff_feed_width" type="text" value="<?php esc_attr_e( $cff_feed_width ); ?>" size="6" />
                            <span>Eg. 500px, 50%, 10em.  <i style="color: #666; font-size: 11px; margin-left: 5px;">Default is 100%</i></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Feed Height'); ?></th>
                        <td>
                            <input name="cff_feed_height" type="text" value="<?php esc_attr_e( $cff_feed_height ); ?>" size="6" />
                            <span>Eg. 500px, 50em. <i style="color: #666; font-size: 11px; margin-left: 5px;">Leave empty to set no maximum height. If the feed exceeds this height then a scroll bar will be used.</i></span>
                        </td>
                    </tr>
                        <th scope="row"><?php _e('Feed Padding'); ?></th>
                        <td>
                            <input name="cff_feed_padding" type="text" value="<?php esc_attr_e( $cff_feed_padding ); ?>" size="6" />
                            <span>Eg. 20px, 5%. <i style="color: #666; font-size: 11px; margin-left: 5px;">This is the amount of padding/spacing that goes around the feed. This is particularly useful if you intend to set a background color on the feed.</i></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Feed Background Color'); ?></th>
                        <td>
                            <label for="cff_bg_color">#</label>
                            <input name="cff_bg_color" type="text" value="<?php esc_attr_e( $cff_bg_color ); ?>" size="10" />
                            <span>Eg. ED9A00 <a href="http://www.colorpicker.com/" target="_blank">Color Picker</a></span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <br />
            <hr />

            <table class="form-table">
                <tbody>

                    <h3><?php _e('Post Types'); ?></h3>
                    <tr valign="top">
                        <th scope="row"><?php _e('Show these types of posts:'); ?></th>
                        <td>
                            <input name="cff_show_status_type" type="checkbox" id="cff_show_status_type" disabled checked />
                            <label for="cff_show_status_type">Statuses</label>
                            &nbsp;&nbsp;
                        
                            <input type="checkbox" name="cff_show_event_type" id="cff_show_event_type" disabled checked />
                            <label for="cff_show_event_type">Events</label>
                            &nbsp;&nbsp;
                            <input type="checkbox" name="cff_show_photos_type" id="cff_show_photos_type" disabled checked />
                            <label for="cff_show_photos_type">Photos</label>
                            &nbsp;&nbsp;
                            <input type="checkbox" name="cff_show_video_type" id="cff_show_video_type" disabled checked />
                            <label for="cff_show_video_type">Videos</label>
                            &nbsp;&nbsp;
                            <input type="checkbox" name="cff_show_links_type" id="cff_show_links_type" disabled checked />
                            <label for="cff_show_links_type">Links</label>
                            &nbsp;
                            <i style="color: #666; font-size: 11px; margin-left: 5px;"><a href="http://smashballoon.com/custom-facebook-feed/" target="_blank">Upgrade to Pro</a></i>
                        </td>
                    </tr>

                </tbody>
            </table>
            <br />
            <hr />
            <h3><?php _e('Post Layout'); ?></h3>
            <table class="form-table">
                <tbody>
                    <tr>
                        <td><p>Choose a post layout:</p></td>
                        <td>
                            <select name="cff_preset_layout" disabled>
                                <option>Thumbnail</option>
                                <option>Half-width</option>
                                <option>Full-width</option>
                            </select>
                            <i style="color: #666; font-size: 11px; margin-left: 5px;"><a href="http://smashballoon.com/custom-facebook-feed/" target="_blank">Upgrade to Pro</a></i>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Include the following in posts (when applicable):'); ?></th>
                        <td>
                            <div>
                                <input name="cff_show_text" type="checkbox" id="cff_show_text" <?php if($cff_show_text == true) echo "checked"; ?> />
                                <label for="cff_show_text">Post text</label>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_desc" id="cff_show_desc" <?php if($cff_show_desc == true) echo 'checked="checked"' ?> />
                                <label for="cff_show_desc">Video/link description</label>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_date" id="cff_show_date" <?php if($cff_show_date == true) echo 'checked="checked"' ?> />
                                <label for="cff_show_date">Date</label>
                            </div>
                            <div>
                                <input type="checkbox" disabled />
                                <label style="color: #999;">Photos/videos</label><i style="color: #666; font-size: 11px; margin-left: 5px;"><a href="http://smashballoon.com/custom-facebook-feed/" target="_blank">Upgrade to Pro</a></i>
                            </div>
                            
                            <div>
                                <input type="checkbox" name="cff_show_event_title" id="cff_show_event_title" <?php if($cff_show_event_title == true) echo 'checked="checked"' ?> />
                                <label for="cff_show_event_title">Event title</label>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_event_details" id="cff_show_event_details" <?php if($cff_show_event_details == true) echo 'checked="checked"' ?> />
                                <label for="cff_show_event_details">Event details</label>
                            </div>
                            <div>
                                <input type="checkbox" disabled />
                                <label style="color: #999;">Like/shares/comments</label><i style="color: #666; font-size: 11px; margin-left: 5px;"><a href="http://smashballoon.com/custom-facebook-feed/" target="_blank">Upgrade to Pro</a></i>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_link" id="cff_show_link" <?php if($cff_show_link == true) echo 'checked="checked"' ?> />
                                <label for="cff_show_link">View on Facebook/View Link</label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php submit_button(); ?>
            <hr />
            <h3><?php _e('Typography'); ?></h3>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e('Post Text'); ?></th>
                        
                        <!-- <p>What does inherit mean?</p> -->
                        <td>
                            <label for="cff_title_format">Format</label>
                            <select name="cff_title_format">
                                <option value="p" <?php if($cff_title_format == "p") echo 'selected="selected"' ?> >Paragraph</option>
                                <option value="h3" <?php if($cff_title_format == "h3") echo 'selected="selected"' ?> >Heading 3</option>
                                <option value="h4" <?php if($cff_title_format == "h4") echo 'selected="selected"' ?> >Heading 4</option>
                                <option value="h5" <?php if($cff_title_format == "h5") echo 'selected="selected"' ?> >Heading 5</option>
                                <option value="h6" <?php if($cff_title_format == "h6") echo 'selected="selected"' ?> >Heading 6</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_title_size">Font Size</label>
                            <select name="cff_title_size">
                                <option value="inherit" <?php if($cff_title_size == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                <option value="10" <?php if($cff_title_size == "10") echo 'selected="selected"' ?> >10px</option>
                                <option value="11" <?php if($cff_title_size == "11") echo 'selected="selected"' ?> >11px</option>
                                <option value="12" <?php if($cff_title_size == "12") echo 'selected="selected"' ?> >12px</option>
                                <option value="14" <?php if($cff_title_size == "14") echo 'selected="selected"' ?> >14px</option>
                                <option value="16" <?php if($cff_title_size == "16") echo 'selected="selected"' ?> >16px</option>
                                <option value="18" <?php if($cff_title_size == "18") echo 'selected="selected"' ?> >18px</option>
                                <option value="20" <?php if($cff_title_size == "20") echo 'selected="selected"' ?> >20px</option>
                                <option value="24" <?php if($cff_title_size == "24") echo 'selected="selected"' ?> >24px</option>
                                <option value="28" <?php if($cff_title_size == "28") echo 'selected="selected"' ?> >28px</option>
                                <option value="32" <?php if($cff_title_size == "32") echo 'selected="selected"' ?> >32px</option>
                                <option value="36" <?php if($cff_title_size == "36") echo 'selected="selected"' ?> >36px</option>
                                <option value="42" <?php if($cff_title_size == "42") echo 'selected="selected"' ?> >42px</option>
                                <option value="48" <?php if($cff_title_size == "48") echo 'selected="selected"' ?> >48px</option>
                                <option value="60" <?php if($cff_title_size == "54") echo 'selected="selected"' ?> >54px</option>
                                <option value="60" <?php if($cff_title_size == "60") echo 'selected="selected"' ?> >60px</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_title_weight">Font Weight</label>
                            <select name="cff_title_weight">
                                <option value="inherit" <?php if($cff_title_weight == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                <option value="normal" <?php if($cff_title_weight == "normal") echo 'selected="selected"' ?> >Normal</option>
                                <option value="bold" <?php if($cff_title_weight == "bold") echo 'selected="selected"' ?> >Bold</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_title_color">Font Color&nbsp;&nbsp;#</label>
                            <input name="cff_title_color" type="text" value="<?php esc_attr_e( $cff_title_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                            <span><a href="http://www.colorpicker.com/" target="_blank">Color Picker</a></span>
                            &nbsp;&nbsp;
                            <input type="checkbox" name="cff_title_link" id="cff_title_link" <?php if($cff_title_link == true) echo 'checked="checked"' ?> />
                            <label for="cff_title_link">Link to Facebook post</label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Link Description'); ?></th>
                        
                        <td>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label for="cff_body_size">Font Size</label>
                            <select name="cff_body_size">
                                <option value="inherit" <?php if($cff_body_size == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                <option value="10" <?php if($cff_body_size == "10") echo 'selected="selected"' ?> >10px</option>
                                <option value="11" <?php if($cff_body_size == "11") echo 'selected="selected"' ?> >11px</option>
                                <option value="12" <?php if($cff_body_size == "12") echo 'selected="selected"' ?> >12px</option>
                                <option value="14" <?php if($cff_body_size == "14") echo 'selected="selected"' ?> >14px</option>
                                <option value="16" <?php if($cff_body_size == "16") echo 'selected="selected"' ?> >16px</option>
                                <option value="18" <?php if($cff_body_size == "18") echo 'selected="selected"' ?> >18px</option>
                                <option value="20" <?php if($cff_body_size == "20") echo 'selected="selected"' ?> >20px</option>
                                <option value="24" <?php if($cff_body_size == "24") echo 'selected="selected"' ?> >24px</option>
                                <option value="28" <?php if($cff_body_size == "28") echo 'selected="selected"' ?> >28px</option>
                                <option value="32" <?php if($cff_body_size == "32") echo 'selected="selected"' ?> >32px</option>
                                <option value="36" <?php if($cff_body_size == "36") echo 'selected="selected"' ?> >36px</option>
                                <option value="42" <?php if($cff_body_size == "42") echo 'selected="selected"' ?> >42px</option>
                                <option value="48" <?php if($cff_body_size == "48") echo 'selected="selected"' ?> >48px</option>
                                <option value="60" <?php if($cff_body_size == "54") echo 'selected="selected"' ?> >54px</option>
                                <option value="60" <?php if($cff_body_size == "60") echo 'selected="selected"' ?> >60px</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_body_weight">Font Weight</label>
                            <select name="cff_body_weight">
                                <option value="inherit" <?php if($cff_body_weight == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                <option value="normal" <?php if($cff_body_weight == "normal") echo 'selected="selected"' ?> >Normal</option>
                                <option value="bold" <?php if($cff_body_weight == "bold") echo 'selected="selected"' ?> >Bold</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_body_color">Font Color&nbsp;&nbsp;#</label>
                            <input name="cff_body_color" type="text" value="<?php esc_attr_e( $cff_body_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                            <a href="http://www.colorpicker.com/" target="_blank">Color Picker</a>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Event Title'); ?></th>
                        
                        <td>
                            <label for="cff_event_title_format">Format</label>
                            <select name="cff_event_title_format">
                                <option value="p" <?php if($cff_event_title_format == "p") echo 'selected="selected"' ?> >Paragraph</option>
                                <option value="h3" <?php if($cff_event_title_format == "h3") echo 'selected="selected"' ?> >Heading 3</option>
                                <option value="h4" <?php if($cff_event_title_format == "h4") echo 'selected="selected"' ?> >Heading 4</option>
                                <option value="h5" <?php if($cff_event_title_format == "h5") echo 'selected="selected"' ?> >Heading 5</option>
                                <option value="h6" <?php if($cff_event_title_format == "h6") echo 'selected="selected"' ?> >Heading 6</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_event_title_size">Font Size</label>
                            <select name="cff_event_title_size">
                                <option value="inherit" <?php if($cff_event_title_size == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                <option value="10" <?php if($cff_event_title_size == "10") echo 'selected="selected"' ?> >10px</option>
                                <option value="11" <?php if($cff_event_title_size == "11") echo 'selected="selected"' ?> >11px</option>
                                <option value="12" <?php if($cff_event_title_size == "12") echo 'selected="selected"' ?> >12px</option>
                                <option value="14" <?php if($cff_event_title_size == "14") echo 'selected="selected"' ?> >14px</option>
                                <option value="16" <?php if($cff_event_title_size == "16") echo 'selected="selected"' ?> >16px</option>
                                <option value="18" <?php if($cff_event_title_size == "18") echo 'selected="selected"' ?> >18px</option>
                                <option value="20" <?php if($cff_event_title_size == "20") echo 'selected="selected"' ?> >20px</option>
                                <option value="24" <?php if($cff_event_title_size == "24") echo 'selected="selected"' ?> >24px</option>
                                <option value="28" <?php if($cff_event_title_size == "28") echo 'selected="selected"' ?> >28px</option>
                                <option value="32" <?php if($cff_event_title_size == "32") echo 'selected="selected"' ?> >32px</option>
                                <option value="36" <?php if($cff_event_title_size == "36") echo 'selected="selected"' ?> >36px</option>
                                <option value="42" <?php if($cff_event_title_size == "42") echo 'selected="selected"' ?> >42px</option>
                                <option value="48" <?php if($cff_event_title_size == "48") echo 'selected="selected"' ?> >48px</option>
                                <option value="60" <?php if($cff_event_title_size == "54") echo 'selected="selected"' ?> >54px</option>
                                <option value="60" <?php if($cff_event_title_size == "60") echo 'selected="selected"' ?> >60px</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_event_title_weight">Font Weight</label>
                            <select name="cff_event_title_weight">
                                <option value="inherit" <?php if($cff_event_title_weight == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                <option value="normal" <?php if($cff_event_title_weight == "normal") echo 'selected="selected"' ?> >Normal</option>
                                <option value="bold" <?php if($cff_event_title_weight == "bold") echo 'selected="selected"' ?> >Bold</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_event_title_color">Font Color&nbsp;&nbsp;#</label>
                            <input name="cff_event_title_color" type="text" value="<?php esc_attr_e( $cff_event_title_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                            <a href="http://www.colorpicker.com/" target="_blank">Color Picker</a>
                            &nbsp;&nbsp;
                            <input type="checkbox" name="cff_event_title_link" id="cff_event_title_link" <?php if($cff_event_title_link == true) echo 'checked="checked"' ?> />
                            <label for="cff_event_title_link">Link to Facebook event</label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Event Details'); ?></th>
                        
                        <td>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label for="cff_event_details_size">Font Size</label>
                            <select name="cff_event_details_size">
                                <option value="inherit" <?php if($cff_event_details_size == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                <option value="10" <?php if($cff_event_details_size == "10") echo 'selected="selected"' ?> >10px</option>
                                <option value="11" <?php if($cff_event_details_size == "11") echo 'selected="selected"' ?> >11px</option>
                                <option value="12" <?php if($cff_event_details_size == "12") echo 'selected="selected"' ?> >12px</option>
                                <option value="14" <?php if($cff_event_details_size == "14") echo 'selected="selected"' ?> >14px</option>
                                <option value="16" <?php if($cff_event_details_size == "16") echo 'selected="selected"' ?> >16px</option>
                                <option value="18" <?php if($cff_event_details_size == "18") echo 'selected="selected"' ?> >18px</option>
                                <option value="20" <?php if($cff_event_details_size == "20") echo 'selected="selected"' ?> >20px</option>
                                <option value="24" <?php if($cff_event_details_size == "24") echo 'selected="selected"' ?> >24px</option>
                                <option value="28" <?php if($cff_event_details_size == "28") echo 'selected="selected"' ?> >28px</option>
                                <option value="32" <?php if($cff_event_details_size == "32") echo 'selected="selected"' ?> >32px</option>
                                <option value="36" <?php if($cff_event_details_size == "36") echo 'selected="selected"' ?> >36px</option>
                                <option value="42" <?php if($cff_event_details_size == "42") echo 'selected="selected"' ?> >42px</option>
                                <option value="48" <?php if($cff_event_details_size == "48") echo 'selected="selected"' ?> >48px</option>
                                <option value="60" <?php if($cff_event_details_size == "54") echo 'selected="selected"' ?> >54px</option>
                                <option value="60" <?php if($cff_event_details_size == "60") echo 'selected="selected"' ?> >60px</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_event_details_weight">Font Weight</label>
                            <select name="cff_event_details_weight">
                                <option value="inherit" <?php if($cff_event_details_weight == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                <option value="normal" <?php if($cff_event_details_weight == "normal") echo 'selected="selected"' ?> >Normal</option>
                                <option value="bold" <?php if($cff_event_details_weight == "bold") echo 'selected="selected"' ?> >Bold</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_event_details_color">Font Color&nbsp;&nbsp;#</label>
                            <input name="cff_event_details_color" type="text" value="<?php esc_attr_e( $cff_event_details_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                            <a href="http://www.colorpicker.com/" target="_blank">Color Picker</a>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Date'); ?></th>
                        
                        <td>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label for="cff_date_size">Font Size</label>
                            <select name="cff_date_size">
                                <option value="inherit" <?php if($cff_date_size == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                <option value="10" <?php if($cff_date_size == "10") echo 'selected="selected"' ?> >10px</option>
                                <option value="11" <?php if($cff_date_size == "11") echo 'selected="selected"' ?> >11px</option>
                                <option value="12" <?php if($cff_date_size == "12") echo 'selected="selected"' ?> >12px</option>
                                <option value="14" <?php if($cff_date_size == "14") echo 'selected="selected"' ?> >14px</option>
                                <option value="16" <?php if($cff_date_size == "16") echo 'selected="selected"' ?> >16px</option>
                                <option value="18" <?php if($cff_date_size == "18") echo 'selected="selected"' ?> >18px</option>
                                <option value="20" <?php if($cff_date_size == "20") echo 'selected="selected"' ?> >20px</option>
                                <option value="24" <?php if($cff_date_size == "24") echo 'selected="selected"' ?> >24px</option>
                                <option value="28" <?php if($cff_date_size == "28") echo 'selected="selected"' ?> >28px</option>
                                <option value="32" <?php if($cff_date_size == "32") echo 'selected="selected"' ?> >32px</option>
                                <option value="36" <?php if($cff_date_size == "36") echo 'selected="selected"' ?> >36px</option>
                                <option value="42" <?php if($cff_date_size == "42") echo 'selected="selected"' ?> >42px</option>
                                <option value="48" <?php if($cff_date_size == "48") echo 'selected="selected"' ?> >48px</option>
                                <option value="60" <?php if($cff_date_size == "54") echo 'selected="selected"' ?> >54px</option>
                                <option value="60" <?php if($cff_date_size == "60") echo 'selected="selected"' ?> >60px</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_date_weight">Font Weight</label>
                            <select name="cff_date_weight">
                                <option value="inherit" <?php if($cff_date_weight == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                <option value="normal" <?php if($cff_date_weight == "normal") echo 'selected="selected"' ?> >Normal</option>
                                <option value="bold" <?php if($cff_date_weight == "bold") echo 'selected="selected"' ?> >Bold</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_date_color">Font Color&nbsp;&nbsp;#</label>
                            <input name="cff_date_color" type="text" value="<?php esc_attr_e( $cff_date_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                            <a href="http://www.colorpicker.com/" target="_blank">Color Picker</a>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Link to Facebook'); ?></th>
                        
                        <!-- <p>What does inherit mean?</p> -->
                        
                        <td>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label for="cff_link_size">Font Size</label>
                            <select name="cff_link_size">
                                <option value="inherit" <?php if($cff_link_size == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                <option value="10" <?php if($cff_link_size == "10") echo 'selected="selected"' ?> >10px</option>
                                <option value="11" <?php if($cff_link_size == "11") echo 'selected="selected"' ?> >11px</option>
                                <option value="12" <?php if($cff_link_size == "12") echo 'selected="selected"' ?> >12px</option>
                                <option value="14" <?php if($cff_link_size == "14") echo 'selected="selected"' ?> >14px</option>
                                <option value="16" <?php if($cff_link_size == "16") echo 'selected="selected"' ?> >16px</option>
                                <option value="18" <?php if($cff_link_size == "18") echo 'selected="selected"' ?> >18px</option>
                                <option value="20" <?php if($cff_link_size == "20") echo 'selected="selected"' ?> >20px</option>
                                <option value="24" <?php if($cff_link_size == "24") echo 'selected="selected"' ?> >24px</option>
                                <option value="28" <?php if($cff_link_size == "28") echo 'selected="selected"' ?> >28px</option>
                                <option value="32" <?php if($cff_link_size == "32") echo 'selected="selected"' ?> >32px</option>
                                <option value="36" <?php if($cff_link_size == "36") echo 'selected="selected"' ?> >36px</option>
                                <option value="42" <?php if($cff_link_size == "42") echo 'selected="selected"' ?> >42px</option>
                                <option value="48" <?php if($cff_link_size == "48") echo 'selected="selected"' ?> >48px</option>
                                <option value="60" <?php if($cff_link_size == "54") echo 'selected="selected"' ?> >54px</option>
                                <option value="60" <?php if($cff_link_size == "60") echo 'selected="selected"' ?> >60px</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_link_weight">Font Weight</label>
                            <select name="cff_link_weight">
                                <option value="inherit" <?php if($cff_link_weight == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                <option value="normal" <?php if($cff_link_weight == "normal") echo 'selected="selected"' ?> >Normal</option>
                                <option value="bold" <?php if($cff_link_weight == "bold") echo 'selected="selected"' ?> >Bold</option>
                            </select>
                            &nbsp;&nbsp;
                            <label for="cff_link_color">Font Color&nbsp;&nbsp;#</label>
                            <input name="cff_link_color" type="text" value="<?php esc_attr_e( $cff_link_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                            <a href="http://www.colorpicker.com/" target="_blank">Color Picker</a>
                        </td>
                    </tr>
                    <tr><i style="color: #666; font-size: 11px; margin-left: 5px;">'Inherit' means that the text will inherit the styles from your theme.</i></tr>
                </tbody>
            </table>
            <br />
            <hr />
            <h3><?php _e('Likes, Shares and Comments'); ?></h3>
            <p style="color: #666; font-size: 11px; font-style: italic; margin-left: 5px;"><a href="http://smashballoon.com/custom-facebook-feed/" target="_blank">Upgrade to Pro</a></p>
            
            <br />
            <hr />

            <h3><?php _e('Custom CSS'); ?></h3>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <td>
                        Enter your own custom CSS in the box below
                        </td>
                    </tr>
                    <tr valign="top">
                        <td>
                            <textarea name="cff_custom_css" id="cff_custom_css" style="width: 70%;" rows="7"><?php esc_attr_e( $cff_custom_css ); ?></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br />
            <hr />

            <h3><?php _e('Misc'); ?></h3>
            <table class="form-table">
                <tbody>
                    <tr><td><b style="font-size: 14px;"><?php _e('Text Character Limits'); ?></b></td></tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Maximum Post Text Length'); ?></th>
                        <td>
                            <input name="cff_title_length" type="text" value="<?php esc_attr_e( $cff_title_length_val ); ?>" size="4" /> <span>Characters.</span> <span>Eg. 200</span> <i style="color: #666; font-size: 11px; margin-left: 5px;">(Leave empty to set no maximum length)</i>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Maximum Video/Link/Event Description Length'); ?></th>
                        <td>
                            <input name="cff_body_length" type="text" value="<?php esc_attr_e( $cff_body_length_val ); ?>" size="4" /> <span>Characters.</span> <i style="color: #666; font-size: 11px; margin-left: 5px;">(Leave empty to set no maximum length)</i>
                        </td>
                    </tr>
                    <tr><td><b style="font-size: 14px;"><?php _e('Like Box'); ?></b></td></tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Show the Like Box'); ?></th>
                        <td>
                            <input type="checkbox" name="cff_show_like_box" id="cff_show_like_box" <?php if($cff_show_like_box == true) echo 'checked="checked"' ?> />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Like Box Position'); ?></th>
                        <td>
                            <select name="cff_like_box_position">
                                <option value="bottom" <?php if($cff_like_box_position == "bottom") echo 'selected="selected"' ?> >Bottom</option>
                                <option value="top" <?php if($cff_like_box_position == "top") echo 'selected="selected"' ?> >Top</option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Like Box Background Color'); ?></th>
                        <td>
                            <label for="cff_likebox_bg_color">#</label>
                            <input name="cff_likebox_bg_color" type="text" value="<?php esc_attr_e( $cff_likebox_bg_color ); ?>" size="10" />
                            <span>Eg. ED9A00</span>&nbsp;&nbsp;<a href="http://www.colorpicker.com/" target="_blank">Color Picker</a>
                        </td>
                    </tr>

                    <tr><td><b style="font-size: 14px;"><?php _e('Separating Line'); ?></b></td></tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Separating Line Color'); ?></th>
                        <td>
                            <label for="cff_sep_color">#</label>
                            <input name="cff_sep_color" type="text" value="<?php esc_attr_e( $cff_sep_color ); ?>" size="10" />
                            <span>Eg. ED9A00</span>&nbsp;&nbsp;<a href="http://www.colorpicker.com/" target="_blank">Color Picker</a>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Separating Line Thickness'); ?></th>
                        <td>
                            <input name="cff_sep_size" type="text" value="<?php esc_attr_e( $cff_sep_size ); ?>" size="1" /><span>px</span> <i style="color: #666; font-size: 11px; margin-left: 5px;">(Leave empty to hide)</i>
                        </td>
                    </tr>

                </tbody>
            </table>
            <?php submit_button(); ?>
        </form>
        <hr />
        <br />
        <p>Please note that the free version of the plugin only displays text updates. For <b>photos, videos, comments and more</b> please upgrade to the <a href="http://smashballoon.com/custom-facebook-feed/" target="_blank">Pro version</a> of the plugin.</p>
        <br /><br />
        <a href="http://smashballoon.com/custom-facebook-feed/demo" target="_blank"><img src="<?php echo plugins_url( 'img/pro.png' , __FILE__ ) ?>" /></a>
<?php 
} //End Style_Page 
?>