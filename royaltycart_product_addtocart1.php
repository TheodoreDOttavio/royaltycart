  <!-- Royaltycart Add to Cart Element -->
  <!-- A Pull down selector -->
  <?php echo ("Purchase <strong>" . $product_name . "</strong> for");?>

  <table width="100"><tr><td width="75%">
   <select name="royaltycart_addprice">
   <?php
    $rc_pricelist = explode(" ",$priceing['price_list']);
    sort($rc_pricelist);
    foreach($rc_pricelist as $rc_amt){
      echo ("<option value='" . $rc_amt . "'");
        if ($rc_amt == $rc_pricelist[1]){
          echo (" selected");
        }
      echo (" />$ " . $rc_amt . "</option>");
    };
    ?>
    </select>
  </td><td width="25%">
    <input name="save" type="submit" 
      class="button button-primary button-small" 
      id="royaltycart_purchase" 
      value="Checkout" /></td>
  </tr></table>
  <!-- end Payee Royaltycart Add to Cart Element -->