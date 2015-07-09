<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );

global $wpdb;


function royaltycart_insert_order(){
  $royaltycart_order = array(
    'post_title'    => 'Royalty Cart Order',
    'post_type'     => 'royaltycart_cart_orders',
    'post_content'  => '',
    'post_status'   => 'trash',
  );
  
  $post_id  = wp_insert_post($royaltycart_order);
}


function wpspc_insert_new_record()
{
    //First time adding to the cart
    //$cart_id = uniqid();
    //$_SESSION['simple_cart_id'] = $cart_id;
    $wpsc_order = array(
    'post_title'    => 'WPSC Cart Order',
    'post_type'     => 'wpsc_cart_orders',
    'post_content'  => '',
    'post_status'   => 'trash',
    );
    // Insert the post into the database
    $post_id  = wp_insert_post($wpsc_order);
    if($post_id){
        //echo "post id: ".$post_id;
        $_SESSION['simple_cart_id'] = $post_id;
        $updated_wpsc_order = array(
            'ID'             => $post_id,
            'post_title'    => $post_id,
            'post_type'     => 'wpsc_cart_orders',
        );
        wp_update_post($updated_wpsc_order);
        $status = "In Progress";
        update_post_meta($post_id, 'wpsc_order_status', $status);
        if(isset($_SESSION['simpleCart']) && !empty($_SESSION['simpleCart']))
        {
            update_post_meta( $post_id, 'wpsc_cart_items', $_SESSION['simpleCart']);
        }
    }
}


function wpspc_update_cart_items_record()
{
    if(isset($_SESSION['simpleCart']) && !empty($_SESSION['simpleCart']))
    {
        $post_id = $_SESSION['simple_cart_id'];
        update_post_meta( $post_id, 'wpsc_cart_items', $_SESSION['simpleCart']);
    }
}
?>