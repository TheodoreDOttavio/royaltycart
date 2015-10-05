<!-- View Payment Block -->
<div align = "center">
<table width = "400"><tr><td width = "15%" align = "right">
  <div class="rccontent">
    <?php echo ("$" . $payment['amount']); ?>
  </div>
</td><td>
  <div class='rccontent'>
    <?php echo ($payment['payee_name']. " - " . $payment['comment_role']); ?>
  </div>
</td></tr></table>
</div>
<!-- end View Payee Block -->