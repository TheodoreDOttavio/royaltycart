<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );


function show_royaltycart_administration_orders(){
  echo 'Show orders here';
  //add_action('admin_init', 'wp_cart_admin_init_handler'); //wpspc_add_meta_boxes()
  add_action('admin_init', 'royaltycart_add_meta_boxes');
  add_action('save_post', 'royaltycart_cart_save_orders', 10, 2 );

  add_action('manage_royaltycart_cart_orders_posts_custom_column', 'royaltycart_populate_order_columns', 10, 2);
  add_filter('manage_edit-royaltycart_cart_orders_columns', 'royaltycart_orders_display_columns' );

  //no and no... can't get the orderslist to show..yet
  //royaltycart_populate_order_columns();
  //royaltycart_orders_display_columns();
  royaltycart_order_review_meta_box();
}


function royaltycart_create_orders_page(){
  register_post_type( 'royaltycart_cart_orders',
    array(
      'labels' => array(
      'name' => "Cart Orders",
      'singular_name' => "Cart Order",
      'add_new' => "Add New",
      'add_new_item' => "Add New Order",
      'edit' => "Edit",
      'edit_item' => "Edit Order",
      'new_item' => "New Order",
      'view' => "View",
      'view_item' => "View Order",
      'search_items' => "Search Order",
      'not_found' => "No order found",
      'not_found_in_trash' => "No order found in Trash",
      'parent' => "Parent Order"
    ),
      'public' => true,
      'menu_position' => 80,
      'supports' => false,
      'taxonomies' => array( '' ),
      'menu_icon' => 'dashicons-cart',/*plugin_dir_path( __FILE__ ).'/images/cart-orders-icon.png'*/
      'has_archive' => true
    )
  );
}


function royaltycart_add_meta_boxes(){
  add_meta_box( 'order_review_meta_box',
    "Order Review",
    'royaltycart_order_review_meta_box',
    'royaltycart_cart_orders', 
    'normal', 
    'high'
  );
}


function royaltycart_order_review_meta_box($royaltycart_cart_orders){
  $order_id = $royaltycart_cart_orders->ID;
  $first_name = get_post_meta( $royaltycart_cart_orders->ID, 'royaltycart_first_name', true );
  $last_name = get_post_meta( $royaltycart_cart_orders->ID, 'royaltycart_last_name', true );
  $email = get_post_meta( $royaltycart_cart_orders->ID, 'royaltycart_email_address', true );
  $txn_id = get_post_meta( $royaltycart_cart_orders->ID, 'royaltycart_txn_id', true );
  $ip_address = get_post_meta( $royaltycart_cart_orders->ID, 'royaltycart_ipaddress', true );
  $total_amount = get_post_meta( $royaltycart_cart_orders->ID, 'royaltycart_total_amount', true );
  //$shipping_amount = get_post_meta( $wpsc_cart_orders->ID, 'wpsc_shipping_amount', true );
  //$address = get_post_meta( $wpsc_cart_orders->ID, 'wpsc_address', true );
  //$phone = get_post_meta( $wpsc_cart_orders->ID, 'wpspsc_phone', true );
  $email_sent_value = get_post_meta( $royaltycart_cart_orders->ID, 'royaltycart_buyer_email_sent', true );
    
  $email_sent_field_msg = "No";
    if(!empty($email_sent_value)){
      $email_sent_field_msg = "Yes. ".$email_sent_value;
    }
    
  $items_ordered = get_post_meta( $royaltycart_cart_orders->ID, 'royaltycart_items_ordered', true );
  //$applied_coupon = get_post_meta( $wpsc_cart_orders->ID, 'wpsc_applied_coupon', true );
  ?>
    <table>
        <p>Order ID: #<?php echo $order_id;?></p>
        <?php if($txn_id){?>
        <p>Transaction ID: #<?php echo $txn_id;?></p>
        <?php } ?>
        <tr>
            <td>First Name</td>
            <td><input type="text" size="40" name="royaltycart_first_name" value="<?php echo $first_name; ?>" /></td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td><input type="text" size="40" name="royaltycart_last_name" value="<?php echo $last_name; ?>" /></td>
        </tr>
        <tr>
            <td>Phone</td>
            <td><input type="text" size="40" name="royaltycart_phone" value="<?php echo $phone; ?>" /></td>
        </tr>
        <tr>
            <td>Email Address</td>
            <td><input type="text" size="40" name="royaltycart_email_address" value="<?php echo $email; ?>" /></td>
        </tr>
        <tr>
            <td>IP Address</td>
            <td><input type="text" size="40" name="royaltycart_ipaddress" value="<?php echo $ip_address; ?>" /></td>
        </tr>
        <tr>
            <td>Address</td>
            <td><textarea name="royaltycart_address" cols="85" rows="2"><?php echo $address;?></textarea></td>
        </tr>
        <tr>
            <td>Total</td>
            <td><input type="text" size="20" name="royaltycart_total_amount" value="<?php echo $total_amount; ?>" /></td>
        </tr>
        <tr>
            <td>Item(s) Ordered:</td>
            <td><textarea name="royaltycart_items_ordered" cols="85" rows="5"><?php echo $items_ordered;?></textarea></td>
        </tr>
        <tr>
            <td>Buyer Email Sent?</td>
            <td><input type="text" size="20" name="royaltycart_buyer_email_sent" value="<?php echo $email_sent_field_msg; ?>" readonly /></td>
        </tr>
    </table>
    <?php
}


function royaltycart_cart_save_orders( $order_id, $royaltycart_cart_orders ) {
    if ( $royaltycart_cart_orders->post_type == 'royaltycart_cart_orders' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['royaltycart_first_name'] ) && $_POST['royaltycart_first_name'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_first_name', $_POST['royaltycart_first_name'] );
        }
        if ( isset( $_POST['royaltycart_last_name'] ) && $_POST['royaltycart_last_name'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_last_name', $_POST['royaltycart_last_name'] );
        }
        if ( isset( $_POST['royaltycart_email_address'] ) && $_POST['royaltycart_email_address'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_email_address', $_POST['royaltycart_email_address'] );
        }
        if ( isset( $_POST['royaltycart_ipaddress'] ) && $_POST['royaltycart_ipaddress'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_ipaddress', $_POST['royaltycart_ipaddress'] );
        }
        if ( isset( $_POST['royaltycart_total_amount'] ) && $_POST['royaltycart_total_amount'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_total_amount', $_POST['royaltycart_total_amount'] );
        }
        //if ( isset( $_POST['royaltycart_shipping_amount'] ) && $_POST['royaltycart_shipping_amount'] != '' ) {
        //    update_post_meta( $order_id, 'royaltycart_shipping_amount', $_POST['royaltycart_shipping_amount'] );
        //}
        if ( isset( $_POST['royaltycart_address'] ) && $_POST['royaltycart_address'] != '' ) {
            update_post_meta( $order_id, 'royaltycart_address', $_POST['royaltycart_address'] );
        }
        if ( isset( $_POST['wpspsc_phone'] ) && $_POST['wpspsc_phone'] != '' ) {
            update_post_meta( $order_id, 'wpspsc_phone', $_POST['wpspsc_phone'] );
        }
        if ( isset( $_POST['wpspsc_items_ordered'] ) && $_POST['wpspsc_items_ordered'] != '' ) {
            update_post_meta( $order_id, 'wpspsc_items_ordered', $_POST['wpspsc_items_ordered'] );
        }
    }
}


//add_filter( 'manage_edit-royaltycart_cart_orders_columns', 'royaltycart_orders_display_columns' );
function royaltycart_orders_display_columns( $columns ) 
{
    unset( $columns['comments'] );
    unset( $columns['date'] );
    $columns['title'] = "Order ID";
    $columns['royaltycart_first_name'] = "First Name";
    $columns['royaltycart_last_name'] = "Last Name";
    $columns['royaltycart_email_address'] = "Email";
    $columns['royaltycart_total_amount'] = "Total";
    $columns['royaltycart_order_status'] = "Status";
    $columns['date'] = "Date";
    return $columns;
}


//add_action('manage_royaltycart_cart_orders_posts_custom_column', 'royaltycart_populate_order_columns', 10, 2);
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
    else if ( 'royaltycart_email_address' == $column ) {
        $email = get_post_meta( $post_id, 'royaltycart_email_address', true );
        echo $email;
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


function royaltycart_customize_order_link( $permalink, $post ) {
    if( $post->post_type == 'royaltycart_cart_orders' ) {
        $permalink = get_admin_url().'post.php?post='.$post->ID.'&action=edit';
    }
    return $permalink;
}
add_filter('post_type_link',"royaltycart_customize_order_link",10,2);
?>