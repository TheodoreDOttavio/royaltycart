<div align = "center">
  <!-- Edit Payee Block -->
  <table><tr><td>
    <div align="left">
      <input type="text" size="30" class="rctextinputsmall" name="royaltycart_product_payout_payee_name<?php echo $payout['rclistindex']; ?>" value="<?php echo $payout['payee_name']; ?>" />
    </div>
    <div align="left">
     <input type="text" size="30" class="rctextinputsmall" name="royaltycart_product_payout_comment_role<?php echo $payout['rclistindex']; ?>" value="<?php echo $payout['comment_role']; ?>" />
    </div>
    <div align="left">
     <input type="text" size="30" class="rctextinputsmall" name="royaltycart_product_payout_payee<?php echo $payout['rclistindex']; ?>" value="<?php echo $payout['payee']; ?>" />
    </div>
  </td><td>
    <div align="left">
     <?php if ( $payout['remainder'] != 1 ) { ?>
      <?php if($payout['percent'] == 1){echo("%");}else{echo("$");} ?>
      <input type="text" size="6" class="rctextinputsmall" name="royaltycart_product_payout_value<?php echo $payout['rclistindex']; ?>" value="<?php echo $payout['value']; ?>" />
      <input type='checkbox' name = 'royaltycart_product_payout_percent<?php echo $payout['rclistindex']; ?>' 
      id = 'royaltycart_product_payout_percent<?php echo $payout['rclistindex']; ?>' value = '1' <?php if($payout['percent'] == 1){echo(" checked = '1' "); } ?> />percent/value
    </div>
    <div align="left">
        $<input type="text" size="6" class="rctextinputsmall" name="royaltycart_product_payout_trigger<?php echo $payout['rclistindex']; ?>" value="<?php echo $payout['trigger']; ?>" />
        Transfer amount<br>
        $ <?php echo ($payout['reserve']); ?> Currently Reserved
        <input type="hidden" name="royaltycart_product_payout_reserve<?php echo $payout['rclistindex']; ?>" value="<?php echo ($payout['reserve']); ?>" />
      <?php } ?>
    </div>
    <div align="right">
     <input type = "radio"
      name = "royaltycart_product_payout_remainder" 
      id = "royaltycart_product_payout_remainder" 
      value="<?php echo $payout['rclistindex']; ?>" 
      <?php if ( $payout['remainder'] == 1 ) { echo 'checked'; } ?> />Primary Recipient
    </div>
  </td></tr>
  <tr><td colspan="2">
   <textarea rows="1" cols="70" class="rctextareainput" name="royaltycart_product_payout_comments<?php echo $payout['rclistindex']; ?>" /><?php echo $payout['comments']; ?></textarea>
  </td></tr></table>
  <!-- end Edit Payee Block -->
</div>