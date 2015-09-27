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


//trying to add custom stylesheets
//http://codex.wordpress.org/Function_Reference/wp_enqueue_style
//<link href='https://fonts.googleapis.com/css?family=Josefin+Sans|Righteous' rel='stylesheet' type='text/css'>

add_action( 'admin_enqueue_scripts', 'royaltycart_admin_styles' );
function royaltycart_admin_styles() {
    wp_enqueue_style( 'royaltycartStylesheet', plugins_url('stylesheets/royaltycartstyles.css', __FILE__));
    wp_enqueue_style( 'royaltycartGoogleFonts', 'https://fonts.googleapis.com/css?family=Josefin+Sans|Righteous');    
   }


//Plugin-wide variables
define('ROYALTYCART_LIVE_PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr');
define('ROYALTYCART_SANDBOX_PAYPAL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');

require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );


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