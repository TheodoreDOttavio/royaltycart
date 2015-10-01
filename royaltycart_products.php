<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );

global $product_id;

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
  //  ['recipient1']=>Sub array;
  //    ['value']=>60: base amount or percentage value: 2.5 or 10 for $2.50 or 10%
  //    ['percent']=>true: false for set amounts (60% vs. $60.00)
  //    ['remainder']=>false: One recipent gets the rest when values are less than total paid 
  //    ['payee']=>'teddottavio@gmail.com': Recipients account (email for paypal)
  //    ['payee_name']=>'Ted DOttavio': Name for display
  //    ['comment_role']=>'Producer': Recipients title or role
  //    ['comments']=>'Brought everyone together': Additional comments abut payee
  //  ['recipient2']=>
  
  // //hardcode some payees
  $thebegger = array(
    'value' => 0.05,
    'percent' => 0,
    'remainder' => 0,
    'payee' => "teddottavio@yahoo.com",
    'payee_name' => "Ted DOttavio",
    'comment_role' => "Plug in Author",
    'comments' => "Thank you for using this plug in. Leave this here if you would like to donate"
  );
  // $sample1 = array(
    // 'value' => 80,
    // 'percent' => 1,
    // 'remainder' => 1,
    // 'payee' => "teddottavio@yahoo.com",
    // 'payee_name' => "Joe Smith",
    // 'comment_role' => "Producer",
    // 'comments' => ""
  // );
  // $sample2 = array(
    // 'value' => 10,
    // 'percent' => 1,
    // 'remainder' => 0,
    // 'payee' => "ashley@yahoo.com",
    // 'payee_name' => "Ashley",
    // 'comment_role' => "Lead Actress",
    // 'comments' => ""
  // );
  // $sample3 = array(
    // 'value' => 10,
    // 'percent' => 1,
    // 'remainder' => 0,
    // 'payee' => "brian@yahoo.com",
    // 'payee_name' => "Brian",
    // 'comment_role' => "Lead Actor",
    // 'comments' => ""
  // );
  // $sample4 = array(
    // 'value' => 20,
    // 'percent' => 1,
    // 'remainder' => 0,
    // 'payee' => "teddottavio@yahoo.com",
    // 'payee_name' => "Richard",
    // 'comment_role' => "Wardrobe",
    // 'comments' => ""
  // );
  // $payoutlist = array(
    // 'recipient1' => $sample1,
    // 'recipient2' => $sample2,
    // 'recipient3' => $sample3,
    // 'recipient4' => $sample4,
    // 'recipient5' => $thebegger
  // );
  
  //$payoutlist = array();
  //pull list in from data, sort it and here ID is just an ordering so reset it.
  $getpayoutlist = get_post_meta( $royaltycart_products->ID, 'royaltycart_payout', true );
  
  $getpayoutlist = array_sort($getpayoutlist, 'value', SORT_DESC);
  
  $nextpayee = 1;
  foreach($getpayoutlist as $getpayee){
  	$getpayee['form_id'] = $nextpayee;
  	$payoutlist['recipient' . $nextpayee] = $getpayee;
	$nextpayee = $nextpayee + 1;
  }
  
  //create an empty payee
  $emptypayee = array(
    'form_id' => $nextpayee,
    'value' => 0,
    'percent' => 1,
    'remainder'=> 0,
    'payee' => "email",
    'payee_name' => "No Name " . $nextpayee,
    'comment_role' => "",
    'comments' => ""
  );
  
  $payoutlist['recipient' . $nextpayee] = $emptypayee;



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
  
  //List out available product files
  $rcproductdir = trailingslashit( WP_CONTENT_DIR ) . 'uploads/royaltycart/' . $product_id . "/*.*";
  $rcfilelisttmp = array();
  $rcfiles = glob($rcproductdir);
  foreach($rcfiles as $rcfile){
  	//the extra steps here avoid the php error: Only variables should be passed by reference
    $rcfilenames = explode("/", $rcfile);
	$rcfilename = end($rcfilenames);
  	array_push($rcfilelisttmp, $rcfilename);
  }
  $rcfilelist = $rcfilelisttmp;


  //Prepare list for file suffix selection
  $rcsuffixlist = royaltycart_fileformat_array();
  
  //DISPLAY
  include 'royaltycart_product_view.php';
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
       	    //sort the list
       	      $pricearry = explode(" ", $_POST['royaltycart_priceing_price_list']);
              sort($pricearry);
       	    $newpriceing['price_list'] = implode(" ", $pricearry);
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
        //generate $payoutlist object
        $payoutlist = array();
        $nextpayee = 0;
		$foundform_id = true;
        do {
         $nextpayee = $nextpayee + 1;
		 
         //handle radio button across all formsposts
		 if ($_POST['royaltycart_payout_remainder'] == $nextpayee){
			$thisremainder = 1;
			$thisvalue = 100;
		 }else{
			$thisremainder = 0;
			$thisvalue = $_POST['royaltycart_payout_value' . $nextpayee];
		 }
		 
		 
		 if ( empty($_POST['royaltycart_payout_value' . $nextpayee]) && $thisvalue != 100) {
		 	$foundform_id = false;
		    break;
	     }else{
		  //build this form's data object
          $formpayee = array(
           'form_id' => $nextpayee,
           'value' => $thisvalue,
           'percent' => $_POST['royaltycart_payout_percent' . $nextpayee],
           'remainder' => $thisremainder,
           'payee' => $_POST['royaltycart_payout_payee' . $nextpayee],
           'payee_name' => $_POST['royaltycart_payout_payee_name' . $nextpayee],
           'comment_role' => $_POST['royaltycart_payout_comment_role' . $nextpayee],
           'comments' => $_POST['royaltycart_payout_comments' . $nextpayee]
          );
		  $payoutlist['recipient' . $nextpayee] = $formpayee;
		 }
		 
        } while ($foundform_id = true);
		
        update_post_meta( $product_id, 'royaltycart_payout', $payoutlist );


        
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
		
		
        if ( isset( $_FILES['rc_media_upload'] ) && $_FILES['rc_media_upload']['size'] > 0 ) {
			$uploadedfile = $_FILES['rc_media_upload'];	
			$upload_overrides = array( 'test_form' => false );

			add_filter('wp_handle_upload_prefilter', 'royaltycart_custom_upload_filter');
			add_filter( 'upload_dir', 'royaltycart_upload_dir' );
			
			//check what the form is posting - this will also create the directory for non custom paths
			$uploadinfo = wp_upload_dir();

			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

			//update_post_meta( $product_id, 'royaltycart_basefile', '29<br>' . $movefile['file'] . "<br>" . $uploadinfo['path'] . "<br>" . $movefile['errors']);

			//clean up...
			remove_filter('wp_handle_upload_prefilter', 'royaltycart_custom_upload_filter');
			remove_filter( 'upload_dir', 'royaltycart_upload_dir' );
        }

    }//end post type
}//end function


function royaltycart_custom_upload_filter( $file ){
	//check existing file extension against the selected extension
	$namearray = explode('.', $file['name']);
	$currentextension = array_pop($namearray);
	$namearray = explode('.', $_POST['file_suffix']);
	$newextension = array_pop($namearray);
	
	//If the extensions match or there is no extension, use the selection
	if ($currentextension == $newextension && $currentextension != ""){
		$extension = $_POST['file_suffix'];
	}else{
		$extension = "." . $currentextension;
	}
	
    $file['name'] = $_POST['royaltycart_basefile'] . $extension;
    return $file;
}


function royaltycart_upload_dir( $param ){
	$mydir = trailingslashit( WP_CONTENT_DIR ) . 'uploads/royaltycart/' . $_POST['ID'];
    
    $param['path'] = $mydir;
    $param['url'] = $mydir;
	
	//$mydir = '/royaltycart/' . $_POST['ID'];
    //$param['path'] = $param['path'] . $mydir;
    //$param['url'] = $param['url'] . $mydir;

    error_log("path={$param['path']}");  
    error_log("url={$param['url']}");
    error_log("subdir={$param['subdir']}");
    error_log("basedir={$param['basedir']}");
    error_log("baseurl={$param['baseurl']}");
    error_log("error={$param['error']}"); 

    return $param;
}



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



function royaltycart_fileformat_array(){
  //add or remove any possible file formats here
  //  No sorting is applied, arranged the hard way...
  return array(
    array('suffix' => "-low.wav", 'description' => 'Low bandwidth microsoft audio'),
    array('suffix' => ".wav", 'description' => 'CD quality microsoft audio'),
    array('suffix' => "-low.mp3", 'description' => 'Low bandwidth mpeg audio'),
    array('suffix' => ".mp3", 'description' => 'CD quality mpeg audio'),
    array('suffix' => "-sm.jpg", 'description' => 'Jpeg Low Resolution Image'),
    array('suffix' => ".jpg", 'description' => 'Jpeg High Resolution Image'),
    array('suffix' => ".pdf", 'description' => 'PDF: Adobe Acrobat'),
    array('suffix' => ".doc", 'description' => 'Word Document'),
    array('suffix' => "-net.mpg", 'description' => 'low bandwidth mpeg video'),
    array('suffix' => "-tv.mpg", 'description' => 'TV quality mpeg video'),
    array('suffix' => "-hd.mpg", 'description' => 'High Definition mpeg video'),
    array('suffix' => "-net.wmv", 'description' => 'low bandwidth Windows Media video'),
    array('suffix' => "-tv.wmv", 'description' => 'TV quality Windows Media video'),
    array('suffix' => "-hd.wmv", 'description' => 'High Definition Windows Media video'),
    array('suffix' => "-net.mov", 'description' => 'low bandwidth Quicktime video'),
    array('suffix' => "-tv.mov", 'description' => 'TV quality Quicktime video'),
    array('suffix' => "-hd.mov", 'description' => 'High Definition Quicktime video'),
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
}

?>