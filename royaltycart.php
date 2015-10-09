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
  add_action( 'init', 'royaltycart_create_post_type', 0 );
	
  //For future upgrades;
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
}


register_uninstall_hook( __FILE__, 'royaltycart_purge');
function royaltycart_purge(){
  //remove cpt posts!
}


//Custom stylesheets
add_action( 'admin_enqueue_scripts', 'royaltycart_admin_scripts' );
function royaltycart_admin_scripts() {
    wp_enqueue_style( 'royaltycartStylesheet', plugins_url('stylesheets/royaltycartstyles.css', __FILE__));
    wp_enqueue_style( 'royaltycartGoogleFonts', 'https://fonts.googleapis.com/css?family=Josefin+Sans|Molengo');
	
	//https://wordpress.org/support/topic/howto-integrate-the-media-library-into-a-plugin?replies=4
	//5yr old post. didn't work out, but leaving the java in...
	//wp_enqueue_script(plugins_url('scripts/royaltycartjava.js', __FILE__));
   }

//multipart form is needed for file uploads
add_action('post_edit_form_tag', 'royaltycart_add_edit_form_multipart_encoding');
function royaltycart_add_edit_form_multipart_encoding() {
    echo ' enctype="multipart/form-data"';
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


//tools and functions

function royaltycart_messagearray_set($messagearray, $msgkey, $msgvalue){
  $newmessagearray = array();
  //maintain current array without the incoming key
  if (!empty($messagearray)){
  foreach ($messagearray as $key => $value){
    if ($msgkey != $key) {
      if ($value != "" ){
        $newmessagearray[$key]=$value;
      }
    }
  }
  }
  //add the incoming key
  $newmessagearray[$msgkey]=$msgvalue;
  return $newmessagearray;
}


function array_sort($array, $on, $order=SORT_ASC){
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }
        
        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }
    return $new_array;
}


function royaltycart_remainder_percent($payoutlist){
	//add up percents - skip remainder
	$totalpercentpaidout = 0;
	foreach($payoutlist as $payout){
		if ($payout['remainder'] == 0){
			if ($payout['percent'] == 1){
				$totalpercentpaidout = $totalpercentpaidout + $payout['value'];
			}
		}
	}
	return 100-$totalpercentpaidout;
}


function royaltycart_process_payouts($payoutlist, $received, $errorcheck){
  //Prepare a list of arrays - Min is $0.02
  //Deduct $ amounts before percentages, then give the remainder to one name
	//payments array  - determines all the people that get paid and how much.
    //  ['recipient1']=>Sub array;
    //    ['amount']=>60: base amount or percentage value: 2.5 or 10 for $2.50 or 10%
    //    ['payee']=>'teddottavio@gmail.com': Recipients account (email for paypal)
    //    ['payee_name']=>'Ted DOttavio': Name for display
    //    ['comment_role']=>'Producer': Recipients title or role
    //  ['recipient2']=>
  $totalgoingout = 0;
  $fee = 0.30;

  //Cash deductions
  foreach ($payoutlist as $payout){
  	if ($payout['remainder'] == 0){
  	  if ($payout['percent'] == 0){
  	    $amount = $payout['value'];
        if ($amount < .02 && $amount != 0){$amount = 0.02;}
  	  	
  	  	//deduct from amount
  	  	$totalgoingout += $amount;
  	  	
  	  	//set the amount into resulting array
  	  	if ($payout['value'] >0){
  	  	$newpayee = array(
  	  	  'amount' => $amount,
  	  	  'payee' => $payout['payee'],
  	  	  'payee_name' => $payout['payee_name'],
  	  	  'comment_role' => $payout['comment_role']
  	  	);
   
  	  	$payments['recipient'.$payout['rclistindex']] = $newpayee;
		}
	  }
	}
  }
  
  //percent deductions
  foreach ($payoutlist as $payout){
  	if ($payout['remainder'] == 0){
  	  if ($payout['percent'] == 1){
  	  	$amount = (.01 * $payout['value']) * $received;
  	  	if ($amount < .02 && $amount != 0){$amount = 0.02;}
		
  	  	//to deduct from amount
  	  	$totalgoingout += $amount;
		
  	  	//set the amount into resulting array
  	  	if ($payout['value'] >0){
  	  	$newpayee = array(
  	  	  'amount' => $amount,
  	  	  'payee' => $payout['payee'],
  	  	  'payee_name' => $payout['payee_name'],
  	  	  'comment_role' => $payout['comment_role']
  	  	);
   
  	  	$payments['recipient'.$payout['rclistindex']] = $newpayee;
		}
	  }
	}
  }
  
  //now go through and find the primary recipient
  foreach ($payoutlist as $payout){
  	if ($payout['remainder'] == 1){
  	  	//set the amount into resulting array
		$fee += round((($received - $totalgoingout) * 0.029), 2);
		
  	  	$newpayee = array(
  	  	  'amount' => $received - ($totalgoingout + $fee),
  	  	  'payee' => $payout['payee'],
  	  	  'payee_name' => $payout['payee_name'],
  	  	  'comment_role' => $payout['comment_role']
  	  	);
		$newfee = array(
  	  	  'amount' => $fee,
  	  	  'payee' => 'Transaction Fee',
  	  	  'payee_name' => 'Paypal',
  	  	  'comment_role' => 'Transaction Fee'
  	  	);
  	  	$payments['recipient'.$payout['rclistindex']] = $newpayee;
		$payments['fees'.$payout['rclistindex']] = $newfee;
	  }

  }
  
  $payments = array_sort($payments, 'amount', SORT_DESC);
  
  //return list or return an error report
  if ($errorcheck == 0){
    return $payments;
  }else{
    if (($received - $totalgoingout) < 0 ){
      return 1;
    }else{
      return 0;
    }
  }
}
?>