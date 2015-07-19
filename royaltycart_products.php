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
  $payout = get_post_meta( $royaltycart_products->ID, 'royaltycart_payout', true );

  //priceing array - determines what is charged for the download
  //  ['price']=>10: Number for setprice, default, or suggested amount
  //  ['user_sets']=>boolean: true allows user type-in, user selct, or multiple buttons
  //  ['display']=>0,1,2: Type in, Buttons, Option Selection
  //  ['min']=>1: minimum amount for user type in
  //  ['price_list']=>[1,5,10,15]: array for multiple buttons or pull down list
  $priceing = get_post_meta( $royaltycart_priceing->ID, 'royaltycart_priceing', true );
  
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
            <td valign='top'>Price Options</td>
            <td>            	
            	<input type="text" size="5" name="royaltycart_priceing_price" value="<?php echo $priceing['price']; ?>" /> Set Price or Suggested Price<br>
            	<input type="checkbox" name="royaltycart_priceing_user_sets" value='1' >Allow the customer to select the price<br>
            	<p>User Enrty Display Type</p>
                <input type = "radio"
                  name = "royaltycart_priceing_display"
                  id = "user_enter"
                  value = "0" />
                <label for = "0">Type in -- Minimum:</label> 
               
                <input type = "radio"
                 name = "royaltycart_priceing_display"
                 id = "user_select"
                 value = "1" />
                <label for = "1">Pull Down Selector</label><br>
 
                <input type = "radio"
                 name = "royaltycart_priceing_display"
                 id = "user_button"
                 value = "2" />
                <label for = "2">Button(s)</label><br> 
                
                <input type="text" size="5" name="royaltycart_priceing_min" value="<?php echo $priceing['min']; ?>" /><br>

            	Pice options (seperate values by commas) example: 5,10,15:<br>
            	<input type="text" size="40" name="royaltycart_priceing_price_list" value="<?php echo $priceing['price_list']; ?>" /><br>
 
            </td>
        </tr>

        <tr>
            <td valign='top'>Payments</td>
            <td><input type="text" size="40" name="royaltycart_payout" value="<?php echo $payout; ?>" /></td>
        </tr>
        
        <tr>
            <td>Base File Name</td>
            <td><input type="text" size="40" name="royaltycart_basefile" value="<?php echo $basefile; ?>" /></td>
        </tr>
        <tr>
            <td valign='top'>File Formats</td>
            <td>
            	<table width="80%"><tr>
            	  </tr><td>&nbsp;</td><td>File Suffix</td><td>Description</td><tr>
            	  <?php
            	  $allfileformats = royaltycart_fileformat_array();
				  foreach($allfileformats as $format){
					if ( !array_search($format['suffix'], $fileformats)) {
						$myvalue= "value='".$format['suffix']."'";
					}else{
						$myvalue = "value='".$format['suffix']."' checked";
					}
                    echo "</tr><td><input type='checkbox' name='royaltycart_".$format['suffix']."' ".$myvalue."></td><td>".$format['suffix']."</td><td>".$format['description']."</td><tr>";
			      }?>
            	</tr></table>
            </td>
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
		if ( isset( $_POST['royaltycart_payout'] ) && $_POST['royaltycart_payout'] != '' ) {
            update_post_meta( $product_id, 'royaltycart_payout', $_POST['royaltycart_payout'] );
        }
        
        //File Formats save and update
        if ( isset( $_POST['royaltycart_basefile'] ) && $_POST['royaltycart_basefile'] != '' ) {
            update_post_meta( $product_id, 'royaltycart_basefile', $_POST['royaltycart_basefile'] );
        }
        $allfileformats = royaltycart_fileformat_array();
		$newfileformats = array(0 => 'None');
	    foreach($allfileformats as $format){
	      $aspost = str_replace(".", "_", $format['suffix']);
		  if ( isset( $_POST['royaltycart_'.$aspost] ) ) {
		  	$newfileformats[] = $format['suffix'];
		  }
		}
		
		update_post_meta( $product_id, 'royaltycart_fileformats', $newfileformats );
		//Priceing Save and update
       if ( isset( $_POST['royaltycart_priceing_price'] ) ) {
       	 if ($_POST['royaltycart_priceing_price'] != ''){
       	   $newpriceing['price'] = $_POST['royaltycart_priceing_price'];
		 }else{
		   $newpriceing['price'] = 0;
       	 }
	   }
	   if ( isset( $_POST['royaltycart_priceing_user_sets']) && $_POST['royaltycart_priceing_user_sets'] == 1 ) {
	   	 $newpriceing['user_sets'] = 1;
	   }else{
	   	 $newpriceing['user_sets'] = 0;
	   }
       if ( isset( $_POST['royaltycart_priceing_display'] ) ) {
       	 $newpriceing['display'] = $_POST['royaltycart_priceing_display'];
       }
       if ($_POST['royaltycart_priceing_min'] != "" ){
           $newpriceing['min'] = $_POST['royaltycart_priceing_min'];
       }else{
		   $newpriceing['min'] = 0;
       }
	   update_post_meta( $product_id, 'royaltycart_priceing', $newpriceing );
    }//end post type
}//end function



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
    else if ( 'royaltycart_priceing' == $column ) {
        $priceing = get_post_meta( $post_id, 'royaltycart_priceing', true );
        echo $priceing;
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
  //add or remove any possible file formats here
  //  No sorting is applied, arrange them the hard way...
  $allfileformats  = array(
    array('suffix' => "-low.wav", 'description' => 'Low bandwidth microsoft audio'),
    array('suffix' => ".wav", 'description' => 'CD quality microsoft audio'),
    array('suffix' => "-low.mp3", 'description' => 'Low bandwidth mpeg audio'),
    array('suffix' => ".mp3", 'description' => 'CD quality mpeg audio'),
    array('suffix' => ".jpg", 'description' => 'Jpeg High Resolution Image'),
    array('suffix' => ".pdf", 'description' => 'PDF: Adobe Acrobat'),
    array('suffix' => ".doc", 'description' => 'Word Document'),
    array('suffix' => "-net.mp4", 'description' => 'low bandwidth mpeg video'),
    array('suffix' => "-tv.mp4", 'description' => 'TV quality mpeg video'),
    array('suffix' => "-hd.mp4", 'description' => 'High Definition mpeg video'),
    array('suffix' => "-net.wmv", 'description' => 'low bandwidth Windows Media video'),
    array('suffix' => "-tv.wmv", 'description' => 'TV quality Windows Media video'),
    array('suffix' => "-hd.wmv", 'description' => 'High Definition Windows Media video'),
    array('suffix' => "-net.mov", 'description' => 'low bandwidth Quicktime video'),
    array('suffix' => "-tv.mov", 'description' => 'TV quality Quicktime video'),
    array('suffix' => "-hd.mov", 'description' => 'High Definition Quicktime video'),
    array('suffix' => "-3d-net.mp4", 'description' => 'Anaglyph low bandwidth mpeg video'),
    array('suffix' => "-3d-tv.mp4", 'description' => 'Anaglyph TV quality mpeg video'),
    array('suffix' => "-3d-hd.mp4", 'description' => 'Anaglyph High Definition mpeg video'),
    array('suffix' => "-3d-net.wmv", 'description' => 'Anaglyph low bandwidth Windows Media video'),
    array('suffix' => "-3d-tv.wmv", 'description' => 'Anaglyph TV quality Windows Media video'),
    array('suffix' => "-3d-hd.wmv", 'description' => 'Anaglyph High Definition Windows Media video'),
    array('suffix' => "-3d-net.mov", 'description' => 'Anaglyph low bandwidth Quicktime video'),
    array('suffix' => "-3d-tv.mov", 'description' => 'Anaglyph TV quality Quicktime video'),
    array('suffix' => "-3d-hd.mov", 'description' => 'Anaglyph High Definition Quicktime video')
  );
  return $allfileformats;
}

?>