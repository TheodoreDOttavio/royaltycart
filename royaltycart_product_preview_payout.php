<!-- View Payment Block -->
<table width = "400"><tr><td width = "15%" align = "right">
<div class="rccontent">
  <?php
  echo ("$" . $payment['amount']);

  echo "</div></td><td><div class='rccontent'>"; 
  echo ($payment['payee_name']. " - " . $payment['comment_role']); 
  ?>
  </div>
  </td></tr></table>
<!-- end View Payee Block -->