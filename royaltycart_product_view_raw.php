<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );
?>

<div class="rctitle">Product Name</div>
<input type="text" size="60" class="rcinfobox" name="royaltycart_product_name" value="<?php echo $product_name; ?>" />

<div class="rcdescription">Use this shortcode for an 'add to cart' button</div>
<div class="rcinfobox">[royaltycart_purchase id=<?php echo $product_id;?>]</div>

<div class="rctitle">Price Options</div>
<div class="rcdescription">Seperate values with a space like this: "5 9.99 20"</div>
<input type="text" size="60" class="rcinfobox" name="royaltycart_priceing_price_list" value="<?php echo $priceing['price_list']; ?>" />
<div class="rcdescription">Display Type</div>
<div class="rcinfo">
    <input type = "radio"
    name = "royaltycart_priceing_display"
    id = "user_enter"
    value = 0 
    <?php if ( $priceing['display'] == 0 ) { echo 'checked'; } ?> />
    <label for = 0 >Type In</label>

    <input type = "radio"
     name = "royaltycart_priceing_display"
     id = "user_select"
     value = 1 
     <?php if ( $priceing['display'] == 1 ) { echo 'checked'; } ?> />
    <label for = 1 >Pull Down Selector</label>
    <br>
    <input type = "radio"
     name = "royaltycart_priceing_display"
     id = "user_button"
     value = 2 
     <?php if ( $priceing['display'] == 2 ) { echo 'checked'; } ?> />
    <label for = 2 >Button Set</label>
    
    <input type = "radio"
     name = "royaltycart_priceing_display"
     id = "user_button"
     value = 3
     <?php if ( $priceing['display'] == 3 ) { echo 'checked'; } ?> />
    <label for = 2 >Single Price Button</label>
</div>

<div class="rctitle">Payments</div>

<div class="rctitle">Files to Download</div>
<div class="rcdescription">Base Filename</div>
<input type="text" size="60" class="rcinfobox" name="royaltycart_basefile" value="<?php echo $basefile; ?>" />
<div class="rcdescription">Files Found</div>


<div class="rctitle">Preview of what the shortcode will add to your site</div>

<div class="rctitle">Sample of payouts when this is purchased</div>
                        
    <p><table>
            <table><tr>
              <td>User Display Type</td>
              <td align='center'>Sample</td>
            </tr><tr>
              <td>

              </td>
              <td>$<input type="text" size="4" name="foobar" value = <?php echo $pricearry[1]; ?> /> ( The Minimum value is $ <?php echo $pricearry['0']; ?> )</td>
              </tr><tr>
              <td> 

              </td>
              </tr><tr>
              <td>

              </td>
              <td>
              	<?php foreach($pricearry as $thisprice){
              	  echo "<input name='save' type='submit' class='button button-primary button-small' id='publish' value='$".$thisprice."' />&nbsp;";
				}?></td>
              </tr><tr>
              <td>

              </td>
              <td><input name="save" type="submit" class="button button-primary button-small" id="nil" value="$<?php echo $pricearry[1]; ?>" /></td>
              </tr></table>
 
            </td>
        </tr>

        <tr>
            <td valign='top'>Payments</td>
            <td><input type="text" size="40" name="royaltycart_payout" value="<?php echo $payout; ?>" /></td>
        </tr>
        
        <tr>
            <td>Base File Name</td>
            <td></td>
        </tr>
        <tr>
            <td valign='top'>File Formats</td>
            <td>
            	<table width="80%"><tr>
            	  </tr><td>&nbsp;</td><td>File Suffix</td><td>Description</td><tr>
            	  <?php
            	  $allfileformats = royaltycart_fileformat_array();
				  foreach($allfileformats as $format){
					if ( !array_search($format['suffix'], $fileformats)) {
						$myvalue= "value='".$format['suffix']."'";
					}else{
						$myvalue = "value='".$format['suffix']."' checked";
					}
                    echo "</tr><td><input type='checkbox' name='royaltycart_".$format['suffix']."' ".$myvalue."></td><td>".$format['suffix']."</td><td>".$format['description']."</td><tr>";
			      }?>
            	</tr></table>
            </td>
        </tr>
        
    </table>
