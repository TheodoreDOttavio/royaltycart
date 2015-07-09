<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );

  global $wpdb;
  $table = $wpdb->prefix."royaltycart_products";
  $structure = "DROP TABLE $table;";
	
  dbDelta( $structure );
?>