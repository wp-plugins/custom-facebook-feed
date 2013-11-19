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
//Create Settings page
function cff_settings_page() {
    //Declare variables for fields
    $hidden_field_name      = 'cff_submit_hidden';
    $access_token           = 'cff_access_token';
    $page_id                = 'cff_page_id';
    $num_show               = 'cff_num_show';
    $cff_post_limit         = 'cff_post_limit';
    $cff_show_others        = 'cff_show_others';
    $cff_cache_time         = 'cff_cache_time';
    $cff_cache_time_unit    = 'cff_cache_time_unit';
    $cff_locale             = 'cff_locale';
    // Read in existing option value from database
    $access_token_val = get_option( $access_token );
    $page_id_val = get_option( $page_id );
    $num_show_val = get_option( $num_show, '5' );
    $cff_post_limit_val = get_option( $cff_post_limit );
    $cff_show_others_val = get_option( $cff_show_others );
    $cff_cache_time_val = get_option( $cff_cache_time, '1' );
    $cff_cache_time_unit_val = get_option( $cff_cache_time_unit, 'hours' );
    $cff_locale_val = get_option( $cff_locale, 'en_US' );
    // See if the user has posted us some information. If they did, this hidden field will be set to 'Y'.
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $access_token_val = $_POST[ $access_token ];
        $page_id_val = $_POST[ $page_id ];
        $num_show_val = $_POST[ $num_show ];
        $cff_post_limit_val = $_POST[ $cff_post_limit ];
        $cff_show_others_val = $_POST[ $cff_show_others ];
        $cff_cache_time_val = $_POST[ $cff_cache_time ];
        $cff_cache_time_unit_val = $_POST[ $cff_cache_time_unit ];
        $cff_locale_val = $_POST[ $cff_locale ];
        // Save the posted value in the database
        update_option( $access_token, $access_token_val );
        update_option( $page_id, $page_id_val );
        update_option( $num_show, $num_show_val );
        update_option( $cff_post_limit, $cff_post_limit_val );
        update_option( $cff_show_others, $cff_show_others_val );
        update_option( $cff_cache_time, $cff_cache_time_val );
        update_option( $cff_cache_time_unit, $cff_cache_time_unit_val );
        update_option( $cff_locale, $cff_locale_val );
        
        //Delete the transient for the main page ID
        delete_transient( 'cff_posts_json_' .$page_id_val );
        delete_transient( 'cff_feed_json_' .$page_id_val );
        delete_transient( 'cff_events_json_' . $page_id_val );
        //Delete ALL transients
        global $wpdb;
        $table_name = $wpdb->prefix . "options";
        $wpdb->query( "
            DELETE
            FROM $table_name
            WHERE `option_name` LIKE ('%cff\_posts\_json\_%')
            " );
        $wpdb->query( "
            DELETE
            FROM $table_name
            WHERE `option_name` LIKE ('%cff\_feed\_json\_%')
            " );
        $wpdb->query( "
            DELETE
            FROM $table_name
            WHERE `option_name` LIKE ('%cff\_events\_json\_%')
            " );
        // Put an settings updated message on the screen 
    ?>
    <div class="updated"><p><strong><?php _e('Settings saved.', 'custom-facebook-feed' ); ?></strong></p></div>
    <?php } ?> 
 
    <div id="cff-admin" class="wrap">
        <div id="header">
            <h1><?php _e('Custom Facebook Feed Settings'); ?></h1>
        </div>
        <form name="form1" method="post" action="">
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
            <br />
            <h3><?php _e('Configuration'); ?></h3>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e('Access Token'); ?></th>
                        <td>
                            <input name="cff_access_token" type="text" value="<?php esc_attr_e( $access_token_val ); ?>" size="60" />
                            <!--<a href="#" id="verify-token" class="button-secondary"><?php _e('Verify Access Token'); ?></a>-->
                            &nbsp;<a class="tooltip-link" href="JavaScript:void(0);"><?php _e('How to get an Access Token'); ?></a>
                            <br /><i style="color: #666; font-size: 11px;">Eg. 1234567890123|ABC2fvp5h9tJe4-5-AbC123</i>
                            <p class="tooltip"><?php _e("In order to use the plugin, Facebook requires you to obtain an access token to access their data.  Don't worry though, this is really easy to do.  Just follow these <a href='http://smashballoon.com/custom-facebook-feed/access-token/' target='_blank'>step-by-step instructions</a>"); ?>.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Facebook Page ID'); ?></th>
                        <td>
                            <input name="cff_page_id" type="text" value="<?php esc_attr_e( $page_id_val ); ?>" size="60" />
                            &nbsp;<a class="tooltip-link" href="JavaScript:void(0);"><?php _e('What\'s my Page ID?'); ?></a>
                            <br /><i style="color: #666; font-size: 11px;">Eg. 1234567890123 or smashballoon</i>
                            <div class="tooltip">
                                <ul>
                                    <li><?php _e('If you have a Facebook <b>page</b> with a URL like this: <code>https://www.facebook.com/your_page_name</code> then the Page ID is just <b>your_page_name</b>. If your page URL is structured like this: <code>https://www.facebook.com/pages/your_page_name/123654123654123</code> then the Page ID is actually the number at the end, so in this case <b>123654123654123</b>.</li>'); ?>
                                    <li><?php _e('If you have a Facebook <b>group</b> then use <a href="http://lookup-id.com/" target="_blank" title="Find my ID">this tool</a> to find your ID.'); ?></li>
                                    <li><?php _e('You can copy and paste your ID into the <a href="http://smashballoon.com/custom-facebook-feed/demo/" target="_blank">demo</a> to test it.'); ?></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Number of posts to display'); ?></th>
                        <td>
                            <input name="cff_num_show" type="text" value="<?php esc_attr_e( $num_show_val ); ?>" size="4" />
                            <i style="color: #666; font-size: 11px;">Eg. 5</i>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Alter the post limit'); ?></th>
                        <td>
                            <input name="cff_post_limit" type="text" value="<?php esc_attr_e( $cff_post_limit_val ); ?>" size="4" />
                            <i style="color: #666; font-size: 11px;">Eg. 50</i> <a class="tooltip-link bump-left" href="JavaScript:void(0);"><?php _e('What does this mean?'); ?></a>
                            <p class="tooltip"><?php _e('By default the Facebook API only returns your latest 25 posts. If you would like to retrieve more than 25 posts then you can increase the limit by specifying a higher value here. However, the more posts you request the slower the page load time may be when the plugin needs to check Facebook for new posts. Similarly, if you only intend to retrieve a few posts then you may wish to set a lower post limit here so that you aren\'t retrieving more posts than necessary. It\'s best to set this higher than the actual number of posts you want to display as some posts may be filtered out.'); ?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Show posts by others on my page'); ?><br /><i style="color: #666; font-size: 11px;"><?php _e('(Check this if using a <b>group</b>)'); ?></i></th>
                        <td>
                            <input name="cff_show_others" type="checkbox" id="cff_show_others" <?php if($cff_show_others_val == true) echo "checked"; ?> />
                            <i style="color: #666; font-size: 11px;"><?php _e('By default only posts by the page owner will be shown. Check this box to also show posts by others.'); ?></i>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Check for new Facebook posts every'); ?></th>
                        <td>
                            <input name="cff_cache_time" type="text" value="<?php esc_attr_e( $cff_cache_time_val ); ?>" size="4" />
                            <select name="cff_cache_time_unit">
                                <option value="minutes" <?php if($cff_cache_time_unit_val == "minutes") echo 'selected="selected"' ?> ><?php _e('Minutes'); ?></option>
                                <option value="hours" <?php if($cff_cache_time_unit_val == "hours") echo 'selected="selected"' ?> ><?php _e('Hours'); ?></option>
                                <option value="days" <?php if($cff_cache_time_unit_val == "days") echo 'selected="selected"' ?> ><?php _e('Days'); ?></option>
                            </select>
                            <a class="tooltip-link bump-left" href="JavaScript:void(0);"><?php _e('What does this mean?'); ?></a>
                            <p class="tooltip"><?php _e('Your Facebook posts and comments data is temporarily cached by the plugin in your WordPress database. You can choose how long this data should be cached for. If you set the time to 60 minutes then the plugin will clear the cached data after that length of time, and the next time the page is viewed it will check for new data.'); ?></p>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><?php _e('Localization'); ?></th>
                        <td>
                            <select name="cff_locale">
                                <option value="af_ZA" <?php if($cff_locale_val == "af_ZA") echo 'selected="selected"' ?> ><?php _e('Afrikaans'); ?></option>
                                <option value="ar_AR" <?php if($cff_locale_val == "ar_AR") echo 'selected="selected"' ?> ><?php _e('Arabic'); ?></option>
                                <option value="az_AZ" <?php if($cff_locale_val == "az_AZ") echo 'selected="selected"' ?> ><?php _e('Azerbaijani'); ?></option>
                                <option value="be_BY" <?php if($cff_locale_val == "be_BY") echo 'selected="selected"' ?> ><?php _e('Belarusian'); ?></option>
                                <option value="bg_BG" <?php if($cff_locale_val == "bg_BG") echo 'selected="selected"' ?> ><?php _e('Bulgarian'); ?></option>
                                <option value="bn_IN" <?php if($cff_locale_val == "bn_IN") echo 'selected="selected"' ?> ><?php _e('Bengali'); ?></option>
                                <option value="bs_BA" <?php if($cff_locale_val == "bs_BA") echo 'selected="selected"' ?> ><?php _e('Bosnian'); ?></option>
                                <option value="ca_ES" <?php if($cff_locale_val == "ca_ES") echo 'selected="selected"' ?> ><?php _e('Catalan'); ?></option>
                                <option value="cs_CZ" <?php if($cff_locale_val == "cs_CZ") echo 'selected="selected"' ?> ><?php _e('Czech'); ?></option>
                                <option value="cy_GB" <?php if($cff_locale_val == "cy_GB") echo 'selected="selected"' ?> ><?php _e('Welsh'); ?></option>
                                <option value="da_DK" <?php if($cff_locale_val == "da_DK") echo 'selected="selected"' ?> ><?php _e('Danish'); ?></option>
                                <option value="de_DE" <?php if($cff_locale_val == "de_DE") echo 'selected="selected"' ?> ><?php _e('German'); ?></option>
                                <option value="el_GR" <?php if($cff_locale_val == "el_GR") echo 'selected="selected"' ?> ><?php _e('Greek'); ?></option>
                                <option value="en_GB" <?php if($cff_locale_val == "en_GB") echo 'selected="selected"' ?> ><?php _e('English (UK)'); ?></option>
                                <option value="en_PI" <?php if($cff_locale_val == "en_PI") echo 'selected="selected"' ?> ><?php _e('English (Pirate)'); ?></option>
                                <option value="en_UD" <?php if($cff_locale_val == "en_UD") echo 'selected="selected"' ?> ><?php _e('English (Upside Down)'); ?></option>
                                <option value="en_US" <?php if($cff_locale_val == "en_US") echo 'selected="selected"' ?> ><?php _e('English (US)'); ?></option>
                                <option value="eo_EO" <?php if($cff_locale_val == "eo_EO") echo 'selected="selected"' ?> ><?php _e('Esperanto'); ?></option>
                                <option value="es_ES" <?php if($cff_locale_val == "es_ES") echo 'selected="selected"' ?> ><?php _e('Spanish (Spain)'); ?></option>
                                <option value="es_LA" <?php if($cff_locale_val == "es_LA") echo 'selected="selected"' ?> ><?php _e('Spanish'); ?></option>
                                <option value="et_EE" <?php if($cff_locale_val == "et_EE") echo 'selected="selected"' ?> ><?php _e('Estonian'); ?></option>
                                <option value="eu_ES" <?php if($cff_locale_val == "eu_ES") echo 'selected="selected"' ?> ><?php _e('Basque'); ?></option>
                                <option value="fa_IR" <?php if($cff_locale_val == "fa_IR") echo 'selected="selected"' ?> ><?php _e('Persian'); ?></option>
                                <option value="fb_LT" <?php if($cff_locale_val == "fb_LT") echo 'selected="selected"' ?> ><?php _e('Leet Speak'); ?></option>
                                <option value="fi_FI" <?php if($cff_locale_val == "fi_FI") echo 'selected="selected"' ?> ><?php _e('Finnish'); ?></option>
                                <option value="fo_FO" <?php if($cff_locale_val == "fo_FO") echo 'selected="selected"' ?> ><?php _e('Faroese'); ?></option>
                                <option value="fr_CA" <?php if($cff_locale_val == "fr_CA") echo 'selected="selected"' ?> ><?php _e('French (Canada)'); ?></option>
                                <option value="fr_FR" <?php if($cff_locale_val == "fr_FR") echo 'selected="selected"' ?> ><?php _e('French (France)'); ?></option>
                                <option value="fy_NL" <?php if($cff_locale_val == "fy_NL") echo 'selected="selected"' ?> ><?php _e('Frisian'); ?></option>
                                <option value="ga_IE" <?php if($cff_locale_val == "ga_IE") echo 'selected="selected"' ?> ><?php _e('Irish'); ?></option>
                                <option value="gl_ES" <?php if($cff_locale_val == "gl_ES") echo 'selected="selected"' ?> ><?php _e('Galician'); ?></option>
                                <option value="he_IL" <?php if($cff_locale_val == "he_IL") echo 'selected="selected"' ?> ><?php _e('Hebrew'); ?></option>
                                <option value="hi_IN" <?php if($cff_locale_val == "hi_IN") echo 'selected="selected"' ?> ><?php _e('Hindi'); ?></option>
                                <option value="hr_HR" <?php if($cff_locale_val == "hr_HR") echo 'selected="selected"' ?> ><?php _e('Croatian'); ?></option>
                                <option value="hu_HU" <?php if($cff_locale_val == "hu_HU") echo 'selected="selected"' ?> ><?php _e('Hungarian'); ?></option>
                                <option value="hy_AM" <?php if($cff_locale_val == "hy_AM") echo 'selected="selected"' ?> ><?php _e('Armenian'); ?></option>
                                <option value="id_ID" <?php if($cff_locale_val == "id_ID") echo 'selected="selected"' ?> ><?php _e('Indonesian'); ?></option>
                                <option value="is_IS" <?php if($cff_locale_val == "is_IS") echo 'selected="selected"' ?> ><?php _e('Icelandic'); ?></option>
                                <option value="it_IT" <?php if($cff_locale_val == "it_IT") echo 'selected="selected"' ?> ><?php _e('Italian'); ?></option>
                                <option value="ja_JP" <?php if($cff_locale_val == "ja_JP") echo 'selected="selected"' ?> ><?php _e('Japanese'); ?></option>
                                <option value="ka_GE" <?php if($cff_locale_val == "ka_GE") echo 'selected="selected"' ?> ><?php _e('Georgian'); ?></option>
                                <option value="km_KH" <?php if($cff_locale_val == "km_KH") echo 'selected="selected"' ?> ><?php _e('Khmer'); ?></option>
                                <option value="ko_KR" <?php if($cff_locale_val == "ko_KR") echo 'selected="selected"' ?> ><?php _e('Korean'); ?></option>
                                <option value="ku_TR" <?php if($cff_locale_val == "ku_TR") echo 'selected="selected"' ?> ><?php _e('Kurdish'); ?></option>
                                <option value="la_VA" <?php if($cff_locale_val == "la_VA") echo 'selected="selected"' ?> ><?php _e('Latin'); ?></option>
                                <option value="lt_LT" <?php if($cff_locale_val == "lt_LT") echo 'selected="selected"' ?> ><?php _e('Lithuanian'); ?></option>
                                <option value="lv_LV" <?php if($cff_locale_val == "lv_LV") echo 'selected="selected"' ?> ><?php _e('Latvian'); ?></option>
                                <option value="mk_MK" <?php if($cff_locale_val == "mk_MK") echo 'selected="selected"' ?> ><?php _e('Macedonian'); ?></option>
                                <option value="ml_IN" <?php if($cff_locale_val == "ml_IN") echo 'selected="selected"' ?> ><?php _e('Malayalam'); ?></option>
                                <option value="ms_MY" <?php if($cff_locale_val == "ms_MY") echo 'selected="selected"' ?> ><?php _e('Malay'); ?></option>
                                <option value="nb_NO" <?php if($cff_locale_val == "nb_NO") echo 'selected="selected"' ?> ><?php _e('Norwegian (bokmal)'); ?></option>
                                <option value="ne_NP" <?php if($cff_locale_val == "ne_NP") echo 'selected="selected"' ?> ><?php _e('Nepali'); ?></option>
                                <option value="nl_NL" <?php if($cff_locale_val == "nl_NL") echo 'selected="selected"' ?> ><?php _e('Dutch'); ?></option>
                                <option value="nn_NO" <?php if($cff_locale_val == "nn_NO") echo 'selected="selected"' ?> ><?php _e('Norwegian (nynorsk)'); ?></option>
                                <option value="pa_IN" <?php if($cff_locale_val == "pa_IN") echo 'selected="selected"' ?> ><?php _e('Punjabi'); ?></option>
                                <option value="pl_PL" <?php if($cff_locale_val == "pl_PL") echo 'selected="selected"' ?> ><?php _e('Polish'); ?></option>
                                <option value="ps_AF" <?php if($cff_locale_val == "ps_AF") echo 'selected="selected"' ?> ><?php _e('Pashto'); ?></option>
                                <option value="pt_BR" <?php if($cff_locale_val == "pt_BR") echo 'selected="selected"' ?> ><?php _e('Portuguese (Brazil)'); ?></option>
                                <option value="pt_PT" <?php if($cff_locale_val == "pt_PT") echo 'selected="selected"' ?> ><?php _e('Portuguese (Portugal)'); ?></option>
                                <option value="ro_RO" <?php if($cff_locale_val == "ro_RO") echo 'selected="selected"' ?> ><?php _e('Romanian'); ?></option>
                                <option value="ru_RU" <?php if($cff_locale_val == "ru_RU") echo 'selected="selected"' ?> ><?php _e('Russian'); ?></option>
                                <option value="sk_SK" <?php if($cff_locale_val == "sk_SK") echo 'selected="selected"' ?> ><?php _e('Slovak'); ?></option>
                                <option value="sl_SI" <?php if($cff_locale_val == "sl_SI") echo 'selected="selected"' ?> ><?php _e('Slovenian'); ?></option>
                                <option value="sq_AL" <?php if($cff_locale_val == "sq_AL") echo 'selected="selected"' ?> ><?php _e('Albanian'); ?></option>
                                <option value="sr_RS" <?php if($cff_locale_val == "sr_RS") echo 'selected="selected"' ?> ><?php _e('Serbian'); ?></option>
                                <option value="sv_SE" <?php if($cff_locale_val == "sv_SE") echo 'selected="selected"' ?> ><?php _e('Swedish'); ?></option>
                                <option value="sw_KE" <?php if($cff_locale_val == "sw_KE") echo 'selected="selected"' ?> ><?php _e('Swahili'); ?></option>
                                <option value="ta_IN" <?php if($cff_locale_val == "ta_IN") echo 'selected="selected"' ?> ><?php _e('Tamil'); ?></option>
                                <option value="te_IN" <?php if($cff_locale_val == "te_IN") echo 'selected="selected"' ?> ><?php _e('Telugu'); ?></option>
                                <option value="th_TH" <?php if($cff_locale_val == "th_TH") echo 'selected="selected"' ?> ><?php _e('Thai'); ?></option>
                                <option value="tl_PH" <?php if($cff_locale_val == "tl_PH") echo 'selected="selected"' ?> ><?php _e('Filipino'); ?></option>
                                <option value="tr_TR" <?php if($cff_locale_val == "tr_TR") echo 'selected="selected"' ?> ><?php _e('Turkish'); ?></option>
                                <option value="uk_UA" <?php if($cff_locale_val == "uk_UA") echo 'selected="selected"' ?> ><?php _e('Ukrainian'); ?></option>
                                <option value="vi_VN" <?php if($cff_locale_val == "vi_VN") echo 'selected="selected"' ?> ><?php _e('Vietnamese'); ?></option>
                                <option value="zh_CN" <?php if($cff_locale_val == "zh_CN") echo 'selected="selected"' ?> ><?php _e('Simplified Chinese (China)'); ?></option>
                                <option value="zh_HK" <?php if($cff_locale_val == "zh_HK") echo 'selected="selected"' ?> ><?php _e('Traditional Chinese (Hong Kong)'); ?></option>
                                <option value="zh_TW" <?php if($cff_locale_val == "zh_TW") echo 'selected="selected"' ?> ><?php _e('Traditional Chinese (Taiwan)'); ?></option>
                            </select>
                            <i style="color: #666; font-size: 11px;"><?php _e('Select a language'); ?></i>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
            <?php submit_button(); ?>
        </form>
        <h3><?php _e('Support'); ?></h3>
        <p>Having trouble getting the plugin to work? Try visiting the <a href="http://smashballoon.com/custom-facebook-feed/faq/" target="_blank" />Troubleshooting &amp; FAQ</a> page or contact <a href="http://smashballoon.com/custom-facebook-feed/support" target="_blank">support</a>.<br />Smash Balloon is committed to making this plugin better. Please let us know if you have had any issues when using this plugin so that we can continue to improve it!</p>
        <hr />
        <h3><?php _e('Displaying your Feed'); ?></h3>
        <p><?php _e('Copy and paste this shortcode directly into the page, post or widget where you\'d like the feed to show up:'); ?></p>
        <input type="text" value="[custom-facebook-feed]" size="22" readonly="readonly" onclick="this.focus();this.select()" id="system-info-textarea" name="edd-sysinfo" title="<?php _e('To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac).'); ?>" />
        <p><?php _e('If you wish, you can override the settings above directly in the shortcode like so:'); ?></p>
        <p>[custom-facebook-feed <b><span style='color: purple;'>id=Put_Your_Facebook_Page_ID_Here</span> <span style='color: green;'>num=3</span> <span style='color: blue;'>layout=thumb</span></b>]</p>
        <p><a href="http://smashballoon.com/custom-facebook-feed/docs/shortcodes/" target="_blank"><?php _e('Click here'); ?></a> <?php _e('for a full list of shortcode options'); ?></p>
        <hr />
        
        <a href="http://smashballoon.com/custom-facebook-feed/demo" target="_blank"><img src="<?php echo plugins_url( 'img/pro.png' , __FILE__ ) ?>" /></a>
        <hr />
        <h4><?php _e('<u>System Info:</u>'); ?></h4>
        <p>PHP Version:          <b><?php echo PHP_VERSION . "\n"; ?></b></p>
        <p>Web Server Info:      <b><?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?></b></p>
        <p>PHP allow_url_fopen:  <b><?php echo ini_get( 'allow_url_fopen' ) ? "<span style='color: green;'>Yes</span>" : "<span style='color: red;'>No</span>"; ?></b></p>
        <p>PHP cURL:             <b><?php echo is_callable('curl_init') ? "<span style='color: green;'>Yes</span>" : "<span style='color: red;'>No</span>" ?></b></p>
        <p>JSON:                 <b><?php echo function_exists("json_decode") ? "<span style='color: green;'>Yes</span>" : "<span style='color: red;'>No</span>" ?></b></p>
        <i style="color: #666; font-size: 11px;"><?php _e('(If any of the items above are listed as'); ?> <span style='color: red;'>No</span> <?php _e('then please include this in your support request)'); ?></i>
        
        
<?php 
} //End Settings_Page 
//Create Style page
function cff_style_page() {
    //Declare variables for fields
    $style_hidden_field_name                = 'cff_style_submit_hidden';
    $style_general_hidden_field_name        = 'cff_style_general_submit_hidden';
    $style_post_layout_hidden_field_name    = 'cff_style_post_layout_submit_hidden';
    $style_typography_hidden_field_name     = 'cff_style_typography_submit_hidden';
    $style_misc_hidden_field_name           = 'cff_style_misc_submit_hidden';
    $defaults = array(
        //Post types
        'cff_show_links_type'       => true,
        'cff_show_event_type'       => true,
        'cff_show_video_type'       => true,
        'cff_show_photos_type'      => true,
        'cff_show_status_type'      => true,
        //Layout
        'cff_preset_layout'         => 'thumb',
        //Include
        'cff_show_text'             => true,
        'cff_show_desc'             => true,
        'cff_show_shared_links'     => true,
        'cff_show_date'             => true,
        'cff_show_media'            => true,
        'cff_show_event_title'      => true,
        'cff_show_event_details'    => true,
        'cff_show_meta'             => true,
        'cff_show_link'             => true,
        'cff_show_like_box'         => true,
        //Typography
        'cff_see_more_text'         => 'See More',
        'cff_see_less_text'         => 'See Less',
        'cff_title_format'          => 'p',
        'cff_title_size'            => 'inherit',
        'cff_title_weight'          => 'inherit',
        'cff_title_color'           => '',
        'cff_body_size'             => 'inherit',
        'cff_body_weight'           => 'inherit',
        'cff_body_color'            => '',
        //Event title
        'cff_event_title_format'    => 'p',
        'cff_event_title_size'      => 'inherit',
        'cff_event_title_weight'    => 'inherit',
        'cff_event_title_color'     => '',
        //Event date
        'cff_event_date_size'       => 'inherit',
        'cff_event_date_weight'     => 'inherit',
        'cff_event_date_color'      => '',
        'cff_event_date_position'   => 'below',
        'cff_event_date_formatting' => '1',
        'cff_event_date_custom'     => '',
        //Event details
        'cff_event_details_size'    => 'inherit',
        'cff_event_details_weight'  => 'inherit',
        'cff_event_details_color'   => '',
        //Date
        'cff_date_position'         => 'below',
        'cff_date_size'             => 'inherit',
        'cff_date_weight'           => 'inherit',
        'cff_date_color'            => '',
        'cff_date_formatting'       => '1',
        'cff_date_custom'           => '',
        'cff_date_before'           => '',
        'cff_date_after'            => '',
        //Link to Facebook
        'cff_link_size'             => 'inherit',
        'cff_link_weight'           => 'inherit',
        'cff_link_color'            => '',
        'cff_facebook_link_text'    => 'View on Facebook',
        'cff_view_link_text'        => 'View Link',
        'cff_link_to_timeline'          => false,
        //Meta
        'cff_icon_style'            => 'light',
        'cff_meta_text_color'       => '',
        'cff_meta_bg_color'         => '',
        'cff_nocomments_text'       => 'No comments yet',
        'cff_hide_comments'         => '',
        //Misc
        'cff_feed_width'            => '',
        'cff_feed_height'           => '',
        'cff_feed_padding'          => '',
        'cff_like_box_position'     => 'bottom',
        'cff_like_box_outside'      => false,
        'cff_likebox_width'         => '300',
        'cff_like_box_faces'        => false,

        'cff_bg_color'              => '',
        'cff_likebox_bg_color'      => '',
        'cff_video_height'          => '',
        'cff_show_author'           => false,
        'cff_class'                 => '',
        //New
        'cff_custom_css'            => '',
        'cff_title_link'            => false,
        'cff_event_title_link'      => false,
        'cff_video_action'          => 'file',
        'cff_sep_color'             => '',
        'cff_sep_size'              => '1'
    );
    //Save layout option in an array
    $options = wp_parse_args(get_option('cff_style_settings'), $defaults);
    add_option( 'cff_style_settings', $options );
    //Set the page variables
    //Post types
    $cff_show_links_type = $options[ 'cff_show_links_type' ];
    $cff_show_event_type = $options[ 'cff_show_event_type' ];
    $cff_show_video_type = $options[ 'cff_show_video_type' ];
    $cff_show_photos_type = $options[ 'cff_show_photos_type' ];
    $cff_show_status_type = $options[ 'cff_show_status_type' ];
    //Layout
    $cff_preset_layout = $options[ 'cff_preset_layout' ];
    //Include
    $cff_show_text = $options[ 'cff_show_text' ];
    $cff_show_desc = $options[ 'cff_show_desc' ];
    $cff_show_shared_links = $options[ 'cff_show_shared_links' ];
    $cff_show_date = $options[ 'cff_show_date' ];
    $cff_show_media = $options[ 'cff_show_media' ];
    $cff_show_event_title = $options[ 'cff_show_event_title' ];
    $cff_show_event_details = $options[ 'cff_show_event_details' ];
    $cff_show_meta = $options[ 'cff_show_meta' ];
    $cff_show_link = $options[ 'cff_show_link' ];
    $cff_show_like_box = $options[ 'cff_show_like_box' ];
    //Typography
    $cff_see_more_text = $options[ 'cff_see_more_text' ];
    $cff_see_less_text = $options[ 'cff_see_less_text' ];
    $cff_title_format = $options[ 'cff_title_format' ];
    $cff_title_size = $options[ 'cff_title_size' ];
    $cff_title_weight = $options[ 'cff_title_weight' ];
    $cff_title_color = $options[ 'cff_title_color' ];
    $cff_body_size = $options[ 'cff_body_size' ];
    $cff_body_weight = $options[ 'cff_body_weight' ];
    $cff_body_color = $options[ 'cff_body_color' ];
    //Event title
    $cff_event_title_format = $options[ 'cff_event_title_format' ];
    $cff_event_title_size = $options[ 'cff_event_title_size' ];
    $cff_event_title_weight = $options[ 'cff_event_title_weight' ];
    $cff_event_title_color = $options[ 'cff_event_title_color' ];
    //Event date
    $cff_event_date_size = $options[ 'cff_event_date_size' ];
    $cff_event_date_weight = $options[ 'cff_event_date_weight' ];
    $cff_event_date_color = $options[ 'cff_event_date_color' ];
    $cff_event_date_position = $options[ 'cff_event_date_position' ];
    $cff_event_date_formatting = $options[ 'cff_event_date_formatting' ];
    $cff_event_date_custom = $options[ 'cff_event_date_custom' ];
    //Event details
    $cff_event_details_size = $options[ 'cff_event_details_size' ];
    $cff_event_details_weight = $options[ 'cff_event_details_weight' ];
    $cff_event_details_color = $options[ 'cff_event_details_color' ];
    //Date
    $cff_date_position = $options[ 'cff_date_position' ];
    $cff_date_size = $options[ 'cff_date_size' ];
    $cff_date_weight = $options[ 'cff_date_weight' ];
    $cff_date_color = $options[ 'cff_date_color' ];
    $cff_date_formatting = $options[ 'cff_date_formatting' ];
    $cff_date_custom = $options[ 'cff_date_custom' ];
    $cff_date_before = $options[ 'cff_date_before' ];
    $cff_date_after = $options[ 'cff_date_after' ];
    //View on Facebook link
    $cff_link_size = $options[ 'cff_link_size' ];
    $cff_link_weight = $options[ 'cff_link_weight' ];
    $cff_link_color = $options[ 'cff_link_color' ];
    $cff_facebook_link_text = $options[ 'cff_facebook_link_text' ];
    $cff_view_link_text = $options[ 'cff_view_link_text' ];
    $cff_link_to_timeline = $options[ 'cff_link_to_timeline' ];
    //Meta
    $cff_icon_style = $options[ 'cff_icon_style' ];
    $cff_meta_text_color = $options[ 'cff_meta_text_color' ];
    $cff_meta_bg_color = $options[ 'cff_meta_bg_color' ];
    $cff_nocomments_text = $options[ 'cff_nocomments_text' ];
    $cff_hide_comments = $options[ 'cff_hide_comments' ];
    //Misc
    $cff_feed_width = $options[ 'cff_feed_width' ];
    $cff_feed_height = $options[ 'cff_feed_height' ];
    $cff_feed_padding = $options[ 'cff_feed_padding' ];
    $cff_like_box_position = $options[ 'cff_like_box_position' ];
    $cff_like_box_outside = $options[ 'cff_like_box_outside' ];
    $cff_likebox_width = $options[ 'cff_likebox_width' ];
    $cff_like_box_faces = $options[ 'cff_like_box_faces' ];

    $cff_show_media = $options[ 'cff_show_media' ];
    $cff_bg_color = $options[ 'cff_bg_color' ];
    $cff_likebox_bg_color = $options[ 'cff_likebox_bg_color' ];
    $cff_video_height = $options[ 'cff_video_height' ];
    $cff_show_author = $options[ 'cff_show_author' ];
    $cff_class = $options[ 'cff_class' ];

    //New
    $cff_custom_css = $options[ 'cff_custom_css' ];
    $cff_title_link = $options[ 'cff_title_link' ];
    $cff_event_title_link = $options[ 'cff_event_title_link' ];
    $cff_video_action = $options[ 'cff_video_action' ];
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
        //Update the General options
        if( isset($_POST[ $style_general_hidden_field_name ]) && $_POST[ $style_general_hidden_field_name ] == 'Y' ) {
            //General
            $cff_feed_width = $_POST[ 'cff_feed_width' ];
            $cff_feed_height = $_POST[ 'cff_feed_height' ];
            $cff_feed_padding = $_POST[ 'cff_feed_padding' ];
            $cff_bg_color = $_POST[ 'cff_bg_color' ];
            $cff_show_author = $_POST[ 'cff_show_author' ];
            $cff_class = $_POST[ 'cff_class' ];
            //Post types
            $cff_show_links_type = $_POST[ 'cff_show_links_type' ];
            $cff_show_event_type = $_POST[ 'cff_show_event_type' ];
            $cff_show_video_type = $_POST[ 'cff_show_video_type' ];
            $cff_show_photos_type = $_POST[ 'cff_show_photos_type' ];
            $cff_show_status_type = $_POST[ 'cff_show_status_type' ];
            //General
            $options[ 'cff_feed_width' ] = $cff_feed_width;
            $options[ 'cff_feed_height' ] = $cff_feed_height;
            $options[ 'cff_feed_padding' ] = $cff_feed_padding;
            $options[ 'cff_bg_color' ] = $cff_bg_color;
            $options[ 'cff_show_author' ] = $cff_show_author;
            $options[ 'cff_class' ] = $cff_class;
             //Post types
            $options[ 'cff_show_links_type' ] = $cff_show_links_type;
            $options[ 'cff_show_event_type' ] = $cff_show_event_type;
            $options[ 'cff_show_video_type' ] = $cff_show_video_type;
            $options[ 'cff_show_photos_type' ] = $cff_show_photos_type;
            $options[ 'cff_show_status_type' ] = $cff_show_status_type;
        }
        //Update the Post Layout options
        if( isset($_POST[ $style_post_layout_hidden_field_name ]) && $_POST[ $style_post_layout_hidden_field_name ] == 'Y' ) {
            //Layout
            $cff_preset_layout = $_POST[ 'cff_preset_layout' ];
            //Include
            $cff_show_text = $_POST[ 'cff_show_text' ];
            $cff_show_desc = $_POST[ 'cff_show_desc' ];
            $cff_show_shared_links = $_POST[ 'cff_show_shared_links' ];
            $cff_show_date = $_POST[ 'cff_show_date' ];
            $cff_show_media = $_POST[ 'cff_show_media' ];
            $cff_show_event_title = $_POST[ 'cff_show_event_title' ];
            $cff_show_event_details = $_POST[ 'cff_show_event_details' ];
            $cff_show_meta = $_POST[ 'cff_show_meta' ];
            $cff_show_link = $_POST[ 'cff_show_link' ];
            //Layout
            $options[ 'cff_preset_layout' ] = $cff_preset_layout;
            //Include
            $options[ 'cff_show_text' ] = $cff_show_text;
            $options[ 'cff_show_desc' ] = $cff_show_desc;
            $options[ 'cff_show_shared_links' ] = $cff_show_shared_links;
            $options[ 'cff_show_date' ] = $cff_show_date;
            $options[ 'cff_show_media' ] = $cff_show_media;
            $options[ 'cff_show_event_title' ] = $cff_show_event_title;
            $options[ 'cff_show_event_details' ] = $cff_show_event_details;
            $options[ 'cff_show_meta' ] = $cff_show_meta;
            $options[ 'cff_show_link' ] = $cff_show_link;
        }
        //Update the Post Layout options
        if( isset($_POST[ $style_typography_hidden_field_name ]) && $_POST[ $style_typography_hidden_field_name ] == 'Y' ) {
            //Character limits
            $cff_title_length_val = $_POST[ $cff_title_length ];
            $cff_body_length_val = $_POST[ $cff_body_length ];
            $cff_see_more_text = $_POST[ 'cff_see_more_text' ];
            $cff_see_less_text = $_POST[ 'cff_see_less_text' ];
            //Typography
            $cff_title_format = $_POST[ 'cff_title_format' ];
            $cff_title_size = $_POST[ 'cff_title_size' ];
            $cff_title_weight = $_POST[ 'cff_title_weight' ];
            $cff_title_color = $_POST[ 'cff_title_color' ];
            $cff_title_link = $_POST[ 'cff_title_link' ];
            $cff_body_size = $_POST[ 'cff_body_size' ];
            $cff_body_weight = $_POST[ 'cff_body_weight' ];
            $cff_body_color = $_POST[ 'cff_body_color' ];
            //Event title
            $cff_event_title_format = $_POST[ 'cff_event_title_format' ];
            $cff_event_title_size = $_POST[ 'cff_event_title_size' ];
            $cff_event_title_weight = $_POST[ 'cff_event_title_weight' ];
            $cff_event_title_color = $_POST[ 'cff_event_title_color' ];
            $cff_event_title_link = $_POST[ 'cff_event_title_link' ];
            //Event date
            $cff_event_date_size = $_POST[ 'cff_event_date_size' ];
            $cff_event_date_weight = $_POST[ 'cff_event_date_weight' ];
            $cff_event_date_color = $_POST[ 'cff_event_date_color' ];
            $cff_event_date_position = $_POST[ 'cff_event_date_position' ];
            $cff_event_date_formatting = $_POST[ 'cff_event_date_formatting' ];
            $cff_event_date_custom = $_POST[ 'cff_event_date_custom' ];
            //Event details
            $cff_event_details_size = $_POST[ 'cff_event_details_size' ];
            $cff_event_details_weight = $_POST[ 'cff_event_details_weight' ];
            $cff_event_details_color = $_POST[ 'cff_event_details_color' ];
            //Date
            $cff_date_position = $_POST[ 'cff_date_position' ];
            $cff_date_size = $_POST[ 'cff_date_size' ];
            $cff_date_weight = $_POST[ 'cff_date_weight' ];
            $cff_date_color = $_POST[ 'cff_date_color' ];
            $cff_date_formatting = $_POST[ 'cff_date_formatting' ];
            $cff_date_custom = $_POST[ 'cff_date_custom' ];
            $cff_date_before = $_POST[ 'cff_date_before' ];
            $cff_date_after = $_POST[ 'cff_date_after' ];
            //View on Facebook link
            $cff_link_size = $_POST[ 'cff_link_size' ];
            $cff_link_weight = $_POST[ 'cff_link_weight' ];
            $cff_link_color = $_POST[ 'cff_link_color' ];
            $cff_facebook_link_text = $_POST[ 'cff_facebook_link_text' ];
            $cff_view_link_text = $_POST[ 'cff_view_link_text' ];
            $cff_link_to_timeline = $_POST[ 'cff_link_to_timeline' ];
            //Character limits
            update_option( $cff_title_length, $cff_title_length_val );
            update_option( $cff_body_length, $cff_body_length_val );
            $options[ 'cff_see_more_text' ] = $cff_see_more_text;
            $options[ 'cff_see_less_text' ] = $cff_see_less_text;
            //Typography
            $options[ 'cff_title_format' ] = $cff_title_format;
            $options[ 'cff_title_size' ] = $cff_title_size;
            $options[ 'cff_title_weight' ] = $cff_title_weight;
            $options[ 'cff_title_color' ] = $cff_title_color;
            $options[ 'cff_title_link' ] = $cff_title_link;
            $options[ 'cff_body_size' ] = $cff_body_size;
            $options[ 'cff_body_weight' ] = $cff_body_weight;
            $options[ 'cff_body_color' ] = $cff_body_color;
            //Event title
            $options[ 'cff_event_title_format' ] = $cff_event_title_format;
            $options[ 'cff_event_title_size' ] = $cff_event_title_size;
            $options[ 'cff_event_title_weight' ] = $cff_event_title_weight;
            $options[ 'cff_event_title_color' ] = $cff_event_title_color;
            $options[ 'cff_event_title_link' ] = $cff_event_title_link;
            //Event date
            $options[ 'cff_event_date_size' ] = $cff_event_date_size;
            $options[ 'cff_event_date_weight' ] = $cff_event_date_weight;
            $options[ 'cff_event_date_color' ] = $cff_event_date_color;
            $options[ 'cff_event_date_position' ] = $cff_event_date_position;
            $options[ 'cff_event_date_formatting' ] = $cff_event_date_formatting;
            $options[ 'cff_event_date_custom' ] = $cff_event_date_custom;
            //Event details
            $options[ 'cff_event_details_size' ] = $cff_event_details_size;
            $options[ 'cff_event_details_weight' ] = $cff_event_details_weight;
            $options[ 'cff_event_details_color' ] = $cff_event_details_color;
            //Date
            $options[ 'cff_date_position' ] = $cff_date_position;
            $options[ 'cff_date_size' ] = $cff_date_size;
            $options[ 'cff_date_weight' ] = $cff_date_weight;
            $options[ 'cff_date_color' ] = $cff_date_color;
            $options[ 'cff_date_formatting' ] = $cff_date_formatting;
            $options[ 'cff_date_custom' ] = $cff_date_custom;
            $options[ 'cff_date_before' ] = $cff_date_before;
            $options[ 'cff_date_after' ] = $cff_date_after;
            //View on Facebook link
            $options[ 'cff_link_size' ] = $cff_link_size;
            $options[ 'cff_link_weight' ] = $cff_link_weight;
            $options[ 'cff_link_color' ] = $cff_link_color;
            $options[ 'cff_facebook_link_text' ] = $cff_facebook_link_text;
            $options[ 'cff_view_link_text' ] = $cff_view_link_text;
            $options[ 'cff_link_to_timeline' ] = $cff_link_to_timeline;
        }
        //Update the Post Layout options
        if( isset($_POST[ $style_misc_hidden_field_name ]) && $_POST[ $style_misc_hidden_field_name ] == 'Y' ) {
            //Meta
            $cff_icon_style = $_POST[ 'cff_icon_style' ];
            $cff_meta_text_color = $_POST[ 'cff_meta_text_color' ];
            $cff_meta_bg_color = $_POST[ 'cff_meta_bg_color' ];
            $cff_nocomments_text = $_POST[ 'cff_nocomments_text' ];
            $cff_hide_comments = $_POST[ 'cff_hide_comments' ];
            //Custom CSS
            $cff_custom_css = $_POST[ 'cff_custom_css' ];
            //Misc
            $cff_show_like_box = $_POST[ 'cff_show_like_box' ];
            $cff_like_box_position = $_POST[ 'cff_like_box_position' ];
            $cff_like_box_outside = $_POST[ 'cff_like_box_outside' ];
            $cff_likebox_bg_color = $_POST[ 'cff_likebox_bg_color' ];
            $cff_likebox_width = $_POST[ 'cff_likebox_width' ];
            $cff_like_box_faces = $_POST[ 'cff_like_box_faces' ];
            $cff_video_height = $_POST[ 'cff_video_height' ];
            $cff_video_action = $_POST[ 'cff_video_action' ];
            $cff_sep_color = $_POST[ 'cff_sep_color' ];
            $cff_sep_size = $_POST[ 'cff_sep_size' ];
            $cff_open_links = $_POST[ 'cff_open_links' ];
            //Meta
            $options[ 'cff_icon_style' ] = $cff_icon_style;
            $options[ 'cff_meta_text_color' ] = $cff_meta_text_color;
            $options[ 'cff_meta_bg_color' ] = $cff_meta_bg_color;
            $options[ 'cff_nocomments_text' ] = $cff_nocomments_text;
            $options[ 'cff_hide_comments' ] = $cff_hide_comments;
            //Custom CSS
            $options[ 'cff_custom_css' ] = $cff_custom_css;
            //Misc
            $options[ 'cff_show_like_box' ] = $cff_show_like_box;
            $options[ 'cff_like_box_position' ] = $cff_like_box_position;
            $options[ 'cff_like_box_outside' ] = $cff_like_box_outside;
            $options[ 'cff_likebox_bg_color' ] = $cff_likebox_bg_color;
            $options[ 'cff_likebox_width' ] = $cff_likebox_width;
            $options[ 'cff_like_box_faces' ] = $cff_like_box_faces;
            
            $options[ 'cff_video_height' ] = $cff_video_height;
            $options[ 'cff_video_action' ] = $cff_video_action;
            $options[ 'cff_sep_color' ] = $cff_sep_color;
            $options[ 'cff_sep_size' ] = $cff_sep_size;
            $options[ 'cff_open_links' ] = $cff_open_links;
        }
        //Update the array
        update_option( 'cff_style_settings', $options );
        // Put an settings updated message on the screen 
    ?>
    <div class="updated"><p><strong><?php _e('Settings saved.', 'custom-facebook-feed' ); ?></strong></p></div>
    <?php } ?> 
 
    <div id="cff-admin" class="wrap">
        <div id="header">
            <h1><?php _e('Layout & Style'); ?></h1>
        </div>
        <form name="form1" method="post" action="">
            <input type="hidden" name="<?php echo $style_hidden_field_name; ?>" value="Y">
            <?php
            $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
            ?>
            <h2 class="nav-tab-wrapper">
                <a href="?page=cff-style&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('General'); ?></a>
                <a href="?page=cff-style&tab=post_layout" class="nav-tab <?php echo $active_tab == 'post_layout' ? 'nav-tab-active' : ''; ?>"><?php _e('Post Layout'); ?></a>
                <a href="?page=cff-style&tab=typography" class="nav-tab <?php echo $active_tab == 'typography' ? 'nav-tab-active' : ''; ?>"><?php _e('Typography'); ?></a>
                <a href="?page=cff-style&tab=misc" class="nav-tab <?php echo $active_tab == 'misc' ? 'nav-tab-active' : ''; ?>"><?php _e('Misc'); ?></a>
            </h2>
            <?php if( $active_tab == 'general' ) { //Start General tab ?>
            <input type="hidden" name="<?php echo $style_general_hidden_field_name; ?>" value="Y">
            <br />
            <table class="form-table">
                <tbody>
                    <h3><?php _e('General'); ?></h3>
                    <tr valign="top">
                        <th scope="row"><?php _e('Feed Width'); ?></th>
                        <td>
                            <input name="cff_feed_width" type="text" value="<?php esc_attr_e( $cff_feed_width ); ?>" size="6" />
                            <span>Eg. 500px, 50%, 10em.  <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('Default is 100%'); ?></i></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Feed Height'); ?></th>
                        <td>
                            <input name="cff_feed_height" type="text" value="<?php esc_attr_e( $cff_feed_height ); ?>" size="6" />
                            <span>Eg. 500px, 50em. <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('Leave empty to set no maximum height. If the feed exceeds this height then a scroll bar will be used.'); ?></i></span>
                        </td>
                    </tr>
                        <th scope="row"><?php _e('Feed Padding'); ?></th>
                        <td>
                            <input name="cff_feed_padding" type="text" value="<?php esc_attr_e( $cff_feed_padding ); ?>" size="6" />
                            <span>Eg. 20px, 5%. <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('This is the amount of padding/spacing that goes around the feed. This is particularly useful if you intend to set a background color on the feed.'); ?></i></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Feed Background Color'); ?></th>
                        <td>
                            <label for="cff_bg_color">#</label>
                            <input name="cff_bg_color" type="text" value="<?php esc_attr_e( $cff_bg_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                            <span><a href="http://www.colorpicker.com/" target="_blank"><?php _e('Color Picker'); ?></a></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Show name and picture of author'); ?></th>
                        <td>
                            <input name="cff_show_author" type="checkbox" id="cff_show_author" <?php if($cff_show_author == true) echo "checked"; ?> />
                            <label for="cff_show_status_type">Yes</label>
                            <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('This will show the thumbnail picture and name of the post author at the top of each post'); ?></i>
                            
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Add CSS class to feed'); ?></th>
                        <td>
                            <input name="cff_class" type="text" value="<?php esc_attr_e( $cff_class ); ?>" size="25" />
                            <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('To add multiple classes separate each with a space, Eg. classone classtwo classthree'); ?></i>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <hr />
            <table class="form-table">
                <tbody>
                    <h3><?php _e('Post Types'); ?></h3>
                    <tr valign="top">
                        <th scope="row"><?php _e('Only show these types of posts:'); ?><br />
                            <i style="color: #666; font-size: 11px;"><a href="http://smashballoon.com/custom-facebook-feed/" target="_blank"><?php _e('Upgrade to Pro to enable post types, photos, videos and more'); ?></a></i></th>
                        <td>
                            <div>
                                <input name="cff_show_status_type" type="checkbox" id="cff_show_status_type" disabled checked />
                                <label for="cff_show_status_type"><?php _e('Statuses'); ?></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_event_type" id="cff_show_event_type" disabled checked />
                                <label for="cff_show_event_type"><?php _e('Events'); ?></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_photos_type" id="cff_show_photos_type" disabled checked />
                                <label for="cff_show_photos_type"><?php _e('Photos'); ?></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_video_type" id="cff_show_video_type" disabled checked />
                                <label for="cff_show_video_type"><?php _e('Videos'); ?></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_links_type" id="cff_show_links_type" disabled checked />
                                <label for="cff_show_links_type"><?php _e('Links'); ?></label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php submit_button(); ?>
            
            <a href="http://smashballoon.com/custom-facebook-feed/demo" target="_blank"><img src="<?php echo plugins_url( 'img/pro.png' , __FILE__ ) ?>" /></a>
            <?php } //End General tab ?>
            <?php if( $active_tab == 'post_layout' ) { //Start Post Layout tab ?>
            <input type="hidden" name="<?php echo $style_post_layout_hidden_field_name; ?>" value="Y">
            <br />
            <h3><?php _e('Post Layout'); ?></h3>
            <table class="form-table">
                <tbody>
                    <tr>
                        <td><p><?php _e('Choose a layout from the 3 below:'); ?></p></td>
                        <td>
                            <select name="cff_preset_layout" disabled>
                                <option value="thumb"><?php _e('Thumbnail'); ?></option>
                                <option value="half"><?php _e('Half-width'); ?></option>
                                <option value="full"><?php _e('Full-width'); ?></option>
                            </select>
                            <i style="color: #666; font-size: 11px; margin-left: 5px;"><a href="http://smashballoon.com/custom-facebook-feed/" target="_blank"><?php _e('Upgrade to Pro to enable post layouts'); ?></a></i>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Thumbnail:'); ?></th>
                        <td>
                            <img src="<?php echo plugins_url( 'img/layout-thumb.png' , __FILE__ ) ?>" alt="Thumbnail Layout" width="400px" style="border: 1px solid #ccc;" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Half-width:'); ?></th>
                        <td>
                            <img src="<?php echo plugins_url( 'img/layout-half.png' , __FILE__ ) ?>" alt="Half Width Layout" width="400px" style="border: 1px solid #ccc;" />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Full-width:'); ?></th>
                        <td>
                            <img src="<?php echo plugins_url( 'img/layout-full.png' , __FILE__ ) ?>" alt="Full Width Layout" width="400px" style="border: 1px solid #ccc;" />
                        </td>
                    </tr>
                    </tbody>
                </table>
                <hr />
                <h3><?php _e('Show/Hide'); ?></h3>
                <table class="form-table">
                    <tbody>
                    <tr valign="top">
                        <th scope="row"><?php _e('Include the following in posts:'); ?><br /><?php _e('(when applicable)'); ?>
                            <br /><i style="color: #666; font-size: 11px;"><a href="http://smashballoon.com/custom-facebook-feed/" target="_blank"><?php _e('Upgrade to Pro to enable all of these options'); ?></a></i></th>
                        <td>
                            <div>
                                <input name="cff_show_text" type="checkbox" id="cff_show_text" <?php if($cff_show_text == true) echo "checked"; ?> />
                                <label for="cff_show_text"><?php _e('Post text'); ?></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_date" id="cff_show_date" <?php if($cff_show_date == true) echo 'checked="checked"' ?> />
                                <label for="cff_show_date"><?php _e('Date'); ?></label>
                            </div>
                            <div>
                                <input type="checkbox" id="cff_show_media" disabled />
                                <label for="cff_show_media"><?php _e('Photos/videos'); ?></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_shared_links" id="cff_show_shared_links" <?php if($cff_show_shared_links == true) echo 'checked="checked"' ?> />
                                <label for="cff_show_shared_links"><?php _e('Shared links'); ?></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_desc" id="cff_show_desc" <?php if($cff_show_desc == true) echo 'checked="checked"' ?> />
                                <label for="cff_show_desc"><?php _e('Link, photo and video descriptions'); ?></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_event_title" id="cff_show_event_title" <?php if($cff_show_event_title == true) echo 'checked="checked"' ?> />
                                <label for="cff_show_event_title"><?php _e('Event title'); ?></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_event_details" id="cff_show_event_details" <?php if($cff_show_event_details == true) echo 'checked="checked"' ?> />
                                <label for="cff_show_event_details"><?php _e('Event details'); ?></label>
                            </div>
                            <div>
                                <input type="checkbox" id="cff_show_meta" disabled />
                                <label for="cff_show_meta"><?php _e('Like/shares/comments'); ?></label>
                            </div>
                            <div>
                                <input type="checkbox" name="cff_show_link" id="cff_show_link" <?php if($cff_show_link == true) echo 'checked="checked"' ?> />
                                <label for="cff_show_link"><?php _e('View on Facebook/View Link'); ?></label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <?php submit_button(); ?>
            <a href="http://smashballoon.com/custom-facebook-feed/demo" target="_blank"><img src="<?php echo plugins_url( 'img/pro.png' , __FILE__ ) ?>" /></a>
            <?php } //End Post Layout tab ?>
            <?php if( $active_tab == 'typography' ) { //Start Typography tab ?>
            <input type="hidden" name="<?php echo $style_typography_hidden_field_name; ?>" value="Y">
            <br />
            <h3><?php _e('Typography'); ?></h3>
            <p><i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('"Inherit" means that the text will inherit the styles from your theme.'); ?></i></p>
            <div id="poststuff" class="metabox-holder">
                <div class="meta-box-sortables ui-sortable">
                <div id="adminform" class="postbox" style="display: block;">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php _e('Text Character Limits'); ?></span></h3>
                    <div class="inside">
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th scope="row"><label class="bump-left"><?php _e('Maximum Post Text Length'); ?></label></th>
                                    <td>
                                        <input name="cff_title_length" type="text" value="<?php esc_attr_e( $cff_title_length_val ); ?>" size="4" /> <span><?php _e('Characters.'); ?></span> <span>Eg. 200</span> <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('If the post text exceeds this length then a "See More" button will be added. Leave empty to set no maximum length.'); ?></i>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><label class="bump-left"><?php _e('Maximum Description Length'); ?></label></th>
                                    <td>
                                        <input name="cff_body_length" type="text" value="<?php esc_attr_e( $cff_body_length_val ); ?>" size="4" /> <span><?php _e('Characters.'); ?></span> <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('Leave empty to set no maximum length'); ?></i>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="cff_see_more_text" class="bump-left"><?php _e('Custom "See More" text'); ?></label></th>
                                    <td>
                                        <input name="cff_see_more_text" type="text" value="<?php esc_attr_e( $cff_see_more_text ); ?>" size="20" />
                                        <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('Use different text in place of the default "See More" text'); ?></i>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="cff_see_less_text" class="bump-left"><?php _e('Custom "See Less" text'); ?></label></th>
                                    <td>
                                        <input name="cff_see_less_text" type="text" value="<?php esc_attr_e( $cff_see_less_text ); ?>" size="20" />
                                        <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('Use different text in place of the default "See Less" text'); ?></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="adminform" class="postbox" style="display: block;">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php _e('Post Text'); ?></span></h3>
                    <div class="inside">
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th><label for="cff_title_format" class="bump-left"><?php _e('Format'); ?></label></th>
                                    <td>
                                        <select name="cff_title_format">
                                            <option value="p" <?php if($cff_title_format == "p") echo 'selected="selected"' ?> >Paragraph</option>
                                            <option value="h3" <?php if($cff_title_format == "h3") echo 'selected="selected"' ?> >Heading 3</option>
                                            <option value="h4" <?php if($cff_title_format == "h4") echo 'selected="selected"' ?> >Heading 4</option>
                                            <option value="h5" <?php if($cff_title_format == "h5") echo 'selected="selected"' ?> >Heading 5</option>
                                            <option value="h6" <?php if($cff_title_format == "h6") echo 'selected="selected"' ?> >Heading 6</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="cff_title_size" class="bump-left"><?php _e('Text Size'); ?></label></th>
                                    <td>
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
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="cff_title_weight" class="bump-left"><?php _e('Text Weight'); ?></label></th>
                                    <td>
                                        <select name="cff_title_weight">
                                            <option value="inherit" <?php if($cff_title_weight == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                            <option value="normal" <?php if($cff_title_weight == "normal") echo 'selected="selected"' ?> >Normal</option>
                                            <option value="bold" <?php if($cff_title_weight == "bold") echo 'selected="selected"' ?> >Bold</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="cff_title_color" class="bump-left"><?php _e('Text Color'); ?></label></th>
                                    <td>
                                        #<input name="cff_title_color" type="text" value="<?php esc_attr_e( $cff_title_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                                        <span><a href="http://www.colorpicker.com/" target="_blank"><?php _e('Color Picker'); ?></a></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="cff_title_link" class="bump-left"><?php _e('Link text to Facebook post?'); ?></label></th>
                                    <td><input type="checkbox" name="cff_title_link" id="cff_title_link" <?php if($cff_title_link == true) echo 'checked="checked"' ?> />&nbsp;Yes</td>
                                </tr>
                                
                                </tbody>
                            </table>
                        </div>
                </div>
                <div id="adminform" class="postbox" style="display: block;">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php _e('Link, Photo and Video Description'); ?></span></h3>
                    <div class="inside">
                        <table class="form-table">
                            <tbody>
                            
                            <tr>
                                <th><label for="cff_body_size" class="bump-left"><?php _e('Text Size'); ?></label></th>
                                <td>
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
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_body_weight" class="bump-left"><?php _e('Text Weight'); ?></label></th>
                                <td>
                                    <select name="cff_body_weight">
                                        <option value="inherit" <?php if($cff_body_weight == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                        <option value="normal" <?php if($cff_body_weight == "normal") echo 'selected="selected"' ?> >Normal</option>
                                        <option value="bold" <?php if($cff_body_weight == "bold") echo 'selected="selected"' ?> >Bold</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_body_color" class="bump-left"><?php _e('Text Color'); ?></label></th>
                                
                                <td>
                                    #<input name="cff_body_color" type="text" value="<?php esc_attr_e( $cff_body_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                                    <a href="http://www.colorpicker.com/" target="_blank"><?php _e('Color Picker'); ?></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <div style="margin-top: -15px;">
                <?php submit_button(); ?>
            </div>
                <div id="adminform" class="postbox" style="display: block;">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php _e('Date'); ?></span></h3>
                    <div class="inside">
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th><label for="cff_date_position" class="bump-left"><?php _e('Position'); ?></label></th>
                                <td>
                                    <select name="cff_date_position">
                                        <option value="below" <?php if($cff_date_position == "below") echo 'selected="selected"' ?> >Below Text</option>
                                        <option value="above" <?php if($cff_date_position == "above") echo 'selected="selected"' ?> >Above Text</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_date_size" class="bump-left"><?php _e('Text Size'); ?></label></th>
                                <td>
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
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_date_weight" class="bump-left"><?php _e('Text Weight'); ?></label></th>
                                <td>
                                    <select name="cff_date_weight">
                                        <option value="inherit" <?php if($cff_date_weight == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                        <option value="normal" <?php if($cff_date_weight == "normal") echo 'selected="selected"' ?> >Normal</option>
                                        <option value="bold" <?php if($cff_date_weight == "bold") echo 'selected="selected"' ?> >Bold</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_date_color" class="bump-left"><?php _e('Text Color'); ?></label></th>
                                <td>
                                    #<input name="cff_date_color" type="text" value="<?php esc_attr_e( $cff_date_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                                    <a href="http://www.colorpicker.com/" target="_blank">Color Picker</a>
                                </td>
                            </tr>
                                    
                            <tr>
                                <th><label for="cff_date_formatting" class="bump-left"><?php _e('Date formatting'); ?></label></th>
                                <td>
                                    <select name="cff_date_formatting">
                                        <?php $original = strtotime('2013-07-25T17:30:00+0000'); ?>
                                        <option value="1" <?php if($cff_date_formatting == "1") echo 'selected="selected"' ?> ><?php _e('Posted 2 days ago'); ?></option>
                                        <option value="2" <?php if($cff_date_formatting == "2") echo 'selected="selected"' ?> ><?php echo date('F jS, g:i a', $original); ?></option>
                                        <option value="3" <?php if($cff_date_formatting == "3") echo 'selected="selected"' ?> ><?php echo date('F jS', $original); ?></option>
                                        <option value="4" <?php if($cff_date_formatting == "4") echo 'selected="selected"' ?> ><?php echo date('D F jS', $original); ?></option>
                                        <option value="5" <?php if($cff_date_formatting == "5") echo 'selected="selected"' ?> ><?php echo date('l F jS', $original); ?></option>
                                        <option value="6" <?php if($cff_date_formatting == "6") echo 'selected="selected"' ?> ><?php echo date('D M jS, Y', $original); ?></option>
                                        <option value="7" <?php if($cff_date_formatting == "7") echo 'selected="selected"' ?> ><?php echo date('l F jS, Y', $original); ?></option>
                                        <option value="8" <?php if($cff_date_formatting == "8") echo 'selected="selected"' ?> ><?php echo date('l F jS, Y - g:i a', $original); ?></option>
                                        <option value="9" <?php if($cff_date_formatting == "9") echo 'selected="selected"' ?> ><?php echo date("l M jS, 'y", $original); ?></option>
                                        <option value="10" <?php if($cff_date_formatting == "10") echo 'selected="selected"' ?> ><?php echo date('m.d.y', $original); ?></option>
                                        <option value="11" <?php if($cff_date_formatting == "11") echo 'selected="selected"' ?> ><?php echo date('m/d/y', $original); ?></option>
                                        <option value="12" <?php if($cff_date_formatting == "12") echo 'selected="selected"' ?> ><?php echo date('d.m.y', $original); ?></option>
                                        <option value="13" <?php if($cff_date_formatting == "13") echo 'selected="selected"' ?> ><?php echo date('d/m/y', $original); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_date_custom" class="bump-left"><?php _e('Custom format'); ?></label></th>
                                <td>
                                    <input name="cff_date_custom" type="text" value="<?php esc_attr_e( $cff_date_custom ); ?>" size="10" placeholder="Eg. F j, Y" />
                                    <i style="color: #666; font-size: 11px;">(<a href="http://smashballoon.com/custom-facebook-feed/docs/date/" target="_blank"><?php _e('Examples'); ?></a>)</i>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_date_before" class="bump-left"><?php _e('Text before date'); ?></label></th>
                                <td><input name="cff_date_before" type="text" value="<?php esc_attr_e( $cff_date_before ); ?>" size="10" placeholder="Eg. Posted" /></td>
                            </tr>
                            <tr>
                                <th><label for="cff_date_after" class="bump-left"><?php _e('Text after date'); ?></label></th>
                                <td><input name="cff_date_after" type="text" value="<?php esc_attr_e( $cff_date_after ); ?>" size="10" placeholder="Eg. ago" /></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="adminform" class="postbox" style="display: block;">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php _e('Event Title'); ?></span></h3>
                    <div class="inside">
                        <table class="form-table">
                            <tbody>
                            
                            <tr>
                                <th><label for="cff_event_title_format" class="bump-left"><?php _e('Format'); ?></label></th>
                                <td>
                                    <select name="cff_event_title_format">
                                        <option value="p" <?php if($cff_event_title_format == "p") echo 'selected="selected"' ?> >Paragraph</option>
                                        <option value="h3" <?php if($cff_event_title_format == "h3") echo 'selected="selected"' ?> >Heading 3</option>
                                        <option value="h4" <?php if($cff_event_title_format == "h4") echo 'selected="selected"' ?> >Heading 4</option>
                                        <option value="h5" <?php if($cff_event_title_format == "h5") echo 'selected="selected"' ?> >Heading 5</option>
                                        <option value="h6" <?php if($cff_event_title_format == "h6") echo 'selected="selected"' ?> >Heading 6</option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <th><label for="cff_event_title_size" class="bump-left"><?php _e('Text Size'); ?></label></th>
                                <td>
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
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_event_title_weight" class="bump-left"><?php _e('Text Weight'); ?></label></th>
                                <td>
                                    <select name="cff_event_title_weight">
                                        <option value="inherit" <?php if($cff_event_title_weight == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                        <option value="normal" <?php if($cff_event_title_weight == "normal") echo 'selected="selected"' ?> >Normal</option>
                                        <option value="bold" <?php if($cff_event_title_weight == "bold") echo 'selected="selected"' ?> >Bold</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_event_title_color" class="bump-left"><?php _e('Text Color'); ?></label></th>
                                <td>
                                    <input name="cff_event_title_color" type="text" value="<?php esc_attr_e( $cff_event_title_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                                    <a href="http://www.colorpicker.com/" target="_blank"><?php _e('Color Picker'); ?></a>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_title_link" class="bump-left"><?php _e('Link title to Facebook event page?'); ?></label></th>
                                <td><input type="checkbox" name="cff_event_title_link" id="cff_event_title_link" <?php if($cff_event_title_link == true) echo 'checked="checked"' ?> />&nbsp;Yes</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="margin-top: -15px;">
                    <?php submit_button(); ?>
                </div>
                <div id="adminform" class="postbox" style="display: block;">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php _e('Event Date'); ?></span></h3>
                    <div class="inside">
                        <table class="form-table">
                            <tbody>
                            
                            <tr>
                                <th><label for="cff_event_date_size" class="bump-left"><?php _e('Text Size'); ?></label></th>
                                <td>
                                    <select name="cff_event_date_size">
                                        <option value="inherit" <?php if($cff_event_date_size == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                        <option value="10" <?php if($cff_event_date_size == "10") echo 'selected="selected"' ?> >10px</option>
                                        <option value="11" <?php if($cff_event_date_size == "11") echo 'selected="selected"' ?> >11px</option>
                                        <option value="12" <?php if($cff_event_date_size == "12") echo 'selected="selected"' ?> >12px</option>
                                        <option value="14" <?php if($cff_event_date_size == "14") echo 'selected="selected"' ?> >14px</option>
                                        <option value="16" <?php if($cff_event_date_size == "16") echo 'selected="selected"' ?> >16px</option>
                                        <option value="18" <?php if($cff_event_date_size == "18") echo 'selected="selected"' ?> >18px</option>
                                        <option value="20" <?php if($cff_event_date_size == "20") echo 'selected="selected"' ?> >20px</option>
                                        <option value="24" <?php if($cff_event_date_size == "24") echo 'selected="selected"' ?> >24px</option>
                                        <option value="28" <?php if($cff_event_date_size == "28") echo 'selected="selected"' ?> >28px</option>
                                        <option value="32" <?php if($cff_event_date_size == "32") echo 'selected="selected"' ?> >32px</option>
                                        <option value="36" <?php if($cff_event_date_size == "36") echo 'selected="selected"' ?> >36px</option>
                                        <option value="42" <?php if($cff_event_date_size == "42") echo 'selected="selected"' ?> >42px</option>
                                        <option value="48" <?php if($cff_event_date_size == "48") echo 'selected="selected"' ?> >48px</option>
                                        <option value="60" <?php if($cff_event_date_size == "54") echo 'selected="selected"' ?> >54px</option>
                                        <option value="60" <?php if($cff_event_date_size == "60") echo 'selected="selected"' ?> >60px</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_event_date_weight" class="bump-left"><?php _e('Text Weight'); ?></label></th>
                                <td>
                                    <select name="cff_event_date_weight">
                                        <option value="inherit" <?php if($cff_event_date_weight == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                        <option value="normal" <?php if($cff_event_date_weight == "normal") echo 'selected="selected"' ?> >Normal</option>
                                        <option value="bold" <?php if($cff_event_date_weight == "bold") echo 'selected="selected"' ?> >Bold</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_event_date_color" class="bump-left"><?php _e('Text Color'); ?></label></th>
                                <td>
                                    #<input name="cff_event_date_color" type="text" value="<?php esc_attr_e( $cff_event_date_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                                    <a href="http://www.colorpicker.com/" target="_blank"><?php _e('Color Picker'); ?></a>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><label class="bump-left"><?php _e('Date Position'); ?></label></th>
                                <td>
                                    <select name="cff_event_date_position">
                                        <option value="below" <?php if($cff_event_date_position == "below") echo 'selected="selected"' ?> ><?php _e('Below event title'); ?></option>
                                        <option value="above" <?php if($cff_event_date_position == "above") echo 'selected="selected"' ?> ><?php _e('Above event title'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_event_date_formatting" class="bump-left"><?php _e('Event date formatting'); ?></label></th>
                                <td>
                                    <select name="cff_event_date_formatting">
                                        <?php $original = strtotime('2013-07-25T17:30:00+0000'); ?>
                                        <option value="1" <?php if($cff_event_date_formatting == "1") echo 'selected="selected"' ?> ><?php echo date('F j, Y, g:ia', $original); ?></option>
                                        <option value="2" <?php if($cff_event_date_formatting == "2") echo 'selected="selected"' ?> ><?php echo date('F jS, g:ia', $original); ?></option>
                                        <option value="3" <?php if($cff_event_date_formatting == "3") echo 'selected="selected"' ?> ><?php echo date('g:ia - F jS', $original); ?></option>
                                        <option value="4" <?php if($cff_event_date_formatting == "4") echo 'selected="selected"' ?> ><?php echo date('g:ia, F jS', $original); ?></option>
                                        <option value="5" <?php if($cff_event_date_formatting == "5") echo 'selected="selected"' ?> ><?php echo date('l F jS - g:ia', $original); ?></option>
                                        <option value="6" <?php if($cff_event_date_formatting == "6") echo 'selected="selected"' ?> ><?php echo date('D M jS, Y, g:iA', $original); ?></option>
                                        <option value="7" <?php if($cff_event_date_formatting == "7") echo 'selected="selected"' ?> ><?php echo date('l F jS, Y, g:iA', $original); ?></option>
                                        <option value="8" <?php if($cff_event_date_formatting == "8") echo 'selected="selected"' ?> ><?php echo date('l F jS, Y - g:ia', $original); ?></option>
                                        <option value="9" <?php if($cff_event_date_formatting == "9") echo 'selected="selected"' ?> ><?php echo date("l M jS, 'y", $original); ?></option>
                                        <option value="10" <?php if($cff_event_date_formatting == "10") echo 'selected="selected"' ?> ><?php echo date('m.d.y - g:iA', $original); ?></option>
                                        <option value="11" <?php if($cff_event_date_formatting == "11") echo 'selected="selected"' ?> ><?php echo date('m/d/y, g:ia', $original); ?></option>
                                        <option value="12" <?php if($cff_event_date_formatting == "12") echo 'selected="selected"' ?> ><?php echo date('d.m.y - g:iA', $original); ?></option>
                                        <option value="13" <?php if($cff_event_date_formatting == "13") echo 'selected="selected"' ?> ><?php echo date('d/m/y, g:ia', $original); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_event_date_custom" class="bump-left"><?php _e('Custom event date format'); ?></label></th>
                                <td>
                                    <input name="cff_event_date_custom" type="text" value="<?php esc_attr_e( $cff_event_date_custom ); ?>" size="10" placeholder="Eg. F j, Y - g:ia" />
                                    <i style="color: #666; font-size: 11px;">(<a href="http://smashballoon.com/custom-facebook-feed/docs/date/" target="_blank"><?php _e('Examples'); ?></a>)</i>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="adminform" class="postbox" style="display: block;">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php _e('Event Details'); ?></span></h3>
                    <div class="inside">
                        <table class="form-table">
                            <tbody>
                            
                            <tr>
                                <th><label for="cff_event_details_size" class="bump-left"><?php _e('Text Size'); ?></label></th>
                                <td>
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
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_event_details_weight" class="bump-left"><?php _e('Text Weight'); ?></label></th>
                                <td>
                                    <select name="cff_event_details_weight">
                                        <option value="inherit" <?php if($cff_event_details_weight == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                        <option value="normal" <?php if($cff_event_details_weight == "normal") echo 'selected="selected"' ?> >Normal</option>
                                        <option value="bold" <?php if($cff_event_details_weight == "bold") echo 'selected="selected"' ?> >Bold</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_event_details_color" class="bump-left"><?php _e('Text Color'); ?></label></th>
                                <td>
                                    #<input name="cff_event_details_color" type="text" value="<?php esc_attr_e( $cff_event_details_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                                    <a href="http://www.colorpicker.com/" target="_blank"><?php _e('Color Picker'); ?></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="adminform" class="postbox" style="display: block;">
                    <div class="handlediv" title="Click to toggle"><br></div>
                    <h3 class="hndle"><span><?php _e('Link to Facebook'); ?></span></h3>
                    <div class="inside">
                        <table class="form-table">
                            <tbody>
                                
                            <tr>
                                <th><label for="cff_link_size" class="bump-left"><?php _e('Text Size'); ?></label></th>
                                <td>
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
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_link_weight" class="bump-left"><?php _e('Text Weight'); ?></label></th>
                                <td>
                                    <select name="cff_link_weight">
                                        <option value="inherit" <?php if($cff_link_weight == "inherit") echo 'selected="selected"' ?> >Inherit</option>
                                        <option value="normal" <?php if($cff_link_weight == "normal") echo 'selected="selected"' ?> >Normal</option>
                                        <option value="bold" <?php if($cff_link_weight == "bold") echo 'selected="selected"' ?> >Bold</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_link_color" class="bump-left"><?php _e('Text Color'); ?></label></th>
                                <td>
                                    <input name="cff_link_color" type="text" value="<?php esc_attr_e( $cff_link_color ); ?>" size="10" placeholder="Eg. ED9A00" />
                                    <a href="http://www.colorpicker.com/" target="_blank"><?php _e('Color Picker'); ?></a>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_facebook_link_text" class="bump-left"><?php _e('Custom "View on Facebook" text'); ?></label></th>
                                <td>
                                    <input name="cff_facebook_link_text" type="text" value="<?php esc_attr_e( $cff_facebook_link_text ); ?>" size="20" />
                                    <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('Use different text in place of the default "View on Facebook" link'); ?></i>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_view_link_text" class="bump-left"><?php _e('Custom "View Link" text'); ?></label></th>
                                <td>
                                    <input name="cff_view_link_text" type="text" value="<?php esc_attr_e( $cff_view_link_text ); ?>" size="20" />
                                    <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('Use different text in place of the default "View on Facebook" link'); ?></i>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="cff_link_to_timeline" class="bump-left"><?php _e('Link statuses to your page'); ?></label></th>
                                <td>
                                    <input type="checkbox" name="cff_link_to_timeline" id="cff_link_to_timeline" <?php if($cff_link_to_timeline == true) echo 'checked="checked"' ?> />&nbsp;Yes
                                    <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e("Check this if you'd like to link statuses to your Facebook timeline/page instead of to their individual posts on Facebook"); ?></i>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                    </div>
                </div>
                </div>
            </div>
            <div style="margin-top: -15px;">
                <?php submit_button(); ?>
            </div>
            <a href="http://smashballoon.com/custom-facebook-feed/demo" target="_blank"><img src="<?php echo plugins_url( 'img/pro.png' , __FILE__ ) ?>" /></a>
            
            <?php } //End Typography tab ?>
            <?php if( $active_tab == 'misc' ) { //Start Misc tab ?>
            <input type="hidden" name="<?php echo $style_misc_hidden_field_name; ?>" value="Y">
            <br />
            <h3><?php _e('Likes, Shares and Comments'); ?></h3><i style="color: #666; font-size: 11px;"><a href="http://smashballoon.com/custom-facebook-feed/" target="_blank"><?php _e('Upgrade to Pro to enable likes, shares and comments'); ?></a></i>
            
            <hr />
            <h3><?php _e('Custom CSS'); ?></h3>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <td>
                        <?php _e('Enter your own custom CSS in the box below'); ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td>
                            <textarea name="cff_custom_css" id="cff_custom_css" style="width: 70%;" rows="7"><?php esc_attr_e( $cff_custom_css ); ?></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr />
            <h3><?php _e('Misc'); ?></h3>
            <table class="form-table">
                <tbody>
                    <tr><td><b style="font-size: 14px;"><?php _e('Like Box'); ?></b></td></tr>
                    <tr valign="top">
                        <th scope="row"><label class="bump-left"><?php _e('Show the Like Box'); ?></label></th>
                        <td>
                            <input type="checkbox" name="cff_show_like_box" id="cff_show_like_box" <?php if($cff_show_like_box == true) echo 'checked="checked"' ?> />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label class="bump-left"><?php _e('Like Box Position'); ?></label></th>
                        <td>
                            <select name="cff_like_box_position">
                                <option value="bottom" <?php if($cff_like_box_position == "bottom") echo 'selected="selected"' ?> ><?php _e('Bottom'); ?></option>
                                <option value="top" <?php if($cff_like_box_position == "top") echo 'selected="selected"' ?> ><?php _e('Top'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label class="bump-left"><?php _e('Display outside the scrollable area'); ?></label></th>
                        <td>
                            <input type="checkbox" name="cff_like_box_outside" id="cff_like_box_outside" <?php if($cff_like_box_outside == true) echo 'checked="checked"' ?> />
                            <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('(Only applicable if you have set a height on the feed)'); ?></i>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label class="bump-left"><?php _e('Like Box Background Color'); ?></label></th>
                        <td>
                            <label for="cff_likebox_bg_color">#</label>
                            <input name="cff_likebox_bg_color" type="text" value="<?php esc_attr_e( $cff_likebox_bg_color ); ?>" size="10" />
                            <span>Eg. ED9A00</span>&nbsp;&nbsp;<a href="http://www.colorpicker.com/" target="_blank"><?php _e('Color Picker'); ?></a>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="cff_likebox_width" class="bump-left"><?php _e('Like Box Width'); ?></label></th>
                        <td>
                            <input name="cff_likebox_width" type="text" value="<?php esc_attr_e( $cff_likebox_width ); ?>" size="6" />
                            <span>px  <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('Default is 300'); ?></i></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label class="bump-left"><?php _e('Show faces in Like Box'); ?></label></th>
                        <td>
                            <input type="checkbox" name="cff_like_box_faces" id="cff_like_box_faces" <?php if($cff_like_box_faces == true) echo 'checked="checked"' ?> />
                            <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('Show thumbnail photos of fans who like your page'); ?></i>
                        </td>
                    </tr>
                    
                    <tr><td><b style="font-size: 14px;"><?php _e('Separating Line'); ?></b></td></tr>
                    <tr valign="top">
                        <th scope="row"><label class="bump-left"><?php _e('Separating Line Color'); ?></label></th>
                        <td>
                            <label for="cff_sep_color">#</label>
                            <input name="cff_sep_color" type="text" value="<?php esc_attr_e( $cff_sep_color ); ?>" size="10" />
                            <span>Eg. ED9A00</span>&nbsp;&nbsp;<a href="http://www.colorpicker.com/" target="_blank"><?php _e('Color Picker'); ?></a>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label class="bump-left"><?php _e('Separating Line Thickness'); ?></label></th>
                        <td>
                            <input name="cff_sep_size" type="text" value="<?php esc_attr_e( $cff_sep_size ); ?>" size="1" /><span>px</span> <i style="color: #666; font-size: 11px; margin-left: 5px;"><?php _e('(Leave empty to hide)'); ?></i>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php submit_button(); ?>
            <a href="http://smashballoon.com/custom-facebook-feed/demo" target="_blank"><img src="<?php echo plugins_url( 'img/pro.png' , __FILE__ ) ?>" /></a>
            <?php } //End Misc tab ?>
        </form>
<?php 
} //End Style_Page
//Enqueue admin styles
function cff_admin_style() {
        wp_register_style( 'custom_wp_admin_css', plugin_dir_url( __FILE__ ) . 'css/cff-admin-style.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'cff_admin_style' );
//Enqueue admin scripts
function cff_admin_scripts() {
    wp_enqueue_script( 'cff_admin_script', plugin_dir_url( __FILE__ ) . 'js/cff-admin-scripts.js' );
    if( !wp_script_is('jquery-ui-draggable') ) { 
        wp_enqueue_script(
            array(
            'jquery',
            'jquery-ui-core',
            'jquery-ui-draggable'
            )
        );
    }
    wp_enqueue_script( 'hoverIntent' );
}
add_action( 'admin_enqueue_scripts', 'cff_admin_scripts' );
?>