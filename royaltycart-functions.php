<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );

global $wpdb;



	
function royaltycart_insert_order(){
  $royaltycart_order = array(
    'post_title' => 'Royalty Cart Order',
    'post_type' => 'royaltycart_orders',
    'post_content' => '',
    'post_status' => 'trash',
  );
  
  $post_id  = wp_insert_post($royaltycart_order);
  
  if($post_id){
  	//$_SESSION['royaltycart_cart_id'] = $post_id;
    $updated_royaltycart_order = array(
      'ID' => $post_id,
      'post_title' => $post_id,
      'post_type' => 'royaltycart_orders',
    );
	wp_update_post($updated_wpsc_order);
    update_post_meta($post_id, 'royaltycart_order_status', "In Progress");
    
    //if(isset($_SESSION['simpleCart']) && !empty($_SESSION['simpleCart'])){
    //   update_post_meta( $post_id, 'wpsc_cart_items', $_SESSION['simpleCart']);
    //}
  }
}


function royaltycart_drop_tables(){
  global $wpdb;
  $table = $wpdb->prefix."royaltycart_products";
  $structure = "DROP TABLE $table;";
	
  dbDelta( $structure );
}
?>