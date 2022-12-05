<?php

/*
   Plugin Name: Product Data Import
   Plugin URI: https://sinnonteq.com/
   description: To import product data
   Version: 1.0.0
   Author: Sinnonteq
   Author URI: https://sinnonteq.com/
*/

function customplugin_menulist() {

    add_menu_page("Managment", "Product Import","manage_options", "product_import", "displayList2",plugins_url(''));
    add_submenu_page("product_import","All Entries2", "Product Import","manage_options", "product_import", "getLocationData"); 
    
	


}
add_action("admin_menu", "customplugin_menulist");
//add_shortcode('product_Mapping', 'getLocationData');   
 
function getLocationData(){
  include "product_mapping.php"; 
}

?>