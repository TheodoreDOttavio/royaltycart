<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );



function royaltycart_add_meta_boxes()
{
    add_meta_box( 'product_review_meta_box',
        __("Product Review"),
        'royaltycart_product_review_meta_box',
        'royaltycart_products', 
        'normal', 
        'high'
    );

    add_meta_box( 'order_review_meta_box',
        __("Order Review"),
        'royaltycart_order_review_meta_box',
        'royaltycart_orders', 
        'normal', 
        'high'
    );
}



function royaltycart_drop_tables(){
  //remove cpt posts!
}
?>