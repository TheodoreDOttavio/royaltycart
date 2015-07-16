<?php
/**
 * @package Royalty_cart
 * @version 1.0
 */
/*
Plugin Name: Royalty Cart
Plugin URI: https://github.com/TheodoreDOttavio/royaltycart
Description: A shopping cart for Musicians, Film Makers, and Collaborative artists to sell digital media. Payments received are divided up and sent to multiple Paypall Accounts.
Author: Ted DOttavio
Version: 1.0
Author URI: https://github.com/TheodoreDOttavio
*/

//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );

//Plugin-wide variables
define('ROYALTYCART_LIVE_PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr');
define('ROYALTYCART_SANDBOX_PAYPAL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');

include 'royaltycart_functions.php';

include 'royaltycart_orders.php';
include 'royaltycart_products.php';

function royaltycart_install(){
	//do I need this????
  add_action( 'init', 'royaltycart_create_post_type', 0 );
	
  //For future upgrades;
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
}

register_activation_hook( __FILE__, 'royaltycart_install' );
register_uninstall_hook( __FILE__, 'royaltycart_drop_tables');


add_action('admin_init', 'royaltycart_add_meta_boxes');

add_action('admin_menu', 'royaltycart_administration_actions');
function royaltycart_administration_actions(){
  if ( is_admin() ) {
    // We are in admin mode
    
    //One menue item with tabs
    //add_menu_page (
    //  "Royalty Cart",
    //  "Royalty Cart",
    //  "manage_options",
    //  "Royalty-Cart",
    //  "royaltycart_menu",
    //  plugin_dir_url( __FILE__ ).'images/cart-orders-icon.png'
    //);
	
    //This adds the menu in the settings panel
    add_options_page("Royalty Cart", "Royalty Cart", 1, "Royalty-Cart", "royaltycart_menu");
  }
}

function royaltycart_menu(){
  //initiates the Admin menue
  include 'administration/royaltycart-administration.php';
}


?>