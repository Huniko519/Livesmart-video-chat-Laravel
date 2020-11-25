Steps to integrate LiveSmart WP plugin:

1. Installation

- Make sure you have LiveSmart Video Chat installed. 
If you do not have it, please visit https://codecanyon.net/item/livesmart-video-chat/23122332
Installation details can be found here https://www.new-dev.com/page/ident/live_smart_video_chat_installation
- Add the file livesmart-plugin.php to wp-content/plugins folder. The plug-in will appear in the WordPress admin panel under Plugins section. 
- Activate the plug-in.

2. Setup

After the activation, a new link appears in the left menu - LiveSmart Settings.

- Server URL - fill in your server URL.
- Button CSS - this is the message that appears on our button.
- Button Message - The label on the button. Default is "Start Video Chat".
- Agent Name - Name of the agent.
- Agent Avatar - URL of an image of the agent.

3. WordPress site integration

Use one of the two options to integrate our widget in your site:

- It can be put on a single page. 
From the Pages section, edit the content of the page and place the tag [livesmart_widget]
- It can be set up in the menu, the header, the footer, etc. Open the necessary PHP page for ex. header.php, footer.php, from Appearance - Editor and place the following WordPress hook <?php do_action('livesmart_widget'); ?>

4. LiveSmart Dashboard

- After you set the correct settings and your DB is set and installed, you can visit LiveSmart Dashboard in the menu. 