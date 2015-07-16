<?php
  //Security measure, disallows direct access
  defined( 'ABSPATH' ) or die( 'No script!' );
  
  global $wpdb;
  
  //DEBUGING
  $wpdb->show_errors();

  //echo '
  //  <div id="message" class="updated fade">reserved for error messages
  //  </div>
  //';

  echo '
    <div class="wrap">
    <h2>Royalty Cart Administration</h2>
  ';


  //This array is the location => title for the top tabs
  $royaltycart_plugin_tabs = array(
    'Royalty-Cart&action=products' => 'Products',
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
    case 'products':
	  include_once('royaltycart-administration_products.php');
      show_royaltycart_administration_product_list();
      break;
    case 'orders':
	  include_once('royaltycart-administration_orders.php');
	  show_royaltycart_administration_orders();
      break;
    case 'emails':
	  show_royaltycart_administration_product_list();
      break;
	case 'usage':
      include_once('royaltycart-administration_usage.php');
	  show_royaltycart_administration_usage();
      break;
    }
  } else {
  	include_once('royaltycart-administration_products.php');
  	show_royaltycart_administration_product_list();
  }
  echo '</div></div>';
  echo '</div>';
?>
