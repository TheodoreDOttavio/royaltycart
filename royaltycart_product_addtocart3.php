  <!-- Royaltycart Add to Cart Element -->
  <!-- A single button -->
  <?php echo ("Purchase <strong>" . $product_name . "</strong> for");?>
  <table width="100"><tr><td>
    <?php
    $rc_pricelist = explode(" ",$priceing['price_list']);
    sort($rc_pricelist);
    $rc_amt = $rc_pricelist[0];
    ?>
  	<input name="<?php echo($rc_amt); ?>" 
  	  type="submit" 
  	  class="button button-primary button-small" 
  	  id="royaltycart_purchase" 
  	value="$ <?php echo($rc_amt); ?>" />
  </tr></table>
  <!-- end Payee Royaltycart Add to Cart Element -->