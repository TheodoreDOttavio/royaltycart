  <!-- Payee Block -->
  <table><tr><td width="50%">
  	<div class="rccontent">
  	  <?php 
  	   if ( $payout['remainder'] == 1 ) {
         echo "Remainder";
       }else{
    
  	   if ($payout['percent']){
  	  	echo $payout['value'];
  	  	echo "%";
	   }else{
         echo "$";
		 echo $payout['value'];
	   }
       
      }
      echo " to "; 
  	  echo ($payout['payee_name']. " - " . $payout['comment_role']); ?>
    </div>
  </td><td>
  <div align="left">
    <input type="text" size="60" class="rctextinputsmall" name="royaltycart_payout_payee_name" value="<?php echo $payout['payee_name']; ?>" />
  </div>
  <div align="left">
    <!-- Set whoever gets the remainder to a value od 100% -->
    <?php if ( $payout['remainder'] == 1 ) {
     $payout['value'] = 100;
     $payout['percent'] = 1;
    } ?>
    
    <input type="text" size="6" class="rctextinputsmall" name="royaltycart_payout_value" value="<?php echo $payout['value']; ?>" />
    <input type='checkbox' name='royaltycart_payout_percent' 
    <?php if($payout['percent'] == 1){echo(" checked = '1' "); } ?>
    >%
    
    or remainder <input type = "radio"
    name = "royaltycart_payout_remainder"
    value = "1"
    <?php if ( $payout['remainder'] == 1 ) { echo 'checked'; } ?> />
    
    <input type="text" size="60" class="rctextinputsmall" name="royaltycart_payout_payee" value="<?php echo $payout['payee']; ?>" />
  </div>
  <div align="left">
   <input type="text" size="60" class="rctextinputsmall" name="royaltycart_payout_comment_role" value="<?php echo $payout['comment_role']; ?>" />
  </div>
  <div align="left">
   <input type="text" size="60" class="rctextinputsmall" name="royaltycart_payout_comments" value="<?php echo $payout['comments']; ?>" />
  </div>
  </td></tr></table>
  <!-- end Payee Block -->