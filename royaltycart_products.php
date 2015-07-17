<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );

add_action( 'save_post', 'royaltycart_cart_save_products', 10, 2 );



//Registers the Products post type
add_action( 'init', 'royaltycart_create_products_custom_post_type', 0 );
function royaltycart_create_products_custom_post_type() {
  $labels = array(
	'name' => _x( 'Royalties Products', 'Post Type General Name' ),
	'singular_name' => _x( 'Royalties Product', 'Post Type Singular Name' ),
	'add_new' => __( 'Add New' ),
	'add_new_item' => __( 'Add New Product' ),
	'edit' => __( 'Edit' ),
	'edit_item' => __( 'Edit Product' ),
	'new_item' => __( 'New Product' ),
	'view' => __( 'View Product' ),
	'view_item' => __( 'View Product' ),
	'search_items' => __( 'Search Products' ),
	'not_found' => __( 'No Products found' ),
	'not_found_in_trash' => __( 'No Products found in Trash' ),
	'parent' => __( 'Parent Product' ),
  );

  $args = array(
    'label' => __( 'royaltycart_products' ),
    'description' => __( 'Royalty Cart Products' ),
    'labels' => $labels,
    'public' => true,
    'menu_position' => 96,
    'supports' => false,
    'taxonomies' => array( '' ),
    'menu_icon' => plugin_dir_url( __FILE__ ).'images/cart-orders-icon.png',
    'has_archive' => true
  );

  register_post_type( 'royaltycart_products', $args );
}



function royaltycart_product_review_meta_box($royaltycart_products){
  $product_id = $royaltycart_products->ID;
  $product_name = get_post_meta( $royaltycart_products->ID, 'royaltycart_product_name', true );
  $basefile = get_post_meta( $royaltycart_products->ID, 'royaltycart_basefile', true );
  //fileformats is an array for checkboxes
  $fileformats = get_post_meta( $royaltycart_products->ID, 'royaltycart_fileformats', true );
  //payout array
  //  [type]=>boolean: 0 sets value as base amount, 1 sets value as percentage
  //  [value]=>Number: base amount or percentage value: 2.5 or 10 for $2.50 or 10%
  //  [Payee]=>Recipients accout (email for paypal)
  //  [Payee_name]=>Name for display
  //  [Comment_role]=>Recipients title or role
  //  [comments]=>Additional comments abut payee
  $payout = get_post_meta( $royaltycart_products->ID, 'royaltycart_payout', true );
  
  ?>
    <table>
    	<tr><td colspan='2' align='center'>
            Use this shortcode for an 'add to cart' button:
        </td></tr>
    	<tr><td colspan='2' align='center'>
            <p style="background-color: #DDDDDD; padding: 5px; display: inline;">[royaltycart_purchase id=<?php echo $product_id;?>]</p>
        </td></tr>
        
        <tr>
            <td>Product Name/Title</td>
            <td><input type="text" size="40" name="royaltycart_product_name" value="<?php echo $product_name; ?>" /></td>
        </tr>
        <tr>
            <td>Base File Name</td>
            <td><input type="text" size="40" name="royaltycart_basefile" value="<?php echo $basefile; ?>" /></td>
        </tr>
        <tr>
            <td>File Formats</td>
            <td>
            	<table width="80%"><tr>
            	  </tr><td>&nbsp;</td><td>File Suffix</td><td>Description</td><tr>
            	  <?php $allfileformats = royaltycart_fileformat_array();
				  foreach($allfileformats as $fileformat){
                    echo "</tr><td><input type='checkbox' name=".$fileformat['suffix']." value='0'></td><td>".$fileformat['suffix']."</td><td>".$fileformat['description']."</td><tr>";
			      }?>
            	</tr></table>
            	<br>Placeholder: <input type="text" size="40" name="royaltycart_fileformats" value="<?php echo $fileformats; ?>" />
            </td>
        </tr>
        <tr>
            <td>Pricing</td>
            <td>Set Price: <input type="text" size="5" name="royaltycart_price" value="<?php echo $price; ?>" /><br>
            	<input type="checkbox" name="royaltycart_price_byuser" value="1">Allow the customer to select the price<br>
            	<input type="text" size="5" name="royaltycart_price_min" value="<?php echo $price_min; ?>" /> Minimum (For typed in amounts):<br>
            	Pice options (seperate values by commas):<br>
            	<input type="text" size="30" name="royaltycart_price_list" value="<?php echo $price_increment; ?>" />
            	
            	<p>Display</p>
                <input type = "radio"
                  name = "royaltycart_pricedisplay"
                  id = "user_enter"
                  value = "enter" />
                <label for = "user_enter">Type in</label><br>
          
                <input type = "radio"
                 name = "royaltycart_pricedisplay"
                 id = "user_select"
                 value = "select" />
                <label for = "user_select">Pull Down Selector</label><br>
 
                <input type = "radio"
                 name = "royaltycart_pricedisplay"
                 id = "user_button"
                 value = "button" />
                <label for = "user_button">Button(s)</label><br>
 
            </td>
        </tr>
        <tr>
            <td>Payments</td>
            <td><input type="text" size="40" name="royaltycart_payout" value="<?php echo $payout; ?>" /></td>
        </tr>
    </table>
    <?php
}


function royaltycart_cart_save_products( $product_id, $royaltycart_products ) {
    if ( $royaltycart_products->post_type == 'royaltycart_products' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['royaltycart_product_name'] ) && $_POST['royaltycart_product_name'] != '' ) {
            update_post_meta( $product_id, 'royaltycart_product_name', $_POST['royaltycart_product_name'] );
        }
        if ( isset( $_POST['royaltycart_basefile'] ) && $_POST['royaltycart_basefile'] != '' ) {
            update_post_meta( $product_id, 'royaltycart_basefile', $_POST['royaltycart_basefile'] );
        }
		if ( isset( $_POST['royaltycart_fileformats'] ) && $_POST['royaltycart_fileformats'] != '' ) {
            update_post_meta( $product_id, 'royaltycart_fileformats', $_POST['royaltycart_fileformats'] );
        }
        if ( isset( $_POST['royaltycart_payout'] ) && $_POST['royaltycart_payout'] != '' ) {
            update_post_meta( $product_id, 'royaltycart_payout', $_POST['royaltycart_payout'] );
        }
    }
}



add_filter( 'manage_edit-royaltycart_products_columns', 'royaltycart_products_display_columns' );
function royaltycart_products_display_columns( $columns ) 
{
    unset( $columns['comments'] );
    unset( $columns['date'] );
    $columns['title'] = "Product ID";
    $columns['royaltycart_product_name'] = "Product Name";
    $columns['royaltycart_basefile'] = "Base File Name";
	$columns['royaltycart_fileformats'] = "File Formats";
	$columns['royaltycart_payout'] = "Payments";
    $columns['date'] = "Date";
    return $columns;
}



add_action('manage_royaltycart_products_posts_custom_column', 'royaltycart_populate_product_columns', 10, 2);
function royaltycart_populate_product_columns($column, $post_id)
{
    if ( 'royaltycart_product_name' == $column ) {
        $product_name = get_post_meta( $post_id, 'royaltycart_product_name', true );
        echo $product_name;
    }
    else if ( 'royaltycart_basefile' == $column ) {
        $basefile = get_post_meta( $post_id, 'royaltycart_basefile', true );
        echo $basefile;
    }
    else if ( 'royaltycart_fileformats' == $column ) {
        $fileformats = get_post_meta( $post_id, 'royaltycart_fileformats', true );
        echo $fileformats;
    }
    else if ( 'royaltycart_payout' == $column ) {
        $payout = get_post_meta( $post_id, 'royaltycart_payout', true );
        echo $payout;
    }
}



add_filter('post_type_link',"royaltycart_customize_product_link",10,2);
function royaltycart_customize_product_link( $permalink, $post ) {
    if( $post->post_type == 'royaltycart_products' ) {
        $permalink = get_admin_url().'post.php?post='.$post->ID.'&action=edit';
    }
    return $permalink;
}

function royaltycart_fileformat_array(){
  //add or remove any available file formats here
  //  No sorting is applied, arrange them here the hard way...
  $allfileformats  = array(
    array('suffix' => "-low.wav", 'description' => 'Low bandwidth microsoft audio'),
    array('suffix' => ".wav", 'description' => 'CD quality microsoft audio'),
    array('suffix' => "-low.mp3", 'description' => 'Low bandwidth mpeg audio'),
    array('suffix' => ".mp3", 'description' => 'CD quality mpeg audio'),
    array('suffix' => "-net.mp4", 'description' => 'low bandwidth mpeg video'),
    array('suffix' => "-tv.mp4", 'description' => 'TV quality mpeg video'),
    array('suffix' => "-hd.mp4", 'description' => 'High Definition mpeg video'),
    array('suffix' => "-net.wmv", 'description' => 'low bandwidth Windows Media video'),
    array('suffix' => "-tv.wmv", 'description' => 'TV quality Windows Media video'),
    array('suffix' => "-hd.wmv", 'description' => 'High Definition Windows Media video'),
    array('suffix' => "-net.mov", 'description' => 'low bandwidth Quicktime video'),
    array('suffix' => "-tv.mov", 'description' => 'TV quality Quicktime video'),
    array('suffix' => "-hd.mov", 'description' => 'High Definition Quicktime video'),
    array('suffix' => ".jpg", 'description' => 'Jpeg High Resolution Image'),
    array('suffix' => ".pdf", 'description' => 'PDF: Adobe Acrobat'),
    array('suffix' => ".doc", 'description' => 'Word Document')
  );
  return $allfileformats;
}

?>