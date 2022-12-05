<?php

/*
   Plugin Name: Version Management
   Plugin URI: https://sandhya.com/
   description: To customize your version
   Version: 1.0.0
   Author: Sandhya Viswanathan
   Author URI: https://sandhya.com/
*/

function customplugin_menu() {
	add_menu_page("Managment", "Version Management","read", "news_listing", "getVersion",plugins_url(''));
	add_submenu_page("news_listing","All Entries", "Version","read", "news_listing", "getVersion");
	
}
add_action("admin_menu", "customplugin_menu");
 
function getVersion(){	
	include "versionPreview.php";
}
?>