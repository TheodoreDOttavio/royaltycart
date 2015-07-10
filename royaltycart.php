<?php
/**
 * @package Royalty_cart
 * @version 1.0
 */
/*
Plugin Name: Royalty Cart
Plugin URI: https://github.com/TheodoreDOttavio/royaltycart
Description: A shopping cart for Musicians, Film Makers, and Collaborative artists to digital media. Payments received are divided up and sent to multiple Paypall Accounts.
Author: Ted DOttavio
Version: 1.0
Author URI: https://github.com/TheodoreDOttavio
*/

//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );

//Plugin-wide variables
define('ROYALTYCART_LIVE_PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr');
define('ROYALTYCART_SANDBOX_PAYPAL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');

include 'royaltycart-functions.php';

function royaltycart_install(){
  add_action( 'init', 'royaltycart_create_post_type', 0 );
	
  //For future upgrades;
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  
    global $wpdb;
    $table = $wpdb->prefix."royaltycart_products";
    $structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
        royaltycart_product_name VARCHAR(80) NOT NULL,
        royaltycart_product_file VARCHAR(20) NOT NULL,
        royaltycart_product_royalty_array VARCHAR(80) NOT NULL,
	    UNIQUE KEY id (id)
    ) $charset_collate;";
	
    dbDelta( $structure );

    //from 2007 article - $wpdb->query($structure);
	
    //Table Notes:
    //  An entry for each item because aach Product has its own royalty split
    //  The filename is the wp-direct URL. The goal here is to send an encoded link, and decode it on download
    //    The download encode/decode and a number of attempted downloads will be in an Orders table
    //  The royalty array is an array of ->Paypall account ->recipient's name for display ->percentatage or base amount
    
    // Populate tables
    $wpdb->insert( $table, 
      array( 
        'royaltycart_product_name' => 'Sample after CPT', 
        'royaltycart_product_file' => 'myfile url', 
        'royaltycart_product_royalty_array' => '[teddottavio@gmail.com,Ted,0.01]',
	  ) 
    );
    
    //add an empty order
    //royaltycart_insert_order();
}


register_activation_hook( __FILE__, 'royaltycart_install' );
register_uninstall_hook( __FILE__, 'royaltycart_drop_tables');


//Registers the Orders post type
function royaltycart_create_post_type() {
  $labels = array(
	'name' => _x( 'Royaly Cart Orders', 'Post Type General Name' ),
	'singular_name' => _x( 'Royaly Cart Order', 'Post Type Singular Name' ),
	'add_new' => __( 'Add New' ),
	'add_new_item' => __( 'Add New Order' ),
	'edit' => __( 'Edit' ),
	'edit_item' => __( 'Edit Order' ),
	'new_item' => __( 'New Order' ),
	'view' => __( 'View Order' ),
	'view_item' => __( 'View Order' ),
	'search_items' => __( 'Search Orders' ),
	'not_found' => __( 'No Orders found' ),
	'not_found_in_trash' => __( 'No Orders found in Trash' ),
	'parent' => __( 'Parent Order' ),
  );

  $args = array(
    'label' => __( 'royaltycart-orders' ),
    'description' => __( 'Royalty Cart Orders' ),
    'labels' => $labels,
    'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
    'taxonomies' => array( 'genres' ),
    'hierarchical' => false,
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'show_in_admin_bar' => true,
    'menu_position' => 5,
    'can_export' => true,
    'has_archive' => true,
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'capability_type' => 'page',
  );

  register_post_type( 'royaltycart-orders', $args );
}
add_action( 'init', 'royaltycart_create_post_type', 0 );


add_action('admin_menu', 'royaltycart_administration_actions');
function royaltycart_administration_actions(){
  //One menue item, 
  //  I'm using the tabs on the admin page 
  //  to handle the subsections because that's the kind of guy I am. 
  if ( is_admin() ) {
    // We are in admin mode
    add_menu_page (
      "Royalty Cart",
      "Royalty Cart",
      "manage_options",
      "Royalty-Cart",
      "royaltycart_menu",
      plugin_dir_url( __FILE__ ).'images/cart-orders-icon.png'
    );
  }  
  
  //This adds the menu in the settings panel
  //add_options_page("Royalty Cart", "Royalty Cart", 1, "Royalty-Cart", "royaltycart_menu");
}


function royaltycart_menu(){
  //initiates the menue and includes the Admin options
  //global $wpdb;
  include 'administration/royaltycart-administration.php';
}
?>