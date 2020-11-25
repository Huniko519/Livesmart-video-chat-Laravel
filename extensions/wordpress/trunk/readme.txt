=== LiveSmart Video Chat Live Video Chat ===
Contributors: nhadjidimitrov
Author URI: https://www.new-dev.com/page/ident/live_smart_video_chat
Tags: webrtc, video conference, audio, chat, call
Requires at least: 2.9+
Tested up to: 4.2
Stable tag: 1.2

LiveSmart Video Chat Live Video chat plugin for WordPress that allows visitors to establish live video chat in the browser without download. 

== Description ==

The plugin is developed by LiveSmart Video Chat Inc., whose mission is to bring face-to-face communication online. Host your own live video/chat sessions, interact with your visitors and boost your sales. 

LiveSmart Video Chat plugin lets you embed ‘Video Chat' button, online and offline forms on your website to let your website visitors click to start a video or regular chat with you directly. Visitors can also make an audio or video call directly from their web browser.

Before installing this plugin make sure you have installed LiveSmart Video Chat on your server. You can acquire it from CodeCanyon.

You can use LiveSmart Video Chat to:

- Live video and/or audio chat;
- Own notification server, no third party software or accounts needed;
- File transfer;
- Pure HTML5 Web chat;
- Screen sharing. Supported on Chrome with additional plugin (Your will be guided in the installation process), Firefox 52+ and MS Edge 17+ browsers;
- Switch between front and back camera of your mobile;
- Notification system;
- Mobile friendly;
- Button for establishing a direct connection and a facility to track the presence of your visitors;
- Predefined buttons styling;
- Internationalization;
- Ability to set own video chat room;

Integrate With Your Theme

The LiveSmart Video Chat Plugin gives you the option to customize your button with a text of your own choice. Get exactly the look and feel you want.

= Options =

The following options are customizable:

* message on the LiveSmart Video Chat button
* CSS for the look and feel of the button
* Room ID

= Reference =

Overview, user guide and installation instructions: 
[https://www.new-dev.com/page/ident/live_smart_video_chat](https://www.new-dev.com/page/ident/live_smart_video_chat "https://www.new-dev.com/page/ident/live_smart_video_chat")
Live Smart in CodeCanyon:
[https://codecanyon.net/item/livesmart-video-chat/23122332](https://codecanyon.net/item/livesmart-video-chat/23122332 "https://codecanyon.net/item/livesmart-video-chat/23122332")

== Frequently Asked Questions ==

= What browser does LiveSmart Video Chat support? =

LiveSmart Video Chat is supported on all major browsers:

– Desktop PC. Microsoft Edge 12+, Google Chrome 28+, Mozilla Firefox 22+, Safari 11+, Opera 18+, Vivaldi 1.9+, IE 11 (Needs installation of Temasys plugin. You will be guided through the installation process)
– Android. Google Chrome 28+ (enabled by default since 29), Mozilla Firefox 24+, Opera Mobile 12+
– Chrome OS.
– Firefox OS.
– Blackberry 10.
– at least iOS 11. MobileSafari/Mac Safari. For legacy Safari (older than v.11) you will be prompted to install Temasys plugin.

= What devices does LiveSmart Video Chat work with? =

We're available on Android Smartphone, Android Tablet, iPad, iPhone, Mac, and PC. 

= How much does it cost? =

The solution is currently free!

= Do I have to install any hardware/software on my premises to use this widget? =

LiveSmart Video Chat is a hosted, cloud based live video communication service. It does not require you to install any additional hardware or software. Simply configure the plugin to suit your preferences and you should be up, and running in minutes.

= I want to provide feedback! =

We love feedback! Feel free to drop us a line at info@new-dev.com for the things you do like or you don't, additional features you would like to see or just to say hi. We will make our best to take them into consideration! Thanks

= My question is not answered here! =

If you have any questions, please send us an email - info@new-dev.com


== Installation ==

How should I setup LiveSmart Video Chat's Wordpress live video button for my site?
 
Just follow the steps listed below. If at any point you have troubles with the installation don't hesitate to contact us - we would love to help you out:

1/ Install and activate the plugin

2/ Once you active the plugin on your left panel you will be able to see LiveSmart Video Chat's plugin settings. Click on them.

3/ Enter the following information in the form:

a/ Server URL : fill in your server URL.

b/ Button CSS - this is the message that appears on our button;

c/ Button Message - The label on the button. Default is "Start Video Chat". We recommend it to be shorter e.g. Video call me or Video Call George. You can change the front message as you like;

d/ room ID - (optional) this is the private roomId of the agent. When you generate a link, this is the string after room attribute. For example if the link is something like this: pages/room.html?room=2bsdhnz0lvs&isAdmin=1 - copy/paste 2bsdhnz0lvs in the shortcode, [livesmart_widget roomId="2bsdhnz0lvs"];

e/ names - (optional) names of the agent, that is receiving the calls. You can set this in the Settings page, or in the shortcode as a parameter, for example [livesmart_widget names="NAME_OF_AGENT"] where NAME_OF_AGENT should be the actual name.

f/ avatar - (optional) URL with an image of the agent. You can set this in the Settings page, or in the shortcode as a parameter, for example [livesmart_widget avatar="URL_OF_AGENT_AVATAR"] where URL_OF_AGENT_AVATAR should be a valid URL of an image of the agent.

4/ LiveSmart Video Chat button can be positioned on any page you would like:

a/ For a single page - Go to Pages menu on the left panel --> Click the page where you would like to see the button for example About Us --> Click the Classic Editor (if you are using Page Composer) ---> Text --> Place [livesmart_widget]. If you want to see the button below your picture, find the corresponding alt tag and place [livesmart_widget] below

b/ For multiple pages e.g.menu, the header, the footer, etc. - Go to Appearance menu on the left panel --> Click Editor --> Open the necessary PHP page e.g. header.php --> Place the following WordPress hook `<?php do_action('livesmart_widget'); ?>`

5/ After you have set correctly LiveSmart Video Chat and your Database, you can visit the Dashboard menu. There you can track your visitors, add/change agents, add rooms and regular visitors.
The LiveSmart Wordpress plugin is available for WP users with capabilities to add pages. In order to login directly to Dashboard, make sure your WP user has the same username as the LiveSmart agent. Default LSV agent has credentials of admin/admin.

6/ Enjoy!

Thanks for using LiveSmart Video Chat :) 

P.S. If you have any troubles with installation feel free do drop us a line at info@new-dev.com

== Changelog ==

= 1.2 =
* Added Dashboard menu

= 1.1 =
* Plugin tested up to WordPress 4.2

= 1.0 =
* Initial release
