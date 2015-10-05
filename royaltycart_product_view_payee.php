<?php if ( $payout['value'] != 0 ) {
  echo ("<!-- View Payee Block -->");
  echo ("<div align = 'center'><table width = '400'><tr><td width = '15%' align = 'right'>");
  echo ("<div class='rccontent'>");

  if ( $payout['remainder'] == 1 ) {
  	echo (royaltycart_remainder_percent($payoutlist) . "%");
  }else{
    
    if ($payout['percent']){
      echo $payout['value'];
      echo "%";
    }else{
      echo "$";
      echo $payout['value'];
    }

  }
  echo "</div></td><td><div class='rccontent'>"; 
  echo ($payout['payee_name']. " - " . $payout['comment_role']); 
  
  echo "</div></td><td width = '20%'><div class='rccontent'>"; 
  
  if ( $payout['remainder'] != 1 ) {
    echo ("<input type='checkbox' name = 'royaltycart_product_payout_remove" . $payout['rclistindex'] ."'");
    echo ("id = 'royaltycart_product_payout_remove" . $payout['rclistindex'] . "' value='1' >Delete");
  }

  echo ("</div></td></tr></table></div>");
  echo ("<!-- End View Payee Block -->");
} ?>