<?php

/*
  Plugin Name: LiveSmart Video Chat
  Plugin URI: https://www.new-dev.com/page/ident/live_smart_video_chat
  Description: LiveSmart Widget HTML and JavaScript. It can be addded in a page using the tag [livesmart_widget] or just call do_action('livesmart_widget');
  Version: 1.1
  Author: LiveSmart
  Author URI: https://www.new-dev.com
 */

function html_livesmart_code($names, $avatar, $roomId, $agentId, $iframeId = null) {
    $livesmart_css = (get_option('livesmart_css') != '') ? get_option('livesmart_css') : '';
    $message = (get_option('livesmart_front_message') != '') ? get_option('livesmart_front_message') : 'Start Video Chat';
    $names = $names ? $names : get_option('livesmart_names');
    $avatar = $avatar ? $avatar : get_option('livesmart_avatar');
    $livesmart_server_url = (get_option('livesmart_server_url') != '') ? get_option('livesmart_server_url') : '';
    echo '<div id="nd-widget-container" class="nd-widget-container"></div> 
	<script id="newdev-embed-script" data-agent_id="'.$agentId.'" data-room_id="'.$roomId.'" data-names="'.$names.'" data-avatar="'.$avatar.'" data-message="'.$message.'" data-button-css="'.$livesmart_css.'" data-source_path="' . $livesmart_server_url . '" data-iframe_id="' . $iframeId . '" src="' . $livesmart_server_url . 'js/widget.js" async></script>';
}

function ls_shortcode($atts = [], $content = null, $tag = '') {
    $names = isset($atts['names']) ? $atts['names'] : '';
    $avatar = isset($atts['avatar']) ? $atts['avatar'] : '';
    $roomId = isset($atts['roomid']) ? $atts['roomid'] : '';
    $agentId = isset($atts['agentid']) ? $atts['agentid'] : '';
    $iframeId = isset($atts['iframeid']) ? $atts['iframeid'] : '';
    ob_start();
    html_livesmart_code($names, $avatar, $roomId, $agentId, $iframeId);

    return ob_get_clean();
}

add_shortcode('livesmart_widget', 'ls_shortcode');

add_action('admin_menu', 'livesmart_plugin_settings');

add_action('livesmart_widget', 'html_livesmart_code');

function livesmart_plugin_settings() {
    add_menu_page('LiveSmart Settings', 'LiveSmart Settings', 'administrator', 'fwds_settings', 'livesmart_display_settings');
    add_submenu_page('fwds_settings', 'LiveSmart Dashboard', 'LiveSmart Dashboard',  'publish_pages', 'fwds_visitors', 'livesmart_display_dash');
}

function livesmart_display_dash() {
    $current_user = wp_get_current_user();
    $livesmart_server_url = (get_option('livesmart_server_url') != '') ? get_option('livesmart_server_url') : '';
    if ($livesmart_server_url) {
        echo '<iframe src="'.$livesmart_server_url.'admin/agent.php?wplogin='.$current_user->user_login.'&url='.base64_encode($livesmart_server_url).'" style="background-color:#ffffff; padding: 0; margin:0" width="100%" height="605" ></iframe>';
    } else {
        echo 'Please define server URL from the settings page';
    }
}


function livesmart_display_settings() {

    $livesmart_server_url = (get_option('livesmart_server_url') != '') ? get_option('livesmart_server_url') : '';
    $livesmart_css = (get_option('livesmart_css') != '') ? get_option('livesmart_css') : '';
    $message = (get_option('livesmart_front_message') != '') ? get_option('livesmart_front_message') : '';
    $names = (get_option('livesmart_names') != '') ? get_option('livesmart_names') : '';
    $avatar = (get_option('livesmart_avatar') != '') ? get_option('livesmart_avatar') : '';
    $html = '<div class="wrap">
			
            <form method="post" name="options" action="options.php">

            <h2>Select Your Settings</h2>' . wp_nonce_field('update-options') . '
            <table width="300" cellpadding="2" class="form-table">
                <tr>
                    <td align="left" scope="row">
                    <label>Server URL</label>
                    </td> 
                    <td><input type="text" style="width: 400px;" name="livesmart_server_url" 
                    value="' . $livesmart_server_url . '" /></td>
                </tr>      
                <tr>
                    <td align="left" scope="row">
                    <label>Button CSS</label>
                    </td> 
                    <td><input type="text" style="width: 400px;" name="livesmart_css" 
                    value="' . $livesmart_css . '" /></td>
                </tr>
                <tr>
                    <td align="left" scope="row">
                    <label>Button Message</label>
                    </td> 
                    <td><input type="text" style="width: 400px;" name="livesmart_front_message" 
                    value="' . $message . '" /></td>
                </tr>
                <tr>
                    <td align="left" scope="row">
                    <label>Agent Name</label>
                    </td> 
                    <td><input type="text" style="width: 400px;" name="livesmart_names" 
                    value="' . $names . '" /></td>
                </tr>
                <tr>
                    <td align="left" scope="row">
                    <label>Agent Avatar</label>
                    </td> 
                    <td><input type="text" style="width: 400px;" name="livesmart_avatar" 
                    value="' . $avatar . '" /></td>
                </tr>

            </table>
            <p class="submit">
                <input type="hidden" name="action" value="update" />  
                <input type="hidden" name="page_options" value="livesmart_names,livesmart_avatar,livesmart_server_url,livesmart_front_message,livesmart_css" /> 
                <input type="submit" name="Submit" value="Update" />
            </p>
            </form>
        </div>';
    echo $html;
}

?>