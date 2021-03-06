<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );


//Message box with things to do...error messages and all..
if (!empty($messagearray)){
  echo("<div class='rcbox_error'>");
  echo("<div class='rcdescription'>This product is not ready to sell.</div>");
  
  foreach($messagearray as $message){
   echo "<div class='rccontent'>" . $message . "</div>";
  }

  echo("</div>");
 }
?>
   
<div class="rcbox_plain">
 <div class="rctitle">Product Name</div>
 <div align="center">
   <input type="text" size="60" class="rctextinput" name="royaltycart_product_name" value="<?php echo $product_name; ?>" />
 </div>

 <div class="rcdescription">Use this shortcode</div>
 <div align="center">
   <div class="rcinfobox">[royaltycart_purchase_id=<?php echo $product_id;?>]</div>
  <?php
  switch ($priceing['display']) {
  case '0':
   include 'royaltycart_product_addtocart0.php';
   break;
  case '1':
   include 'royaltycart_product_addtocart1.php';
   break;
  case '2':
   include 'royaltycart_product_addtocart2.php';
   break;
  case '3':
   include 'royaltycart_product_addtocart3.php';
   break;
  };
 ?>
 </div>
</div>


<div class="rcbox_money">
 <div class="rctitle">Price Options</div>
 <div class="rcdescription">Seperate values with a space like this: "5 9.99 20"</div>
 <div align="center">
   <input type="text" size="60" class="rctextinput" name="royaltycart_product_priceing_price_list" value="<?php echo $priceing['price_list']; ?>" />
 
 <div class="rcdescription">Display Type</div>
 <select class='rcpulldown' name="royaltycart_product_priceing_display">
   <option value="0" <?php if ( $priceing['display'] == 0 ) { echo 'Selected'; } ?> />
   Type in any amount over $ <?php echo $pricearry['0']; ?>
   </option>
   
   <option value="1" <?php if ( $priceing['display'] == 1 ) { echo 'Selected'; } ?> />
   A Pull Down Selector
   </option>
   
   <option value="2" <?php if ( $priceing['display'] == 2 ) { echo 'Selected'; } ?> />
   A Set of Buttons
   </option>

   <option value="3" <?php if ( $priceing['display'] == 3 ) { echo 'Selected'; } ?> />
   A fixed amount of $ <?php echo $pricearry['0']; ?>
   </option>

 </select>
 </div>

 <div class="rctitle">Payments</div>
  <?php 
  foreach($payoutlist as $payout){
    include 'royaltycart_product_view_payee.php';
  };
  echo("<div class='rcdescription'>Edit Payees</div>");
  $rccounter = 0;
  foreach($payoutlist as $payout){
  	if ($rccounter == (count($payoutlist)-1)){
  	  echo("<div class='rcdescription'>Add a new Payee</div>");
  	}
	$rccounter += 1;
    include 'royaltycart_product_edit_payee.php';
  };
  echo ("<input type='hidden' name='royaltycart_totalpayees' value = '" . $rccounter . "' />");
  ?>
</div>


<div class="rcbox_plain">
 <div class="rctitle">Available for Download</div>
 <div align="center">
 <?php
  foreach($rcfilelist as $rcfile){
  	include 'royaltycart_product_preview_downloads.php';
  };
 ?>
 </div>

 <div class="rctitle">Add more files available with this purchase</div>
 <div class="rcdescription">Base Filename</div>
 <div align="center">
   <input type="text" size="60" class='rctextinput' name="royaltycart_product_basefile" value="<?php echo $basefile; ?>" />
 </div>
 
 <div class="rcdescription">Upload Media</div>
 <div align="center">
   <input type="file" class='rcfileinput' name="rc_media_upload" id="rc_media_upload"  multiple="false" />
 
   <select class='rcpulldown' name="file_suffix">
   <?php
    foreach($rcsuffixlist as $suffix){
     echo ("<option value=" . $suffix['suffix'] . ">");
     echo ($suffix['description']);
     echo ("</option>");
    };
   ?>
   </select>
 </div>
 
</div>

<div class="rcbox_plain">
 <div class="rctitle">Preview the results of a Purchase</div>
  <div align = "center">
  	<div class="rcdescription">$<?php echo ($pricearry[0]); ?> Donated</div>
  <?php 
  foreach($payments_low as $payment){
    include 'royaltycart_product_preview_payout.php';
  };
  ?>
  
  <div class="rcdescription">$<?php echo ($pricearry[1]); ?> Donated</div>
    <?php 
  foreach($payments_med as $payment){
    include 'royaltycart_product_preview_payout.php';
  };
  ?>
  
  <div class="rcdescription">$<?php echo ($pricearry[count($pricearry)-1]); ?> Donated</div>
    <?php 
  foreach($payments_high as $payment){
    include 'royaltycart_product_preview_payout.php';
  };
  ?>
  </div>
</div>
