<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );
?>

<!-- Inline javascript for nav tabs -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<!-- enables bootsrap in the admin page -->
<div class="bootstrap-wpadmin">

	
<div>  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist" id=>
    <li role="presentation" class="active"><a href="#rcproductmain" aria-controls="rcproductmain" role="tab" data-toggle="tab">Main</a></li>
    <li role="presentation"><a href="#rcproductpriceing" aria-controls="rcproductpriceing" role="tab" data-toggle="tab">Priceing</a></li>
    <li role="presentation"><a href="#rcproductpayments" aria-controls="rcproductpayments" role="tab" data-toggle="tab">Payments</a></li>
    <li role="presentation"><a href="#rcproductdownloads" aria-controls="rcproductdownloads" role="tab" data-toggle="tab">Downloads</a></li>
  </ul>

  
<div class="tab-content">





<div role="tabpanel" class="tab-pane active" id="rcproductmain">
  
<div class="panel panel-success">
  <div class="panel-heading" align="center">Product Name/Title</div>
  <div class="panel-body" align="center" >
    <input type="text" size="40" name="royaltycart_product_name" value="<?php echo $product_name; ?>" />
  </div>
</div>

<div class="well" align="center">
	Shortcode for this product is 
	<p style="background-color: #DDDDDD; padding: 5px; display: inline;">[royaltycart_purchase id=<?php echo $product_id;?>]</p>
</div>

</div> <!-- end rcproductmain -->





<div role="tabpanel" class="tab-pane active" id="rcproductpriceing">
  <div class="panel-body" align="center" >
    <input type="text" size="40" name="royaltycart_priceing_price_list" value="<?php echo $priceing['price_list']; ?>" />
  </div>
  <div class="panel-body" >
    <table width='100%'><tr>
              <td><h3>User Display Type</h3></td>
              <td align='center'><h3>Sample</h3></td>
            </tr><tr>
              <td>
                <input type = "radio"
                  name = "royaltycart_priceing_display"
                  id = "user_enter"
                  value = 0 
                  <?php if ( $priceing['display'] == 0 ) { echo 'checked'; } ?> />
                <label for = 0 >Type in</label>
              </td>
              <td>$<input type="text" size="4" name="foobar" value = <?php echo $pricearry[1]; ?> /> ( The Minimum value is $ <?php echo $pricearry['0']; ?> )</td>
              </tr><tr>
              <td> 
                <input type = "radio"
                 name = "royaltycart_priceing_display"
                 id = "user_select"
                 value = 1 
                 <?php if ( $priceing['display'] == 1 ) { echo 'checked'; } ?> />
                <label for = 1 >Pull Down Selector</label>
              </td>
              </tr><tr>
              <td>
                <input type = "radio"
                 name = "royaltycart_priceing_display"
                 id = "user_button"
                 value = 2 
                 <?php if ( $priceing['display'] == 2 ) { echo 'checked'; } ?> />
                <label for = 2 >Button Set</label>
              </td>
              <td>
              	<?php foreach($pricearry as $thisprice){
              	  echo "<input name='save' type='submit' class='button button-primary button-small' id='publish' value='$".$thisprice."' />&nbsp;";
				}?></td>
              </tr><tr>
              <td>
                <input type = "radio"
                 name = "royaltycart_priceing_display"
                 id = "user_button"
                 value = 3
                 <?php if ( $priceing['display'] == 3 ) { echo 'checked'; } ?> />
                <label for = 2 >Single Price Button</label>
              </td>
              <td><input name="save" type="submit" class="button button-primary button-small" id="nil" value="$<?php echo $pricearry[1]; ?>" /></td>
              </tr></table>
  </div>
</div>  <!-- end rcproductpriceing-->
  




<div role="tabpanel" class="tab-pane active" id="rcproductpayments"> 

  	<?php print_r($payout);
  	foreach($payout as $key => $value){
  	  $payee = $value;
      echo "<input type='text' size='5' name='royaltycart_payee_value_".$key."' value=".$payee['value']." />";
      echo "<input type='checkbox' name='royaltycart_payee_percent_".$key."' value=".$payee['percent']." />"; //format me!!!
      echo "<input type='text' size='10' name='royaltycart_payee_".$key."' value=".$payee['payee']." />";
      echo "<input type='text' size='10' name='royaltycart_payee_name_".$key."' value=".$payee['payee_name']." />";
      echo "<input type='text' size='10' name='royaltycart_comment_role_".$key."' value=".$payee['comment_role']." />";
      echo "<input type='text' size='10' name='royaltycart_comments_".$key."' value=".$payee['comments']." />";
	}
    ?>

</div>  <!-- end rcproductpayments-->







<div role="tabpanel" class="tab-pane active" id="rcproductdownloads">
  

  <div class="panel-body" align="center" >
  	Base File Name<br>
    <input type="text" size="40" name="royaltycart_basefile" value="<?php echo $basefile; ?>" />
    <p>Upload Media:
    <?php 
      //DEFINE("WPFILEUPLOAD_DIR", '/'.substr(WP_PLUGIN_DIR, strlen(ABSPATH)) .'/'.dirname(plugin_basename (__FILE__)).'/');
      //DEFINE("ABSWPFILEUPLOAD_DIR", ( substr(ABSPATH, -1) == "/" ? substr(ABSPATH, 0, -1) : ABSPATH ).WPFILEUPLOAD_DIR);	
	  //DEFINE("RCFILEUPLOAD_DIR", '/'.substr(WP_PLUGIN_DIR, strlen(ABSPATH)) .'/');
	  //<br>from functions: php wp_handle_upload( $file, $overrides, $time ); 
		
	  //creates a hidden field with nonce (number used once)
	  wp_nonce_field('rc-inputfile-nonce');
    ?>
    <input type="file" size="40" name="rc-inputfile" class="button button-success button-small" />
  </div>

<div class="panel-body" >
  	
<div>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist" id=>
    <li role="presentation" class="active"><a href="#rcaudio" aria-controls="rcaudio" role="tab" data-toggle="tab">Audio</a></li>
    <li role="presentation"><a href="#rcvideo" aria-controls="rcvideo" role="tab" data-toggle="tab">Video</a></li>
    <li role="presentation"><a href="#rc3dvideo" aria-controls="rc3dvideo" role="tab" data-toggle="tab">3d Video</a></li>
    <li role="presentation"><a href="#rcother" aria-controls="rcother" role="tab" data-toggle="tab">Other File types</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="rcaudio">
      <?php $allfileformats = royaltycart_fileformat_array('audio');
      include 'royaltycart_product_view_filelist.php';
      ?>
    </div>
      
    <div role="tabpanel" class="tab-pane" id="rcvideo">
      <?php $allfileformats = royaltycart_fileformat_array('video');
      include 'royaltycart_product_view_filelist.php';
      ?>
    </div>
    
    <div role="tabpanel" class="tab-pane" id="rc3dvideo">
      <?php $allfileformats = royaltycart_fileformat_array('anaglyphvideo');
      include 'royaltycart_product_view_filelist.php';
      ?>
    </div>
    
    <div role="tabpanel" class="tab-pane" id="rcother">
      <?php $allfileformats = royaltycart_fileformat_array('other');
      include 'royaltycart_product_view_filelist.php';
      ?>
    </div>
  </div>
</div>
  	

</div>  <!-- end Panel Body -->


</div>  <!-- end rcproductdownloads -->





</div> <!-- end Panel content -->
</div> <!-- end Nav Tabs -->
</div> <!-- end Bootstrap Div -->