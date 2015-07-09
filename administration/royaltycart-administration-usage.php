<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );
  
function show_royaltycart_administration_usage(){
  echo '
<div class="postbox" style="padding: 5px 5px 5px 10px">
  <h3><label for="title">Quick Usage Guide *** Section Needs to be edited</label></h3>
  <div class="inside">
  
  <p><strong>Step 1</strong> Set up Product options.</p>
  
  <p><strong>Step 2</strong> To add an \'Add to Cart\' button for a product simply add the shortcode [royaltycart_button name=PRODUCT-NAME]</p>
  <p>Example add to cart button shortcode usage:</p>
  <p style="background-color: #DDDDDD; padding: 5px; display: inline;">[royaltycart_button name="Test Product"]</p>
  </div>
</div>

<div class="postbox" style="padding: 5px 5px 5px 10px">
  <h3><label for="title">About</label></h3>
  <div class="inside">
  Musicians, Film Makers, and any collaborative artists can see their share of a product sold directly sent to their Paypall account.
  <p>This plugin is an up-front way of setting up royalty payments for any sales made from your blog or wordpress website.
  <p>
  ';

  $royaltycartinfo = get_plugin_data(plugin_dir_path( __FILE__ ).'royaltycart.php');
  echo '<strong>'.$royaltycartinfo['Name'].'</strong><br>';
  echo 'by '.$royaltycartinfo['Author'].'<br>';
  echo 'Version: '.$royaltycartinfo['Version'].'<br>';
  echo 'Download: <a href='. $royaltycartinfo['PluginURI'].' target = new >'.$royaltycartinfo['PluginURI'].'</a>';

  echo '
  </div>
</div>
';
}


?>