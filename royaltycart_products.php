<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );

add_action( 'save_post', 'royaltycart_save_products', 10, 2 );
add_action( 'delete_post', 'royaltycart_delete_product' );

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
  
  $messagearray = get_post_meta( $royaltycart_products->ID, 'royaltycart_product_errors', true );
  
  $product_name = get_post_meta( $royaltycart_products->ID, 'royaltycart_product_name', true );
  if ( empty($product_name)) {
    $product_name = "New Product Name";
    $messagearray = royaltycart_messagearray_set($messagearray, "name", "Name this product");
  }
  $basefile = get_post_meta( $royaltycart_products->ID, 'royaltycart_product_basefile', true );
  if ( empty($basefile)) {
    $basefile = "new_file_name";
    $messagearray = royaltycart_messagearray_set($messagearray, "basefile", "Select a filename that will be used for all the downloadable files");
  }
  
  //fileformats is an array of suffixes that will be available to download after purchase.
  //  ['none','.mp4','-hd.mp4','.wmv','-hd.wmv'] - array keys are numeric
  $fileformats = get_post_meta( $royaltycart_products->ID, 'royaltycart_fileformats', true );
  
  //payout array  - determines all the people that get paid and how much.
  //  ['recipient1']=>Sub array;
  //    ['value']=>60: base amount or percentage value: 2.5 or 10 for $2.50 or 10%
  //    ['trigger']=>dollar amount. when reserve exceeds this make a payment from Primary recipient to this payee
  //    ['reserve']=>dollar received by primary to be paid
  //    ['percent']=>true: false for set amounts (60% vs. $60.00)
  //    ['remainder']=>false: One recipent gets the rest when values are less than total paid 
  //    ['payee']=>'teddottavio@gmail.com': Recipients account (email for paypal)
  //    ['payee_name']=>'Ted DOttavio': Name for display
  //    ['comment_role']=>'Producer': Recipients title or role
  //    ['comments']=>'Brought everyone together': Additional comments abut payee
  //  ['recipient2']=>
  
  //pull list in from data, sort it and here ID is just an ordering so reset it.
  $nextpayee = 1;
  $getpayoutlist = get_post_meta( $royaltycart_products->ID, 'royaltycart_product_payout', true );
  
  if (empty($getpayoutlist)){
   $theowner = array(
    'rclistindex' => $nextpayee,
    'value' => 100,
    'trigger' => 0,
    'reserve' => 0,
    'percent' => 1,
    'remainder' => 1,
    'payee' => "Paypall Email",
    'payee_name' => "Name",
    'comment_role' => "Primary Recipient",
    'comments' => ""
   );
   $payoutlist['recipient' . $nextpayee] = $theowner;
   $nextpayee = $nextpayee + 1;
   $thebegger = array(
    'rclistindex' => $nextpayee,
    'value' => 0.05,
    'trigger' => 10,
    'reserve' => 0,
    'percent' => 0,
    'remainder' => 0,
    'payee' => "teddottavio@yahoo.com",
    'payee_name' => "Royalty Cart",
    'comment_role' => "Software Donation",
    'comments' => "Thank you for using this plug in. Leave this here if you would like to donate"
   );
   
   $payoutlist['recipient' . $nextpayee] = $thebegger;
   $nextpayee = $nextpayee + 1;
  }else{
   $getpayoutlist = array_sort($getpayoutlist, 'value', SORT_DESC);
  
   foreach($getpayoutlist as $getpayee){
    $getpayee['rclistindex'] = $nextpayee;
    $payoutlist['recipient' . $nextpayee] = $getpayee;
    $nextpayee = $nextpayee + 1;
   }
  }
  
    //create an empty payee
    $emptypayee = array(
     'rclistindex' => $nextpayee,
     'value' => 0,
     'trigger' => 10,
     'reserve' => 0,
     'percent' => 1,
     'remainder'=> 0,
     'payee' => "Paypall Email",
     'payee_name' => "Add a Recipient",
     'comment_role' => "Role or Title",
     'comments' => ""
    );
  
    $payoutlist['recipient' . $nextpayee] = $emptypayee;


  //priceing array - determines what is charged for the download
  //  ['display']=>"0","1","2","3": Type in, Buttons, Option Selection, Single Button
  //  ['price_list']=>"1 5 10 15": array for multiple buttons or pull down list
  $priceing = get_post_meta( $royaltycart_products->ID, 'royaltycart_product_priceing', true );
  if ( empty($priceing['price_list'])) {
  	$priceing = array(
	  "display" => "1",
	  "price_list" => "1 5 10 20"
	 );
     $messagearray = royaltycart_messagearray_set($messagearray, "priceing", "Enter a list of prices that the download(s) can be purchased for. Use a space between each value.");
  }
  $pricearry = explode(" ", $priceing['price_list']);
  sort($pricearry);
  
  	//send a list of payments
	$payments_low = royaltycart_process_payouts($payoutlist, $pricearry[0],0);
	$payments_med = royaltycart_process_payouts($payoutlist, $pricearry[1],0);
	$payments_high = royaltycart_process_payouts($payoutlist, $pricearry[count($pricearry)-1],0);
  
  //List out available product files
  $rcfilelist = get_product_files($product_id);

  if (empty($rcfilelist)){
    $messagearray = royaltycart_messagearray_set($messagearray, "files", "Add files to download");
  }
  
  //Prepare list for file suffix selection
  $rcsuffixlist = royaltycart_fileformat_array();
  
  //DISPLAY
  include 'royaltycart_product_view.php';
}



function royaltycart_save_products( $product_id, $royaltycart_products ) {
// Check post type and this handles new/update saves based on form _Post data
if ( $royaltycart_products->post_type == 'royaltycart_products' ) {
  $messagearray = array();
    
  //Product Name
  if ( isset( $_POST['royaltycart_product_name'] ) && $_POST['royaltycart_product_name'] != '' ) {
    update_post_meta( $product_id, 'royaltycart_product_name', $_POST['royaltycart_product_name'] );
	royaltycart_product_title_function( $product_id, esc_attr($_POST['royaltycart_product_name']) );
  }else{
    $messagearray = royaltycart_messagearray_set($messagearray, "name", "Enter a Product Name");
  }


  //Priceing Save and update
  if ( isset( $_POST['royaltycart_product_priceing_price_list'] ) ) {
    if ($_POST['royaltycart_product_priceing_price_list'] != ''){
      //sort the list
      $pricearry = explode(" ", $_POST['royaltycart_product_priceing_price_list']);
      sort($pricearry);
      $newpriceing['price_list'] = implode(" ", $pricearry);
    }else{
      $newpriceing['price_list'] = "1 10 15 20";
    }
  }
  if ( isset( $_POST['royaltycart_product_priceing_display'] ) ) {
    $newpriceing['display'] = $_POST['royaltycart_product_priceing_display'];
  }else{
    $newpriceing['display'] = "1";
  }
  update_post_meta( $product_id, 'royaltycart_product_priceing', $newpriceing );
		
  //Payments (payees) save and update
    //generate $payoutlist object
  $nextpayee = 0;

  if (isset($_POST['royaltycart_product_payout_remainder'])){
  do {
    $nextpayee = $nextpayee + 1;
    $rcvalue = 0;
		 
    //handle radio button across all formsposts
    if ($_POST['royaltycart_product_payout_remainder'] == $nextpayee){
      $thisremainder = 1;
      $rcvalue = 100;
    }else{
      $thisremainder = 0;
      $rcvalue = $_POST['royaltycart_product_payout_value' . $nextpayee];
    }

    //build this form's data object
    $formpayee = array(
     'rclistindex' => $nextpayee,
     'value' => $rcvalue,
     'trigger' => $_POST['royaltycart_product_payout_trigger' . $nextpayee],
     'reserve' => $_POST['royaltycart_product_payout_reserve' . $nextpayee],
     'percent' => $_POST['royaltycart_product_payout_percent' . $nextpayee],
     'remainder' => $thisremainder,
     'payee' => $_POST['royaltycart_product_payout_payee' . $nextpayee],
     'payee_name' => $_POST['royaltycart_product_payout_payee_name' . $nextpayee],
     'comment_role' => $_POST['royaltycart_product_payout_comment_role' . $nextpayee],
     'comments' => $_POST['royaltycart_product_payout_comments' . $nextpayee]
    );

    //check to see if we'll be adding a new payee
    if ($formpayee['value'] != 0 && $formpayee['payee_name'] != "Add a Recipient"){
        //but WAIT there's more! did the user select the delete button?
        if ($_POST['royaltycart_product_payout_remove' . $nextpayee] == 0){
          //error checking for invalid payees
          if (stripos($_POST['royaltycart_product_payout_payee' . $nextpayee], "@") == false){
            $messagearray = royaltycart_messagearray_set($messagearray, "Payee_". $nextpayee, "Please enter a valid email for " . $_POST['royaltycart_product_payout_payee_name' . $nextpayee]);
          }
          $payoutlist['recipient' . $nextpayee] = $formpayee;
        }
    }
  } while ($nextpayee < $_POST['royaltycart_totalpayees']); //($nextpayee < 100);

  update_post_meta( $product_id, 'royaltycart_product_payout', $payoutlist );
  
  //Report errors on payout
  if (royaltycart_process_payouts($payoutlist, 1, 1)){
  	$messagearray = royaltycart_messagearray_set($messagearray, "Payouts". $nextpayee, "Payment percentages and values exceed the product price");
  }
  }


  //Downloads save and update
    //Basefile Name
  if ( isset( $_POST['royaltycart_product_basefile'] ) && $_POST['royaltycart_product_basefile'] != '' ) {
      update_post_meta( $product_id, 'royaltycart_product_basefile', $_POST['royaltycart_product_basefile'] );
  }


    //Check for file delete
  $rcfilelist = get_product_files($product_id);
  foreach($rcfilelist as $rcfile){
  	$rcfileid = "royaltycart_product_remove_file_" . substr($rcfile, 0, -4);
    if ( isset( $_POST[$rcfileid] ) && $_POST[$rcfileid] = '1' ) {
      royaltycart_delete_download($product_id, $rcfile);
	  //commented out because message array shows up as a red error box.
      //$messagearray = royaltycart_messagearray_set($messagearray, "task", "Removed file " . $rcfile);
    }
  }
	  
	  
    //Check for and upload new files
  if ( isset( $_FILES['rc_media_upload'] ) && $_FILES['rc_media_upload']['size'] > 0 ) {
    $uploadedfile = $_FILES['rc_media_upload'];	
    $upload_overrides = array( 'test_form' => false );

    add_filter('wp_handle_upload_prefilter', 'royaltycart_custom_upload_filter');
    add_filter( 'upload_dir', 'royaltycart_upload_dir' );

    //check what the form is posting - this will also create the directory for non custom paths
    $uploadinfo = wp_upload_dir();

    $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

    //clean up...
    remove_filter('wp_handle_upload_prefilter', 'royaltycart_custom_upload_filter');
    remove_filter( 'upload_dir', 'royaltycart_upload_dir' );
  }
  
  update_post_meta( $product_id, 'royaltycart_product_errors', $messagearray );
  
  if(empty($messagearray)){
    update_post_meta( $product_id, 'royaltycart_product_shortcode', "[royaltycart_purchase_id=" . $product_id . "]" );
  }else{
    update_post_meta( $product_id, 'royaltycart_product_shortcode', "This product has errors" );
  }
  
}//end post type
}//end function



function royaltycart_product_title_function( $post_id, $newtitle ){
  //wp_update creates an infinite loop when used in save_post because it calls save post.
  //need to update the main (non meta) posts in a seperate function like this.
  if ( ! wp_is_post_revision( $post_id ) ){
    // unhook and re-hook the save function
    remove_action('save_post', 'royaltycart_save_products');

    $newpostdata = array (
	    'ID' => $post_id,
	    'post_title' => $newtitle
	  );
    wp_update_post( $newpostdata );

    add_action('save_post', 'royaltycart_save_products');
  }
}



function royaltycart_delete_product($product_id){
  $mydir = trailingslashit( WP_CONTENT_DIR ) . 'uploads/royaltycart/' . $product_id;

  if (! is_dir($mydir)) {
    throw new InvalidArgumentException("$dirPath must be a directory");
  }
  if (substr($mydir, strlen($mydir) - 1, 1) != '/') {
    $mydir .= '/';
  }
  $files = glob($mydir . '*', GLOB_MARK);
  foreach ($files as $file) {
    unlink($file);
  }
  rmdir($mydir);
}



function royaltycart_delete_download($product_id, $filename){
  $mydir = trailingslashit( WP_CONTENT_DIR ) . 'uploads/royaltycart/' . $product_id . "/" . $filename;
  unlink($mydir);
}



function get_product_files($product_id){
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
  return $rcfilelist;
}



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
	
    $file['name'] = $_POST['royaltycart_product_basefile'] . $extension;
    return $file;
}



function royaltycart_upload_dir( $param ){
	$mydir = trailingslashit( WP_CONTENT_DIR ) . 'uploads/royaltycart/' . $_POST['ID'];
    
    $param['path'] = $mydir;
    $param['url'] = $mydir;
	
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
	$columns['title'] = "Product Name";
    //$columns['royaltycart_product_name'] = "Product Name";
    $columns['royaltycart_product_shortcode'] = "Display Shortcode";
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
    else if ( 'royaltycart_product_payout' == $column ) {
        $payout = get_post_meta( $post_id, 'royaltycart_product_payout', true );
        echo $payout;
    }
    else if ( 'royaltycart_product_priceing' == $column ) {
        $priceing = get_post_meta( $post_id, 'royaltycart_product_priceing', true );
        echo $priceing;
    }
    else if ( 'royaltycart_product_basefile' == $column ) {
        $basefile = get_post_meta( $post_id, 'royaltycart_product_basefile', true );
        echo $basefile;
    }
    // else if ( 'royaltycart_fileformats' == $column ) {
        // $fileformats = get_post_meta( $post_id, 'royaltycart_fileformats', true );
        // echo $fileformats;
    // }
    else if ( 'royaltycart_product_shortcode' == $column ) {
        $rcshortcode = get_post_meta( $post_id, 'royaltycart_product_shortcode', true );
        echo $rcshortcode;
    }
    else if ( 'royaltycart_product_errors' == $column ) {
        $rcerrors = get_post_meta( $post_id, 'royaltycart_product_errors', true );
        echo $rcerrors;
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