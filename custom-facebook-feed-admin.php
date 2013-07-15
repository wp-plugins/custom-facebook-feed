<?php 
function cff_menu() {
    add_menu_page('Settings', 'Custom Facebook Feed', 'manage_options', 'cff-top', 'cff_settings_page');
}
add_action('admin_menu', 'cff_menu');

//Create Settings page
function cff_settings_page() {

    //Declare variables for fields
    $hidden_field_name  = 'cff_submit_hidden';
    $access_token       = 'cff_access_token';
    $page_id            = 'cff_page_id';
    $num_show           = 'cff_num_show';
    $cff_title_length   = 'cff_title_length';
    $cff_body_length    = 'cff_body_length';

    // Read in existing option value from database
    $access_token_val = get_option( $access_token );
    $page_id_val = get_option( $page_id );
    $num_show_val = get_option( $num_show );
    $cff_title_length_val = get_option( $cff_title_length );
    $cff_body_length_val = get_option( $cff_body_length );

    // See if the user has posted us some information. If they did, this hidden field will be set to 'Y'.
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $access_token_val = $_POST[ $access_token ];
        $page_id_val = $_POST[ $page_id ];
        $num_show_val = $_POST[ $num_show ];
        $cff_title_length_val = $_POST[ $cff_title_length ];
        $cff_body_length_val = $_POST[ $cff_body_length ];

        // Save the posted value in the database
        update_option( $access_token, $access_token_val );
        update_option( $page_id, $page_id_val );
        update_option( $num_show, $num_show_val );
        update_option( $cff_title_length, $cff_title_length_val );
        update_option( $cff_body_length, $cff_body_length_val );

        // Put an settings updated message on the screen 
    ?>
    <div class="updated"><p><strong><?php _e('Settings saved.', 'custom-facebook-feed' ); ?></strong></p></div>

    <?php } ?> 
 
    <div class="wrap">

        <h2><?php _e('Custom Facebook Feed'); ?></h2>

        <form name="form1" method="post" action="">

            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">


            <h3><?php _e('Feed Settings'); ?></h3>


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

            <br />
            <h3><?php _e('Post Formatting'); ?></h3>


            <table class="form-table">

                <tbody>

                    <tr valign="top">

                        <th scope="row"><?php _e('Maximum Post Text Length'); ?></th>

                        <td>

                            <input name="cff_title_length" type="text" value="<?php esc_attr_e( $cff_title_length_val ); ?>" size="4" /> <span>Characters.</span> <i style="color: #666; font-size: 11px; margin-left: 5px;">(Leave empty to set no maximum length)</i>

                        </td>

                    </tr>

                    <tr valign="top">

                        <th scope="row"><?php _e('Maximum Link/Event Description Length'); ?></th>

                        <td>

                            <input name="cff_body_length" type="text" value="<?php esc_attr_e( $cff_body_length_val ); ?>" size="4" /> <span>Characters.</span> <i style="color: #666; font-size: 11px; margin-left: 5px;">(Leave empty to set no maximum length)</i>

                        </td>

                    </tr>

                </tbody>

            </table>

            <p style="margin: 25px 0 40px 0;">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
                &nbsp;&nbsp;<a href="http://smashballoon.com/custom-facebook-feed/troubleshooting" target="_blank"><b>HELP!</b> My feed is not showing up</a>
            </p>

        </form>

        <hr />

        <h4>Displaying your Feed</h4>

        <p>Copy and paste this shortcode directly into the page, post or widget where you'd like the feed to show up:</p>

        <input type="text" value="[custom-facebook-feed]" size="23" />

        <p>You can override the settings above directly in the shortcode like so:</p>

        <p>[custom-facebook-feed <b>id=Your_Page_ID show=3 titlelength=100 bodylength=150</b>]</p>
        <br />
        <p>Please note that the free version of the plugin only displays text updates. For <b>photos, videos, comments and more</b> please upgrade to the <a href="http://smashballoon.com/custom-facebook-feed/wordpress-plugin/" target="_blank">Pro version</a> of the plugin.</p>

        <br /><br /><a href="http://smashballoon.com/custom-facebook-feed/" target="_blank">Plugin Support</a> - Smash Balloon is committed to making this plugin better. Please let us know if you have had any issues when using this plugin so that we can continue to make it better!

        <br /><br /><br />
        <a href="http://smashballoon.com/custom-facebook-feed/demo" target="_blank"><img src="<?php echo plugins_url( 'img/pro.jpg' , __FILE__ ) ?>" /></a>

<?php 
} //End Settings_Page 
?>