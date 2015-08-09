<?php
//Security measure, disallows direct access
defined( 'ABSPATH' ) or die( 'No script!' );

if ( royaltycart_check_upload_buttons() ) return;
?>

<form action="" method="post">
<?php wp_nonce_field(); ?>
<input name="save" type="submit" value="Save a file" />
</form>

<?php
function royaltycart_check_upload_buttons() {
	if (empty($_POST)) return false;
	
	check_admin_referer();
	
	$form_fields = array ('save');//and anything else passed...
	$method = '';
	
	if (isset($_POST['save'])) {
		// go get credentials
		$url = wp_nonce_url('themes.php?page=otto');
		if (false === ($creds = request_filesystem_credentials($url, $method, false, false, $form_fields) ) ) {
		
			// if we get here, then we don't have credentials yet,
			// but have just produced a form for the user to fill in, 
			// so stop processing for now
			
			return true; // stop the normal page form from displaying
		}
			
		// now we have some credentials, try to get the wp_filesystem running
		if ( ! WP_Filesystem($creds) ) {
			// our credentials were no good, ask the user for them again
			request_filesystem_credentials($url, $method, true, false, $form_fields);
			return true;
		}
		
		//set a remote directory
        add_filter('upload_dir', 'my_upload_dir');	
        $upload = wp_upload_dir();
	    remove_filter('upload_dir', 'my_upload_dir');
		
		// get the upload directory and make a test.txt file
		$upload_dir = wp_upload_dir();
		$filename = trailingslashit($upload_dir['path']).'test.txt';

		// by this point, the $wp_filesystem global should be working, so let's use it to create a file
		global $wp_filesystem;
		if ( ! $wp_filesystem->put_contents( $filename, 'Test file contents', FS_CHMOD_FILE) ) {
			echo "error saving file!";
		}
	}
	
	return true;
}


function my_upload_dir($upload) {
	     $upload['subdir'] = '/sub-dir-to-use' . $upload['subdir'];
	     $upload['path']   = $upload['basedir'] . $upload['subdir'];
	     $upload['url']    = $upload['baseurl'] . $upload['subdir'];
	     return $upload;
	   }
?>