<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );
?>

<div class="rcbox_plain">
 <div class="rctitle">Product Name</div>
 <input type="text" size="60" class="rcinfobox" name="royaltycart_product_name" value="<?php echo $product_name; ?>" />

 <div class="rcdescription">Use this shortcode for an 'add to cart' button</div>
 <div class="rcinfobox">[royaltycart_purchase id=<?php echo $product_id;?>]</div>
</div>

<div class="rcbox_money">
 <div class="rctitle">Price Options</div>
 <div class="rcdescription">Seperate values with a space like this: "5 9.99 20"</div>
 <input type="text" size="60" class="rcinfobox" name="royaltycart_priceing_price_list" value="<?php echo $priceing['price_list']; ?>" />
 <div class="rcdescription">Display Type</div>
 <div class="rcradio">
    <input type = "radio"
    name = "royaltycart_priceing_display"
    id = "user_enter"
    value = 0 
    <?php if ( $priceing['display'] == 0 ) { echo 'checked'; } ?> />
    <label for = 0 >Type in allows any amount over $ <?php echo $pricearry['0']; ?></label>
 </div>
 <div class="rcradio">
    <input type = "radio"
     name = "royaltycart_priceing_display"
     id = "user_select"
     value = 1 
     <?php if ( $priceing['display'] == 1 ) { echo 'checked'; } ?> />
    <label for = 1 >A Pull Down Selector</label>
 </div>
 <div class="rcradio">
    <input type = "radio"
     name = "royaltycart_priceing_display"
     id = "user_button"
     value = 2 
     <?php if ( $priceing['display'] == 2 ) { echo 'checked'; } ?> />
    <label for = 2 >A Set of Buttons</label>
 </div>
 <div class="rcradio">   
    <input type = "radio"
     name = "royaltycart_priceing_display"
     id = "user_button"
     value = 3
     <?php if ( $priceing['display'] == 3 ) { echo 'checked'; } ?> />
    <label for = 2 >A fixed amount of $ <?php echo $pricearry['0']; ?></label>
 </div>

 <div class="rctitle">Payments</div>
</div>


<div class="rcbox_plain">
 <div class="rctitle">Files Available for Download</div>
 <?php
  foreach($rcfilelist as $rcfile){
  	echo ("<div class='rcinfobox'>");
    echo ($rcfile);
	echo ("</div>");
  };
 ?>

 <div class="rctitle">Add more files available with this purchase</div>
 <div class="rcdescription">Base Filename</div>
 <input type="text" size="60" class="rcinfobox" name="royaltycart_basefile" value="<?php echo $basefile; ?>" />
 
 <div class="rcdescription">Upload Media</div>
 <input type="file" name="rc_media_upload" id="rc_media_upload"  multiple="false" />
 
 <div class="rcdescription">Info For Testing</div>
 <div class="rcdescription">
 <?php echo $basefile; ?>
 </div>
</div>

<div class="rcbox_plain">
 <div class="rctitle">Preview</div>
 <div class="rcdescription">This is what the shortcode will add to your site</div>

 <div class="rcdescription">Sample of payouts when this is purchased</div>
</div>        
