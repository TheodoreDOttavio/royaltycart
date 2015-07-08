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

//Actions here, Functions below...
add_action('admin_menu', 'royaltycart_administration_actions');


function royaltycart_install()
{
    global $wpdb;
    $table = $wpdb->prefix."royaltycart_products";
    $structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
        royaltycart_product_name VARCHAR(80) NOT NULL,
        royaltycart_product_file VARCHAR(20) NOT NULL,
        royaltycart_product_royalty_array VARCHAR(80) NOT NULL,
	    UNIQUE KEY id (id)
    ) $charset_collate;";
	
	//For future upgrades;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
    dbDelta( $structure );

    //from 2007 article - $wpdb->query($structure);
	
    //Table Notes:
    //  An entry for each item because aach Product has its own royalty split
    //  The filename is the wp-direct URL. The goal here is to send an encoded link, and decode it on download
    //    The download encode/decode and a number of attempted downloads will be in an Orders table
    //  The royalty array is an array of ->Paypall account ->recipient's name for display ->percentatage or base amount
    
    // Populate table
    $wpdb->insert( $table, 
      array( 
        'royaltycart_product_name' => 'Sample', 
        'royaltycart_product_file' => 'myfile url', 
        'royaltycart_product_royalty_array' => '[teddottavio@gmail.com,Ted,0.01]',
	  ) 
    );
}


register_activation_hook( __FILE__, 'royaltycart_install' );
//register_uninstall_hook()


function royaltycart_administration_actions(){
  add_options_page("Royalty Cart", "Royalty Cart", 1, "Royalty-Cart", "royaltycart_menu");
}


function royaltycart_menu(){
	//initiates the menue and includes the Admin options
  global $wpdb;
  include 'royaltycart-administration.php';
}
?>