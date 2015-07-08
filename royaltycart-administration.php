<?php
  //Security measure, disallows direct access
  defined( 'ABSPATH' ) or die( 'No script!' );
  //DEBUGING
  $wpdb->show_errors();

  echo '
    <div id="message" class="updated fade">reserved for error messages';
  echo '  
    </div>

    <div class="wrap">
    <h2>Royalty Cart Administration</h2>
  ';


  //This array is the location => title for the top tabs
  $royaltycart_plugin_tabs = array(
    'Royalty-Cart' => 'Product List',
    'Royalty-Cart&action=edit' => 'Product Edit',
    'Royalty-Cart&action=orders' => 'Orders',
    'Royalty-Cart&action=emails' => 'Response Emails',
    'Royalty-Cart&action=usage' => 'About & Usage'
  );
  $current = "";
  $content .= '<h2 class="nav-tab-wrapper">';
  foreach($royaltycart_plugin_tabs as $location => $tabname){
  	$class = '';
    if($current == $location){
      $class = ' nav-tab-active';
    }
    $content .= '<a class="nav-tab'.$class.'" href="?page='.$location.'">'.$tabname.'</a>';
  }
  $content .= '</h2>';

  echo $content;     
  echo '<div id="poststuff"><div id="post-body">';
  
  if(isset($_GET['action'])){    
    switch ($_GET['action']){
    case 'edit':
      show_royaltycart_administration_product_list();
      //show_wp_cart_email_settings_page();
      break;
    case 'orders':
	  show_royaltycart_administration_product_list();
      //include_once ('wp_shopping_cart_discounts_menu.php');
      //show_wp_cart_coupon_discount_settings_page();
      break;
    case 'emails':
	  show_royaltycart_administration_product_list();
      break;
	case 'usage':
      include_once('royaltycart-administration-usage.php');
	  show_royaltycart_administration_usage();
      break;
    }
  } else {
  	show_royaltycart_administration_product_list();
  }
  echo '</div></div>';
  echo '</div>';
?>


<?php
function show_royaltycart_administration_product_list(){
	echo '
<div class="postbox" style="padding: 5px 5px 5px 10px">
  <h3><label for="title">Products Available to Royalty Cart</label></h3>
  <div class="inside">
';

    global $wpdb;
    $results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'royaltycart_products' );
  
    foreach($results as $result)
    {
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