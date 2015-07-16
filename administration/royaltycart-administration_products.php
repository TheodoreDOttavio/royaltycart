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
  $results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'royaltycart_products' );
  
  foreach($results as $result){
    echo $result->royaltycart_product_name." : ";
    echo $result->royaltycart_product_file." - ";
	echo $result->royaltycart_product_royalty_array."<br>";
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