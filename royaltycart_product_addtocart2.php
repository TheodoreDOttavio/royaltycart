  <!-- Royaltycart Add to Cart Element -->
  <!-- A set of buttons -->
  <?php echo ("Purchase <strong>" . $product_name . "</strong> for");?>
  
  <table width="100"><tr>
    <?php
    $rc_pricelist = explode(" ",$priceing['price_list']);
    sort($rc_pricelist);
    foreach($rc_pricelist as $rc_amt){
      echo ("<td><input name='" . $rc_amt . "' 
      type='submit'
      class='button button-primary button-small' 
      id='royaltycart_purchase'
      value='$ " . $rc_amt . "' /></td>");
    };
    ?>
  </tr></table>
  <!-- end Payee Royaltycart Add to Cart Element -->