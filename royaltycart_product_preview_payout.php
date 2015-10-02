<?php if ( $payout['value'] != 0 ) {?>
<!-- View Payee Block -->
<table width = "400"><tr><td width = "15%" align = "right">
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
  echo "</div></td><td><div class='rccontent'>"; 
  echo ($payout['payee_name']. " - " . $payout['comment_role']); 
  
  echo "</div></td><td width = '20%'><div class='rccontent'>"; 
  


  echo ("</div></td></tr></table><!-- end View Payee Block -->");
} ?>