<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );
?>

<!-- Inline javascript for nav tabs -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<div class="bootstrap-wpadmin">

<div class="well" align="center">
	Shortcode for this product is 
	<p style="background-color: #DDDDDD; padding: 5px; display: inline;">[royaltycart_purchase id=<?php echo $product_id;?>]</p>
</div>

<div class="panel panel-success">
  <!-- Default panel contents -->
  <div class="panel-heading" align="center">Product Name/Title</div>
  <div class="panel-body" align="center" >
    <input type="text" size="40" name="royaltycart_product_name" value="<?php echo $product_name; ?>" />
  </div>
</div>  
  
<div class="panel panel-info">
  <!-- Default panel contents -->
  <div class="panel-heading" align="center">Price Options</div>
  <div class="panel-body" align="center" >
    <input type="text" size="40" name="royaltycart_priceing_price_list" value="<?php echo $priceing['price_list']; ?>" />
  </div>
  <div class="panel-body" >
    <table width='100%'><tr>
              <td>User Display Type</td>
              <td align='center'>Sample</td>
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
</div>  
 

<div class="panel panel-danger">
  <!-- Default panel contents -->
  <div class="panel-heading" align="center">Payments</div>
  <div class="panel-body" align="center" >
    <input type="text" size="40" name="royaltycart_payout" value="<?php echo $payout; ?>" />
  </div>
</div> 

<div class="panel panel-info">
  <div class="panel-heading" align="center">Downloads</div>
  <div class="panel-body" align="center" >
  	Base File Name<br>
    <input type="text" size="40" name="royaltycart_basefile" value="<?php echo $basefile; ?>" />
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
      <table width="80%" class="table"><tr>
      </tr><td>&nbsp;</td><td>File Suffix</td><td>Description</td><tr>
      <?php
        $allfileformats = royaltycart_fileformat_array('audio');
	    foreach($allfileformats as $format){
	      if ( !array_search($format['suffix'], $fileformats)) {
            $myvalue= "value='".$format['suffix']."'";
        }else{
          $myvalue = "value='".$format['suffix']."' checked";
        }
        echo "</tr><td><input type='checkbox' name='royaltycart_".$format['suffix']."' ".$myvalue."></td><td>".$format['suffix']."</td><td>".$format['description']."</td><tr>";
        }?>
      </tr></table>
    </div>
      
    <div role="tabpanel" class="tab-pane" id="rcvideo">
      <table width="80%" class="table"><tr>
      </tr><td>&nbsp;</td><td>File Suffix</td><td>Description</td><tr>
      <?php
        $allfileformats = royaltycart_fileformat_array('video');
	    foreach($allfileformats as $format){
	      if ( !array_search($format['suffix'], $fileformats)) {
            $myvalue= "value='".$format['suffix']."'";
        }else{
          $myvalue = "value='".$format['suffix']."' checked";
        }
        echo "</tr><td><input type='checkbox' name='royaltycart_".$format['suffix']."' ".$myvalue."></td><td>".$format['suffix']."</td><td>".$format['description']."</td><tr>";
        }?>
      </tr></table>
    </div>
    
    <div role="tabpanel" class="tab-pane" id="rc3dvideo">
      <table width="80%" class="table"><tr>
      </tr><td>&nbsp;</td><td>File Suffix</td><td>Description</td><tr>
      <?php
        $allfileformats = royaltycart_fileformat_array('anaglyphvideo');
	    foreach($allfileformats as $format){
	      if ( !array_search($format['suffix'], $fileformats)) {
            $myvalue= "value='".$format['suffix']."'";
        }else{
          $myvalue = "value='".$format['suffix']."' checked";
        }
        echo "</tr><td><input type='checkbox' name='royaltycart_".$format['suffix']."' ".$myvalue."></td><td>".$format['suffix']."</td><td>".$format['description']."</td><tr>";
        }?>
      </tr></table>
    </div>
    
    <div role="tabpanel" class="tab-pane" id="rcother">
      <table width="80%" class="table"><tr>
      </tr><td>&nbsp;</td><td>File Suffix</td><td>Description</td><tr>
      <?php
        $allfileformats = royaltycart_fileformat_array('other');
	    foreach($allfileformats as $format){
	      if ( !array_search($format['suffix'], $fileformats)) {
            $myvalue= "value='".$format['suffix']."'";
        }else{
          $myvalue = "value='".$format['suffix']."' checked";
        }
        echo "</tr><td><input type='checkbox' name='royaltycart_".$format['suffix']."' ".$myvalue."></td><td>".$format['suffix']."</td><td>".$format['description']."</td><tr>";
        }?>
      </tr></table>
    </div>
  </div>
</div>
  	
  	
  	
  </div>
</div> 


</div>
