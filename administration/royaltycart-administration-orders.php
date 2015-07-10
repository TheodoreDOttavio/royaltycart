<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );


function show_royaltycart_administration_orders(){
  echo 'Show orders here';
  
  $query = new WP_Query( 'post_type=royaltycart_products' );
  if ( $query->have_posts() ) {
	while ( have_posts() ) {
		the_post();
		the_content();
		//
		// Post Content here
		//
	} // end while
  }else{
  	echo"<p>No posts";
  } // end if

  $args = array( 'post_type' => 'royaltycart_products', 'posts_per_page' => 10 );
  $loop = new WP_Query( $args );
  while ( $loop->have_posts() ) : $loop->the_post();
    the_title();
    echo '<div class="entry-content">';
    the_content();
    echo '</div>';
  endwhile;
}
?>