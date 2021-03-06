<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );

add_action( 'save_post', 'royaltycart_cart_save_orders', 10, 2 );



//Registers the Orders post type
add_action( 'init', 'royaltycart_create_orders_custom_post_type', 0 );
function royaltycart_create_orders_custom_post_type() {
  $labels = array(
	'name' => _x( 'Royalties Orders', 'Post Type General Name' ),
	'singular_name' => _x( 'Royalties Order', 'Post Type Singular Name' ),
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
    'label' => __( 'royaltycart_orders' ),
    'description' => __( 'Royalty Cart Orders' ),
    'labels' => $labels,
    'supports' => false, //array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
    'taxonomies' => array( 'genres' ),
    'hierarchical' => false,
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'show_in_admin_bar' => true,
    'menu_position' => 6,
    'menu_icon' => plugin_dir_url( __FILE__ ).'images/cart-orders-icon.png',
    'can_export' => true,
    'has_archive' => true,
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'capability_type' => 'page',
  );
  
  $args = array(
    'label' => __( 'royaltycart_orders' ),
    'description' => __( 'Royalty Cart Orders' ),
    'labels' => $labels,
    'public' => true,
    'menu_position' => 95,
    'supports' => false,
    'taxonomies' => array( '' ),
    'menu_icon' => plugin_dir_url( __FILE__ ).'images/cart-orders-icon.png',
    'has_archive' => true
  );

  register_post_type( 'royaltycart_orders', $args );
  
}



function royaltycart_order_review_meta_box($royaltycart_orders){
  $order_id = $royaltycart_orders->ID;
  $first_name = get_post_meta( $royaltycart_orders->ID, 'royaltycart_first_name', true );
  $last_name = get_post_meta( $royaltycart_orders->ID, 'royaltycart_last_name', true );
  $phone = get_post_meta( $royaltycart_orders->ID, 'royaltycart_phone', true );
  $email = get_post_meta( $royaltycart_orders->ID, 'royaltycart_email', true );
  $txn_id = get_post_meta( $royaltycart_orders->ID, 'royaltycart_txn_id', true );
  $ip_address = get_post_meta( $royaltycart_orders->ID, 'royaltycart_ipaddress', true );
  $address = get_post_meta( $royaltycart_orders->ID, 'royaltycart_address', true );
  $total_amount = get_post_meta( $royaltycart_orders->ID, 'royaltycart_total_amount', true );
  $email_sent_value = get_post_meta( $royaltycart_orders->ID, 'royaltycart_buyer_email_sent', true );
    
  $email_sent_field_msg = "No";
    if(!empty($email_sent_value)){
      $email_sent_field_msg = "Yes. ".$email_sent_value;
    }
    
  $items_ordered = get_post_meta( $royaltycart_orders->ID, 'royaltycart_items_ordered', true );
  
  //DISPLAY
  include 'royaltycart_order_view.php';
}



function royaltycart_cart_save_orders( $order_id, $royaltycart_orders ) {
    if ( $royaltycart_orders->post_type == 'royaltycart_orders' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['royaltycart_first_name'] ) && $_POST['royaltycart_first_name'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_first_name', $_POST['royaltycart_first_name'] );
        }
        if ( isset( $_POST['royaltycart_last_name'] ) && $_POST['royaltycart_last_name'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_last_name', $_POST['royaltycart_last_name'] );
        }
		if ( isset( $_POST['royaltycart_phone'] ) && $_POST['royaltycart_phone'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_phone', $_POST['royaltycart_phone'] );
        }
        if ( isset( $_POST['royaltycart_email'] ) && $_POST['royaltycart_email'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_email', $_POST['royaltycart_email'] );
        }
        if ( isset( $_POST['royaltycart_ipaddress'] ) && $_POST['royaltycart_ipaddress'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_ipaddress', $_POST['royaltycart_ipaddress'] );
        }
		if ( isset( $_POST['royaltycart_address'] ) && $_POST['royaltycart_address'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_address', $_POST['royaltycart_address'] );
        }
        if ( isset( $_POST['royaltycart_total_amount'] ) && $_POST['royaltycart_total_amount'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_total_amount', $_POST['royaltycart_total_amount'] );
        }
        if ( isset( $_POST['royaltycart_items_ordered'] ) && $_POST['royaltycart_items_ordered'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_items_ordered', $_POST['royaltycart_items_ordered'] );
        }
    }
}



add_filter( 'manage_edit-royaltycart_orders_columns', 'royaltycart_orders_display_columns' );
function royaltycart_orders_display_columns( $columns ) 
{
    unset( $columns['comments'] );
    unset( $columns['date'] );
    $columns['title'] = "Order ID";
    $columns['royaltycart_first_name'] = "First Name";
    $columns['royaltycart_last_name'] = "Last Name";
    $columns['royaltycart_phone'] = "Phone";
	$columns['royaltycart_email'] = "Email";
	$columns['royaltycart_address'] = "Address";
    $columns['royaltycart_total_amount'] = "Total";
    $columns['royaltycart_order_status'] = "Status";
    $columns['date'] = "Date";
    return $columns;
}



add_action('manage_royaltycart_orders_posts_custom_column', 'royaltycart_populate_order_columns', 10, 2);
function royaltycart_populate_order_columns($column, $post_id)
{
    if ( 'royaltycart_first_name' == $column ) {
        $ip_address = get_post_meta( $post_id, 'royaltycart_first_name', true );
        echo $ip_address;
    }
    else if ( 'royaltycart_last_name' == $column ) {
        $ip_address = get_post_meta( $post_id, 'royaltycart_last_name', true );
        echo $ip_address;
    }
    else if ( 'royaltycart_phone' == $column ) {
        $phone = get_post_meta( $post_id, 'royaltycart_phone', true );
        echo $phone;
    }
    else if ( 'royaltycart_email' == $column ) {
        $email = get_post_meta( $post_id, 'royaltycart_email', true );
        echo $email;
    }
    else if ( 'royaltycart_address' == $column ) {
        $address = get_post_meta( $post_id, 'royaltycart_address', true );
        echo $address;
    }
    else if ( 'royaltycart_total_amount' == $column ) {
        $total_amount = get_post_meta( $post_id, 'royaltycart_total_amount', true );
        echo $total_amount;
    }
    else if ( 'royaltycart_order_status' == $column ) {
        $status = get_post_meta( $post_id, 'royaltycart_order_status', true );
        echo $status;
    }
}



add_filter('post_type_link',"royaltycart_customize_order_link",10,2);
function royaltycart_customize_order_link( $permalink, $post ) {
    if( $post->post_type == 'royaltycart_orders' ) {
        $permalink = get_admin_url().'post.php?post='.$post->ID.'&action=edit';
    }
    return $permalink;
}

//something that might be useful
//// Send email to admin.
//    wp_mail( 'admin@example.com', $subject, $message );
?>