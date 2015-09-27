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
  if ( empty($product_name)) { $product_name = "New Product Name";}
  $basefile = get_post_meta( $royaltycart_products->ID, 'royaltycart_basefile', true );
  if ( empty($basefile)) { $basefile = "new_file_name";}
  
  //fileformats is an array of suffixes that will be available to download after purchase.
  //  ['none','.mp4','-hd.mp4','.wmv','-hd.wmv'] - array keys are numeric
  $fileformats = get_post_meta( $royaltycart_products->ID, 'royaltycart_fileformats', true );
  
  //payout array  - determines all the people that get paid and how much.
  //  ['1']=>Sub array; //the first person is the default for remaining percentages
  //    ['value']=>60: base amount or percentage value: 2.5 or 10 for $2.50 or 10%
  //    ['percent']=>true: false for set amounts (60% vs. $60.00)
  //    ['payee']=>'teddottavio@gmail.com': Recipients account (email for paypal)
  //    ['payee_name']=>'Ted DOttavio': Name for display
  //    ['comment_role']=>'Producer': Recipients title or role
  //    ['comments']=>'Brought everyone together': Additional comments abut payee
  //  ['2']=>
  
  //create an empty payee
  $emptypayee = array(
    'value' => 0,
    'percent' => 0,
    'payee' => "",
    'payee_name' => "",
    'comment_role' => "",
    'comments' => ""
  );
  $payout = get_post_meta( $royaltycart_products->ID, 'royaltycart_payout', true );
  if ( empty($payout['0'])) {
      $nextpayee = 1;
      $payout = array ($nextpayee => $emptypayee);
  }else {
      //$payout = ($nextpayee => $emptypayee);
  }


  //priceing array - determines what is charged for the download
  //  ['display']=>"0","1","2","3": Type in, Buttons, Option Selection, Single Button
  //  ['price_list']=>"1 5 10 15": array for multiple buttons or pull down list
  $priceing = get_post_meta( $royaltycart_products->ID, 'royaltycart_priceing', true );
  if ( empty($priceing['price_list'])) {
  	$priceing = array(
	  "display" => "1",
	  "price_list" => "1 5 10 20"
	 );
  }
  $pricearry = explode(" ", $priceing['price_list']);
  sort($pricearry);
  
  //DISPLAY
  include 'royaltycart_product_view_raw.php';
}



function royaltycart_cart_save_products( $product_id, $royaltycart_products ) {
    if ( $royaltycart_products->post_type == 'royaltycart_products' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['royaltycart_product_name'] ) && $_POST['royaltycart_product_name'] != '' ) {
            update_post_meta( $product_id, 'royaltycart_product_name', $_POST['royaltycart_product_name'] );
        }

		//Priceing Save and update
		if ( isset( $_POST['royaltycart_priceing_price_list'] ) ) {
       	  if ($_POST['royaltycart_priceing_price_list'] != ''){
       	    $newpriceing['price_list'] = $_POST['royaltycart_priceing_price_list'];
          }else{
		    $newpriceing['price_list'] = "1 10 15 20";
       	  }
	    }
        if ( isset( $_POST['royaltycart_priceing_display'] ) ) {
	      $newpriceing['display'] = $_POST['royaltycart_priceing_display'];
	    }else{
	      $newpriceing['display'] = "1";
        }
	    update_post_meta( $product_id, 'royaltycart_priceing', $newpriceing );
		
		//Payments save and update
		//if ( isset( $_POST['royaltycart_payout'] ) && $_POST['royaltycart_payout'] != '' ) {
        //    update_post_meta( $product_id, 'royaltycart_payout', $_POST['royaltycart_payout'] );
        //}
        
        //File Formats save and update
        if ( isset( $_POST['royaltycart_basefile'] ) && $_POST['royaltycart_basefile'] != '' ) {
            update_post_meta( $product_id, 'royaltycart_basefile', $_POST['royaltycart_basefile'] );
        }
        $allfileformats = royaltycart_fileformat_array('audio');
		$newfileformats = array(0 => 'None');
	    foreach($allfileformats as $format){
	      $aspost = str_replace(".", "_", $format['suffix']);
		  if ( isset( $_POST['royaltycart_'.$aspost] ) ) {
		  	$newfileformats[] = $format['suffix'];
		  }
		}
		update_post_meta( $product_id, 'royaltycart_fileformats', $newfileformats );
		
		
if ( ! function_exists( 'wp_handle_upload' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
}

$uploadedfile = $_FILES['rc_image_upload'];

$upload_overrides = array( 'test_form' => false );

$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

if ( $movefile && !isset( $movefile['error'] ) ) {
    echo "File is valid, and was successfully uploaded.\n";
    var_dump( $movefile);
} else {
    /**
     * Error generated by _wp_handle_upload()
     * @see _wp_handle_upload() in wp-admin/includes/file.php
     */
    echo $movefile['error'];
}

//ok... back to this:
//http://ottopress.com/2011/tutorial-using-the-wp_filesystem/

		// Let WordPress handle the upload.
        //$attachment_id = media_handle_upload( 'rc_image_upload', $product_id );

    }//end post type
    
		
// Check that the nonce is valid, and the user can edit this post.
if ( 
    isset( $_POST['rc_image_upload_nonce'], $_POST['post_id'] ) 
    && wp_verify_nonce( $_POST['rc_image_upload_nonce'], 'rc_image_upload' )
    && current_user_can( 'edit_post', $_POST['post_id'] )
) {
    // The nonce was valid and the user has the capabilities, it is safe to continue.

    // These files need to be included as dependencies when on the front end.
    //require_once( ABSPATH . 'wp-admin/includes/image.php' );
    //require_once( ABSPATH . 'wp-admin/includes/file.php' );
    //require_once( ABSPATH . 'wp-admin/includes/media.php' );
    
    // Let WordPress handle the upload.
    $attachment_id = media_handle_upload( 'rc_image_upload', $_POST['post_id'] );
    
    if ( is_wp_error( $attachment_id ) ) {
      echo("There was an error uploading the image.");
    } else {
      echo("The image was uploaded successfully!");
    }

} else {

    echo("The security check failed, maybe show the user an error.");
}



}//end function



add_filter( 'manage_edit-royaltycart_products_columns', 'royaltycart_products_display_columns' );
function royaltycart_products_display_columns( $columns ) 
{
    unset( $columns['comments'] );
	unset( $columns['date'] );
    $columns['title'] = "Product ID";
    $columns['royaltycart_product_name'] = "Product Name";
    $columns['royaltycart_basefile'] = "Base File Name";
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
    else if ( 'royaltycart_payout' == $column ) {
        $payout = get_post_meta( $post_id, 'royaltycart_payout', true );
        echo $payout;
    }
    else if ( 'royaltycart_priceing' == $column ) {
        $priceing = get_post_meta( $post_id, 'royaltycart_priceing', true );
        echo $priceing;
    }
    else if ( 'royaltycart_basefile' == $column ) {
        $basefile = get_post_meta( $post_id, 'royaltycart_basefile', true );
        echo $basefile;
    }
    else if ( 'royaltycart_fileformats' == $column ) {
        $fileformats = get_post_meta( $post_id, 'royaltycart_fileformats', true );
        echo $fileformats;
    }
}



add_filter('post_type_link',"royaltycart_customize_product_link",10,2);
function royaltycart_customize_product_link( $permalink, $post ) {
    if( $post->post_type == 'royaltycart_products' ) {
        $permalink = get_admin_url().'post.php?post='.$post->ID.'&action=edit';
    }
    return $permalink;
}



function royaltycart_fileformat_array($mytype){
  //add or remove any possible file formats here
  //  No sorting is applied, arrange them the hard way...
  $audiofileformats  = array(
    array('suffix' => "-low.wav", 'description' => 'Low bandwidth microsoft audio'),
    array('suffix' => ".wav", 'description' => 'CD quality microsoft audio'),
    array('suffix' => "-low.mp3", 'description' => 'Low bandwidth mpeg audio'),
    array('suffix' => ".mp3", 'description' => 'CD quality mpeg audio')
  );
  $otherfileformats  = array(
    array('suffix' => ".jpg", 'description' => 'Jpeg High Resolution Image'),
    array('suffix' => ".pdf", 'description' => 'PDF: Adobe Acrobat'),
    array('suffix' => ".doc", 'description' => 'Word Document')
  );
  $videofileformats  = array(
    array('suffix' => "-net.mpg", 'description' => 'low bandwidth mpeg video'),
    array('suffix' => "-tv.mpg", 'description' => 'TV quality mpeg video'),
    array('suffix' => "-hd.mpg", 'description' => 'High Definition mpeg video'),
    array('suffix' => "-net.wmv", 'description' => 'low bandwidth Windows Media video'),
    array('suffix' => "-tv.wmv", 'description' => 'TV quality Windows Media video'),
    array('suffix' => "-hd.wmv", 'description' => 'High Definition Windows Media video'),
    array('suffix' => "-net.mov", 'description' => 'low bandwidth Quicktime video'),
    array('suffix' => "-tv.mov", 'description' => 'TV quality Quicktime video'),
    array('suffix' => "-hd.mov", 'description' => 'High Definition Quicktime video')
  );
  $anaglyphvideofileformats  = array(
    array('suffix' => "-3d-net.mpg", 'description' => 'Anaglyph low bandwidth mpeg video'),
    array('suffix' => "-3d-tv.mpg", 'description' => 'Anaglyph TV quality mpeg video'),
    array('suffix' => "-3d-hd.mpg", 'description' => 'Anaglyph High Definition mpeg video'),
    array('suffix' => "-3d-net.wmv", 'description' => 'Anaglyph low bandwidth Windows Media video'),
    array('suffix' => "-3d-tv.wmv", 'description' => 'Anaglyph TV quality Windows Media video'),
    array('suffix' => "-3d-hd.wmv", 'description' => 'Anaglyph High Definition Windows Media video'),
    array('suffix' => "-3d-net.mov", 'description' => 'Anaglyph low bandwidth Quicktime video'),
    array('suffix' => "-3d-tv.mov", 'description' => 'Anaglyph TV quality Quicktime video'),
    array('suffix' => "-3d-hd.mov", 'description' => 'Anaglyph High Definition Quicktime video')
  );
  
  switch ($mytype) {
    case 'audio':
        return $audiofileformats;
        break;
    case 'video':
        return $videofileformats;
        break;
    case 'other':
        return $otherfileformats;
        break;
    case 'anaglyphvideo':
        return $anaglyphvideofileformats;
        break;
	case '':
	    return $videofileformats;
        //return array_merge($audiofileformats,$videofileformats,$otherfileformats,$anaglyphvideofileformats);
        break;
  }
 
}

?>