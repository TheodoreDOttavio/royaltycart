<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );
?>

    <p><table>
    	<tr><td colspan='2' align='center'>
            Use this shortcode for an 'add to cart' button:
        </td></tr>
    	<tr><td colspan='2' align='center'>
            <p style="background-color: #DDDDDD; padding: 5px; display: inline;">[royaltycart_purchase id=<?php echo $product_id;?>]</p>
        </td></tr>
        
        <tr>
            <td>Product Name/Title</td>
            <td><input type="text" size="40" name="royaltycart_product_name" value="<?php echo $product_name; ?>" /></td>
        </tr>
        
        <tr>
            <td valign='top'>Price Options</td>
            <td>Pice options (seperate values by commas) example: 5,10,15:<br>
            	<input type="text" size="40" name="royaltycart_priceing_price_list" value="<?php echo $priceing['price_list']; ?>" /><br>
            	
            <table><tr>
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
 
            </td>
        </tr>

        <tr>
            <td valign='top'>Payments</td>
            <td><input type="text" size="40" name="royaltycart_payout" value="<?php echo $payout; ?>" /></td>
        </tr>
        
        <tr>
            <td>Base File Name</td>
            <td><input type="text" size="40" name="royaltycart_basefile" value="<?php echo $basefile; ?>" /></td>
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
