<!-- View Download Block -->
<div align = "center">
<table width = "400"><tr><td width = "15%" align = "right">
  <div class="rccontent">
    <?php echo ("fieicon"); ?>
  </div>
</td><td>
  <div class='rcinfobox'>
    <?php echo ($rcfile); ?>
  </div>
</td><td width = "20%" align = "right">
  <div class="rccontent">
  	<?php $rcfileid = "royaltycart_product_remove_file_" . substr($rcfile, 0, -4);
  	echo ("<input type='checkbox' name = '" . $rcfileid . "' /> Delete"); ?>
  </div>
</td></tr></table>
</div>
<!-- end View Download Block -->