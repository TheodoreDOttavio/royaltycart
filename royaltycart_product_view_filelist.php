      <table width="80%" class="table"><tr>
      </tr><td colspan="2"><h3>Available</h3></td><td><h3>Description</h3></td><td><h3>File</h3></td><tr>
      <?php
	    foreach($allfileformats as $format){
	      if ( !array_search($format['suffix'], $fileformats)) {
            $myvalue= "value='".$format['suffix']."'";
        }else{
          $myvalue = "value='".$format['suffix']."' checked";
        }
		$myicon = explode(".",$format['suffix']);
        echo "</tr><td><input type='checkbox' name='royaltycart_".$format['suffix']."' ".$myvalue."></td>";        
        echo "<td><img src='".plugin_dir_url( __FILE__ )."images/fileicons/".$myicon[1].".png'></td>";
		echo "<td>".$format['description']."</td><td>".$basefile.$format['suffix']."</td><tr>";
		}?>
      </tr></table>