<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );

function show_royaltycart_administration_product_list(){
echo '
  <div class="postbox" style="padding: 5px 5px 5px 10px">
    <h3><label for="title">Products Available to Royalty Cart</label></h3>
    <div class="inside">
';

  global $wpdb;
  //wp_htqi_postmeta
  //post_type = 'royaltycart_products'
  $mysql = "SELECT DISTINCT post_id FROM ".$wpdb->prefix."postmeta, ".$wpdb->prefix."posts WHERE ".$wpdb->prefix."postmeta.post_id = ".$wpdb->prefix."posts.ID AND post_type = 'royaltycart_products'";
  
  $results = $wpdb->get_results( $mysql );
  //'SELECT * FROM '.$wpdb->prefix.'postmeta' );
  
  foreach($results as $result){
  	$myproductsql = "SELECT post_id, meta_value FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'royaltycart_product_name' AND post_id = ".$result->post_id;
	$productresults = $wpdb->get_results( $myproductsql );
	
	foreach($productresults as $product){
      echo "<strong>".$product->meta_value."</strong> shortcode - ";
	  echo "[royaltycart_button id=".$product->post_id."]<br>";
	}
  }

echo '
  </div>
</div>
';

echo '<a href="?page=';
$_GET['page'];
echo '&royaltycart-add-product=test">Add an entry</a>
</div>
';
}

?>