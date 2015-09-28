jQuery(document).ready(function() {
 jQuery('#rc_media_upload_button').click(function() {
  formfield = jQuery('#rc_media_upload').attr('name');
  tb_show('', 'media-upload.php?type=image&TB_iframe=true');
  return false;
 });

 window.send_to_editor = function(html) {
  imgurl = jQuery('img',html).attr('src');
  jQuery('#rc_media_upload').val(imgurl);
  tb_remove();
 };

});