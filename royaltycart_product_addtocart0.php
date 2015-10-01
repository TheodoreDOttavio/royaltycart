  <!-- Royaltycart Add to Cart Element -->
  <!-- Type in price -->
  <?php echo ("Purchase <strong>" . $product_name . "</strong>");?>
  <table width="100"><tr><td colspan="2" align="center">
    Enter the amount you would like to pay 
  </td></tr><tr>
    <td width="75%">
    <?php
    $rc_pricelist = explode(" ",$priceing['price_list']);
    sort($rc_pricelist);
    if (empty($rc_pricelist[1])){
        $rc_amt = $rc_pricelist[0];
    }else{
        $rc_amt = $rc_pricelist[1];
    }
    ?>
    <input type="text" size="6" name="royaltycart_addprice" value="<?php echo($rc_amt); ?>" />
  </td><td width="25%">
  	<input name="save" type="submit" 
  	  class="button button-primary button-small" 
  	  id="royaltycart_purchase" 
  	  value="Checkout" />
  </tr></table>
  <!-- end Payee Royaltycart Add to Cart Element -->