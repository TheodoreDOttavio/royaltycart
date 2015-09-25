<?php
/**
 * @package Royalty_cart
 * @version 1.0.1
 */
/*
Plugin Name: Royalty Cart
Plugin URI: https://github.com/TheodoreDOttavio/royaltycart
Description: A shopping cart for Musicians, Film Makers, and Collaborative artists to sell digital media. Payments received are divided up and sent to multiple Paypall Accounts.
Author: Ted DOttavio
Version: 1.0.1
Author URI: https://github.com/TheodoreDOttavio
*/

//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );


register_activation_hook( __FILE__, 'royaltycart_install' );
function royaltycart_install(){
	//do I need this????
  add_action( 'init', 'royaltycart_create_post_type', 0 );
	
  //For future upgrades;
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
}


register_uninstall_hook( __FILE__, 'royaltycart_drop_tables');
function royaltycart_drop_tables(){
  //remove cpt posts!
}



//Plugin-wide variables
define('ROYALTYCART_LIVE_PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr');
define('ROYALTYCART_SANDBOX_PAYPAL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');



//Add two sidebar menues for orders and products

include 'royaltycart_orders.php';
include 'royaltycart_products.php';

add_action('admin_init', 'royaltycart_add_meta_boxes');
function royaltycart_add_meta_boxes()
{
    add_meta_box( 'product_review_meta_box',
        __("Product Review"),
        'royaltycart_product_review_meta_box',
        'royaltycart_products', 
        'normal', 
        'high'
    );

    add_meta_box( 'order_review_meta_box',
        __("Order Review"),
        'royaltycart_order_review_meta_box',
        'royaltycart_orders', 
        'normal', 
        'high'
    );
}
?>